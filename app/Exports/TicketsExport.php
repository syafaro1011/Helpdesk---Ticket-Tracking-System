<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected ?string $status;
    protected ?string $q;
    protected ?int $viewerId;
    protected string $viewerRole;

    public function __construct(?string $status = null, ?string $q = null, ?int $viewerId = null, string $viewerRole = 'admin')
    {
        $this->status = $status;
        $this->q = $q;
        $this->viewerId = $viewerId;
        $this->viewerRole = $viewerRole;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('status') ?: null,
            $request->input('q') ?: null,
            auth()->id(),
            auth()->user()->role ?? 'admin',
        );
    }

    public function collection(): Enumerable
    {
        $query = Ticket::with(['user', 'category', 'technician'])->latest();

        // Samakan scope dengan halaman index: teknisi hanya lihat miliknya / belum ada PJ
        if ($this->viewerRole === 'technician' && $this->viewerId) {
            $query->where(function ($q) {
                $q->where('technician_id', $this->viewerId)
                    ->orWhereNull('technician_id');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->q) {
            $q = $this->q;
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

    public function headings(): array
    {
        return [
            'Kode Tiket',
            'Judul Kendala',
            'Pelapor',
            'Kategori',
            'Prioritas',
            'Status',
            'Teknisi PJ',
            'Tanggal Dibuat',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_code,
            $ticket->title,
            $ticket->user->name ?? '-',
            $ticket->category->name ?? '-',
            strtoupper($ticket->priority),
            $ticket->status === 'in_progress' ? 'IN PROGRESS' : strtoupper($ticket->status),
            $ticket->technician->name ?? 'Belum Ada PJ',
            $ticket->created_at?->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Tiket';
    }
}
