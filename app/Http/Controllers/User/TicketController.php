<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // Menampilkan daftar tiket milik user yang sedang login
    public function index()
    {
        $tickets = Ticket::with(['category', 'technician', 'logs.user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

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

    // Menampilkan form buat tiket baru
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
}