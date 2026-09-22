<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\User;
use App\Services\GeminiService;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramWebhookController extends Controller
{
    protected GeminiService $geminiService;
    protected TelegramService $telegramService;

    public function __construct(GeminiService $geminiService, TelegramService $telegramService)
    {
        $this->geminiService = $geminiService;
        $this->telegramService = $telegramService;
    }

    /**
     * Handle incoming webhook update from Telegram.
     */
    public function handle(Request $request): JsonResponse
    {
        $chatId = $request->input('message.chat.id');
        $text = trim($request->input('message.text', ''));

        if (!$chatId || $text === '') {
            return response()->json(['status' => 'ignored', 'reason' => 'No message or chat id']);
        }

        // Handle perintah /start
        if (str_starts_with($text, '/start')) {
            return $this->handleStartCommand($chatId, $text);
        }

        // Handle pesan aduan kendala dari user
        return $this->handleTicketCreation($chatId, $text);
    }

    /**
     * Penautan Akun Karyawan via perintah /start {KODE} (Metode 1)
     */
    protected function handleStartCommand($chatId, string $text): JsonResponse
    {
        // Cek dulu apakah Chat ID ini SUDAH terhubung dengan user mana pun
        $existingUser = User::where('telegram_chat_id', (string) $chatId)->first();

        $parts = explode(' ', $text);
        $code = isset($parts[1]) ? trim($parts[1]) : null;

        if ($code) {
            $user = User::where('telegram_verification_code', $code)->first();

            if ($user) {
                // Tautkan akun
                $user->update([
                    'telegram_chat_id' => (string) $chatId,
                    'telegram_verification_code' => null,
                ]);

                $reply = "<b>✅ Penautan Akun Berhasil!</b>\n\n" .
                    "Halo <b>" . e($user->name) . "</b> (" . e($user->email) . "), akun Telegram Anda telah resmi terhubung ke sistem Helpdesk.\n\n" .
                    "Anda sekarang dapat langsung menuliskan kendala IT di chat ini kapan saja, dan AI kami akan membuatkan tiket otomatis.";

                $this->telegramService->sendMessage($chatId, $reply);

                return response()->json(['status' => 'linked', 'user' => $user->name], 200);
            }

            // Jika user ternyata sudah terhubung sebelumnya
            if ($existingUser) {
                $reply = "<b>Halo " . e($existingUser->name) . "! 👋</b>\n\n" .
                    "Akun Telegram Anda sudah terhubung ke sistem Helpdesk. Silakan langsung tuliskan kendala IT yang Anda alami.";

                $this->telegramService->sendMessage($chatId, $reply);

                return response()->json(['status' => 'already_linked'], 200);
            }

            $reply = "<b>❌ Kode Tautan Tidak Valid</b>\n\n" .
                "Kode tautan tidak ditemukan atau sudah pernah digunakan. Silakan buat kode tautan baru melalui menu <b>Profil</b> di Web Helpdesk.";

            $this->telegramService->sendMessage($chatId, $reply);

            return response()->json(['status' => 'invalid_code'], 200);
        }

        // Jika hanya mengetik /start tanpa kode
        if ($existingUser) {
            $reply = "<b>Halo " . e($existingUser->name) . "! 👋</b>\n\n" .
                "Akun Telegram Anda sudah terhubung. Silakan langsung ketikkan kendala IT yang Anda alami.";
        } else {
            $reply = "<b>Welcome to IT Helpdesk Bot! 🤖</b>\n\n" .
                "Akun Telegram Anda belum terhubung ke akun karyawan.\n\n" .
                "<b>Langkah Penautan:</b>\n" .
                "1. Login ke Web Helpdesk.\n" .
                "2. Masuk ke menu <b>Profil</b>.\n" .
                "3. Klik <b>Generate Kode Tautan Telegram</b>.\n" .
                "4. Kirimkan kode tersebut di sini dengan format: <code>/start TG-XXXXXX</code>";
        }

        $this->telegramService->sendMessage($chatId, $reply);

        return response()->json(['status' => 'start_info_sent'], 200);
    }

    /**
     * Ekstraksi AI & Pembuatan Tiket Otomatis
     */
    protected function handleTicketCreation($chatId, string $text): JsonResponse
    {
        // 1. Verifikasi Keamanan Akun Karyawan
        $user = User::where('telegram_chat_id', (string) $chatId)->first();

        if (!$user) {
            $reply = "<b>⚠️ Akun Belum Terhubung</b>\n\n" .
                "Mohon maaf, Anda belum menautkan akun Telegram dengan akun karyawan Helpdesk.\n\n" .
                "Silakan buat kode tautan di menu <b>Profil Web Helpdesk</b> terlebih dahulu, lalu kirimkan pesan: <code>/start TG-XXXXXX</code> ke bot ini.";

            $this->telegramService->sendMessage($chatId, $reply);

            return response()->json(['status' => 'unauthorized_user'], 200);
        }

        // 2. Ekstraksi Pesan Tidak Terstruktur dengan Gemini AI
        $extracted = $this->geminiService->parseUnstructuredMessage($text);

        // 3. Pencocokan Kategori di Database
        $categoryName = $extracted['category'] ?? 'Hardware';
        $category = $this->matchCategory($categoryName);

        // 4. Generate Kode Tiket Unik
        $ticketCode = 'TCK-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // 5. Simpan Tiket Baru
        $ticket = Ticket::create([
            'ticket_code' => $ticketCode,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => $extracted['title'],
            'description' => $extracted['description'] ?? $text,
            'priority' => strtolower($extracted['priority'] ?? 'medium'),
            'status' => 'open',
        ]);

        // 6. Catat Log Awal Tiket
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'Tiket otomatis dibuat via Telegram Bot AI oleh karyawan.',
        ]);

        // 7. Kirim Pesan Konfirmasi Balasan ke Telegram
        $reply = "<b>🎫 Tiket Helpdesk Berhasil Dibuat!</b>\n\n" .
            "<b>Kode Tiket:</b> <code>" . e($ticket->ticket_code) . "</code>\n" .
            "<b>Judul:</b> " . e($ticket->title) . "\n" .
            "<b>Kategori:</b> " . e($category->name ?? '-') . "\n" .
            "<b>Prioritas:</b> " . strtoupper($ticket->priority) . "\n" .
            "<b>Status:</b> OPEN\n\n" .
            "<i>Teknisi kami telah menerima laporan ini dan akan segera memprosesnya. Anda dapat mengecek perkembangan tiket melalui Web Helpdesk.</i>";

        $this->telegramService->sendMessage($chatId, $reply);

        return response()->json([
            'success' => true,
            'ticket_code' => $ticket->ticket_code,
            'extracted_data' => $extracted,
        ]);
    }

    /**
     * Pencocokan Cerdas Nama Kategori AI dengan Model Category Database
     */
    protected function matchCategory(string $aiCategory): Category
    {
        $catLower = mb_strtolower($aiCategory);

        if (str_contains($catLower, 'jaringan') || str_contains($catLower, 'network') || str_contains($catLower, 'internet') || str_contains($catLower, 'wifi')) {
            return Category::where('name', 'LIKE', '%Jaringan%')->first() ?? Category::first();
        }

        if (str_contains($catLower, 'akun') || str_contains($catLower, 'account') || str_contains($catLower, 'otentikasi') || str_contains($catLower, 'auth') || str_contains($catLower, 'access') || str_contains($catLower, 'password')) {
            return Category::where('name', 'LIKE', '%Akun%')->first() ?? Category::first();
        }

        if (str_contains($catLower, 'lunak') || str_contains($catLower, 'software') || str_contains($catLower, 'aplikasi') || str_contains($catLower, 'app')) {
            return Category::where('name', 'LIKE', '%Software%')->first() ?? Category::first();
        }

        if (str_contains($catLower, 'keras') || str_contains($catLower, 'hardware') || str_contains($catLower, 'perangkat keras')) {
            return Category::where('name', 'LIKE', '%Hardware%')->first() ?? Category::first();
        }

        return Category::where('name', 'LIKE', '%' . $aiCategory . '%')->first() ?? Category::first();
    }
}
