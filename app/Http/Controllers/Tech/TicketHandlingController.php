<?php

namespace App\Http\Controllers\Tech;

use App\Exports\TicketsExport;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TicketHandlingController extends Controller
{
    // Menampilkan seluruh tiket masuk (untuk Teknisi & Admin)
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'category', 'technician'])->latest();

        // Jika login sebagai Teknisi, hanya tampilkan tiket yang assigned ke dia atau yang masih OPEN
        if (auth()->user()->role === 'technician') {
            $query->where(function ($q) {
                $q->where('technician_id', auth()->id())
                    ->orWhereNull('technician_id');
            });
        }

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan kode atau judul tiket
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('ticket_code', 'like', "%{$q}%")
                    ->orWhere('title', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $tickets = $query->paginate(10)->withQueryString();

        if ($request->boolean('ajax')) {
            return response()->json([
                'data' => $tickets->getCollection()->map(fn ($t) => [
                    'ticket_code' => $t->ticket_code,
                    'title' => $t->title,
                    'category_name' => $t->category->name ?? '-',
                    'priority' => $t->priority,
                    'status' => $t->status,
                    'technician_name' => $t->technician->name ?? null,
                    'user_name' => $t->user->name ?? '-',
                    'created_at' => $t->created_at->format('d M Y, H:i'),
                    'show_url' => route('tech.tickets.show', $t->id),
                ])->values(),
                'pagination' => $tickets->links()->toHtml(),
                'total' => $tickets->total(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
            ]);
        }

        return view('tech.tickets.index', compact('tickets'));
    }

    // Query tiket dengan filter yang sama seperti index (dipakai print & export)
    protected function filteredTickets(Request $request)
    {
        $query = Ticket::with(['user', 'category', 'technician'])->latest();

        if (auth()->user()->role === 'technician') {
            $query->where(function ($q) {
                $q->where('technician_id', auth()->id())
                    ->orWhereNull('technician_id');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('ticket_code', 'like', "%{$q}%")
                    ->orWhere('title', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%");
                    });
            });
        }

        return $query->get();
    }

    // Halaman print (view & print via browser)
    public function print(Request $request)
    {
        $tickets = $this->filteredTickets($request);

        return view('tech.tickets.print', compact('tickets'));
    }

    // Download Excel (.xlsx) mengikuti filter yang aktif
    public function export(Request $request)
    {
        $status = $request->input('status') ?: null;
        $q = $request->input('q') ?: null;

        $filename = 'tiket-' . ($status ?: 'semua') . '-' . date('Ymd-His') . '.xlsx';

        return Excel::download(
            TicketsExport::fromRequest($request),
            $filename
        );
    }

    // Menampilkan detail tiket beserta form update & log diskusi
    public function show($id)
    {
        $ticket = Ticket::with(['user', 'category', 'technician', 'logs.user'])->findOrFail($id);
        $technicians = User::where('role', 'technician')->get();

        return view('tech.tickets.show', compact('ticket', 'technicians'));
    }

    // Mengubah Status Tiket & Penugasan Teknisi
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'technician_id' => 'nullable|exists:users,id',
            'message' => 'nullable|string',
        ]);

        // Auto assign ke teknisi yang sedang login jika belum ada penanggung jawabnya
        $technicianId = $request->technician_id ?? $ticket->technician_id;
        if (!$technicianId && auth()->user()->role === 'technician') {
            $technicianId = auth()->id();
        }

        // Update data tiket
        $ticket->update([
            'status' => $request->status,
            'technician_id' => $technicianId,
        ]);

        // Catat pesan/perubahan ke Ticket Log jika ada pesan
        if ($request->filled('message')) {
            TicketLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $request->message,
            ]);
        }

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function assignTechnician(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'technician_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $ticket = Ticket::findOrFail($id);
        $technician = User::findOrFail($request->technician_id);

        // Pastikan user yang dipilih benar-benar berperan sebagai teknisi
        if ($technician->role !== 'technician') {
            return redirect()->back()->with('error', 'User yang dipilih bukan seorang teknisi.');
        }

        // 2. Update Teknisi PJ & Status Tiket (jika status masih Open, otomatis ubah ke In Progress)
        $oldTechnician = $ticket->technician ? $ticket->technician->name : 'Belum Ada';
        $newStatus = ($ticket->status === 'open') ? 'in_progress' : $ticket->status;

        $ticket->update([
            'technician_id' => $technician->id,
            'status' => $newStatus,
        ]);

        // 3. Catat Aktivitas Penugasan ke Ticket Log
        $logMessage = "Admin menugaskan tiket ini kepada teknisi: {$technician->name}. (Teknisi sebelumnya: {$oldTechnician})";
        if ($request->filled('notes')) {
            $logMessage .= " | Catatan: {$request->notes}";
        }

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $logMessage,
        ]);

        return redirect()->back()->with('success', "Berhasil menugaskan tiket kepada {$technician->name}.");
    }
}