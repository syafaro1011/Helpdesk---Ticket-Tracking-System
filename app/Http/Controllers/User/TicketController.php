<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;
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
                'data' => $tickets->getCollection()->map(fn ($t) => [
                    'ticket_code' => $t->ticket_code,
                    'title' => $t->title,
                    'description' => $t->description,
                    'category_name' => $t->category->name ?? '-',
                    'priority' => $t->priority,
                    'status' => $t->status,
                    'technician_name' => $t->technician->name ?? null,
                    'created_at' => $t->created_at->format('d M Y, H:i'),
                    'show_url' => route('user.tickets.show', $t->id),
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

    // Menyimpan tiket baru ke database
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);

        // 2. Handle Upload File/Gambar
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        }

        // 3. Generate Kode Tiket Unik (Contoh: TCK-20260901-A1B2)
        $ticketCode = 'TCK-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // 4. Simpan ke Database
        $ticket = Ticket::create([
            'ticket_code' => $ticketCode,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open',
            'attachment' => $attachmentPath,
        ]);

        // 5. Simpan log awal pengajuan
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Pelapor membuat tiket kendala baru: {$request->title}",
        ]);

        return redirect()->route('user.tickets.index')
            ->with('success', "Tiket $ticketCode berhasil dibuat dan sedang menunggu penanganan.");
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
        ]);

        $attachmentPath = $ticket->attachment;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
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