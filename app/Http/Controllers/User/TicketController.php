<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // Menampilkan daftar tiket milik user yang sedang login
    public function index(Request $request)
    {
        $tickets = Ticket::with(['category', 'technician', 'logs.user'])
            ->where('user_id', auth()->id())
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('ticket_code', 'like', "%{$q}%")
                        ->orWhere('title', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->boolean('ajax')) {
            return response()->json([
                'data' => $tickets->getCollection()->map(fn($t) => [
                    'id' => $t->id,
                    'ticket_code' => $t->ticket_code,
                    'title' => $t->title,
                    'description' => $t->description,
                    'category_name' => $t->category->name ?? '-',
                    'priority' => $t->priority,
                    'status' => $t->status,
                    'technician_name' => $t->technician->name ?? null,
                    'created_at' => $t->created_at->format('d M Y, H:i'),
                    'show_url' => route('user.tickets.show', $t->id),
                    'edit_url' => route('user.tickets.edit', $t->id),
                    'destroy_url' => route('user.tickets.destroy', $t->id),
                    'can_edit' => $t->status === 'open',
                ])->values(),
                'pagination' => $tickets->links()->toHtml(),
                'total' => $tickets->total(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
            ]);
        }

        return view('user.tickets.index', compact('tickets'));
    }

    // Menampilkan detail tiket milik user beserta riwayat pengerjaan
    public function show($id)
    {
        $ticket = Ticket::with(['category', 'technician', 'logs.user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('user.tickets.show', compact('ticket'));
    }

    // Mengirim balasan/catatan dari user ke teknisi
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Tanggapan/balasan Anda berhasil dikirim.');
    }

    // CREATE
    public function create()
    {
        $categories = Category::all();
        return view('user.tickets.create', compact('categories'));
    }

    // Menyimpan tiket baru ke database (mendukung multi-tiket via jquery.repeater)
    public function store(Request $request)
    {
        // Validasi Input (satu pengajuan bisa berisi 1-10 tiket)
        $validated = $request->validate([
            'tickets' => 'required|array|min:1|max:10',
            'tickets.*.category_id' => 'required|exists:categories,id',
            'tickets.*.title' => 'required|string|max:255',
            'tickets.*.description' => 'required|string',
            'tickets.*.priority' => 'required|in:low,medium,high,urgent',
            'tickets.*.attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB per file
        ]);

        // Satu pengajuan = satu transaksi: gagal di tengah jalan -> semua batal
        $storedFiles = [];

        try {
            $createdCodes = DB::transaction(function () use ($request, $validated, &$storedFiles) {
                $codes = [];

                foreach ($validated['tickets'] as $index => $ticketData) {
                    // Handle Upload File/Gambar per tiket
                    $attachmentPath = null;
                    $file = $request->file("tickets.$index.attachment");
                    if ($file && $file->isValid()) {
                        $attachmentPath = $file->store('tickets', 'public');
                        $storedFiles[] = $attachmentPath;
                    }

                    // Generate Kode Tiket Unik (Contoh: TCK-20260901-A1B2)
                    do {
                        $ticketCode = 'TCK-' . date('Ymd') . '-' . strtoupper(Str::random(4));
                    } while (Ticket::where('ticket_code', $ticketCode)->exists());

                    // Simpan ke Database
                    $ticket = Ticket::create([
                        'ticket_code' => $ticketCode,
                        'user_id' => auth()->id(),
                        'category_id' => $ticketData['category_id'],
                        'title' => $ticketData['title'],
                        'description' => $ticketData['description'],
                        'priority' => $ticketData['priority'],
                        'status' => 'open',
                        'attachment' => $attachmentPath,
                    ]);

                    // Simpan log awal pengajuan
                    TicketLog::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => auth()->id(),
                        'message' => "Pelapor membuat tiket kendala baru: {$ticketData['title']}",
                    ]);

                    $codes[] = $ticketCode;
                }

                return $codes;
            });
        } catch (\Throwable $e) {
            foreach ($storedFiles as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }

        $count = count($createdCodes);
        $message = $count === 1
            ? "Tiket {$createdCodes[0]} berhasil dibuat dan sedang menunggu penanganan."
            : "$count tiket berhasil dibuat dan sedang menunggu penanganan: " . implode(', ', $createdCodes);

        return redirect()->route('user.tickets.index')
            ->with('success', $message);
    }

    // Menampilkan form edit tiket (hanya jika status masih open)
    public function edit($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);

        if ($ticket->status !== 'open') {
            return redirect()->route('user.tickets.show', $ticket->id)
                ->with('error', 'Tiket ini sudah diproses dan tidak dapat diubah lagi.');
        }

        $categories = Category::all();
        return view('user.tickets.edit', compact('ticket', 'categories'));
    }

    // Memperbarui data tiket di database (hanya jika status masih open)
    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);

        if ($ticket->status !== 'open') {
            return redirect()->route('user.tickets.show', $ticket->id)
                ->with('error', 'Tiket ini sudah diproses dan tidak dapat diubah lagi.');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_attachment' => 'nullable|boolean',
        ]);

        $attachmentPath = $ticket->attachment;
        if ($request->hasFile('attachment')) {
            // Hapus file lama jika diganti dengan yang baru
            if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
                Storage::disk('public')->delete($ticket->attachment);
            }
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        } elseif ($request->boolean('remove_attachment')) {
            // Hapus file lama jika user mencentang hapus gambar
            if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
                Storage::disk('public')->delete($ticket->attachment);
            }
            $attachmentPath = null;
        }

        $ticket->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'attachment' => $attachmentPath,
        ]);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => 'Pelapor memperbarui informasi tiket kendala.',
        ]);

        return redirect()->route('user.tickets.show', $ticket->id)
            ->with('success', 'Data tiket berhasil diperbarui.');
    }

    // Menghapus tiket kendala (hanya jika status masih open)
    public function destroy($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);

        if ($ticket->status !== 'open') {
            return redirect()->route('user.tickets.show', $ticket->id)
                ->with('error', 'Tiket ini sudah diproses dan tidak dapat dihapus lagi.');
        }

        $ticketCode = $ticket->ticket_code;

        // Hapus file lampiran jika ada
        if ($ticket->attachment && Storage::disk('public')->exists($ticket->attachment)) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        // Hapus tiket dari database (TicketLog akan terhapus otomatis via cascade)
        $ticket->delete();

        return redirect()->route('user.tickets.index')
            ->with('success', "Tiket #{$ticketCode} berhasil dihapus.");
    }
}