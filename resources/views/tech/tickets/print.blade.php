<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tiket - {{ date('d/m/Y H:i') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111; padding: 24px; }
        .header { text-align: center; margin-bottom: 8px; }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #555; }
        .meta { display: flex; justify-content: space-between; font-size: 11px; color: #444; margin: 12px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; font-size: 11px; text-transform: uppercase; }
        td { font-size: 11px; }
        .footer { margin-top: 24px; display: flex; justify-content: flex-end; }
        .sign { text-align: center; font-size: 11px; }
        .toolbar { display: flex; gap: 8px; margin-bottom: 16px; }
        .toolbar button, .toolbar a {
            padding: 8px 16px; font-size: 12px; border-radius: 6px; cursor: pointer;
            text-decoration: none; border: 1px solid #ccc; background: #fff; color: #111;
        }
        .toolbar .primary { background: #0284c7; border-color: #0284c7; color: #fff; }
        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="primary" onclick="window.print()">🖨️ Print / Simpan PDF</button>
        <a href="{{ route('tech.tickets.index', array_filter(['status' => request('status'), 'q' => request('q')])) }}">← Kembali</a>
    </div>

    <div class="header">
        <h1>Laporan Data Tiket Kendala IT</h1>
        <p>
            Dicetak: {{ date('d M Y, H:i') }} oleh {{ auth()->user()->name ?? '-' }}
            @if(request('status'))
                | Status: {{ strtoupper(request('status') === 'in_progress' ? 'IN PROGRESS' : request('status')) }}
            @endif
            @if(request('q'))
                | Pencarian: "{{ request('q') }}"
            @endif
        </p>
    </div>

    <div class="meta">
        <span>Total: <strong>{{ $tickets->count() }} tiket</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:28px">No</th>
                <th>Kode</th>
                <th>Judul Kendala</th>
                <th>Pelapor</th>
                <th>Kategori</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Teknisi PJ</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $i => $ticket)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>#{{ $ticket->ticket_code }}</strong></td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->user->name ?? '-' }}</td>
                    <td>{{ $ticket->category->name ?? '-' }}</td>
                    <td>{{ strtoupper($ticket->priority) }}</td>
                    <td>{{ $ticket->status === 'in_progress' ? 'IN PROGRESS' : strtoupper($ticket->status) }}</td>
                    <td>{{ $ticket->technician->name ?? 'Belum Ada PJ' }}</td>
                    <td>{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding: 24px; color:#777;">Tidak ada data tiket sesuai filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="sign">
            <p>Mengetahui,</p>
            <br><br><br>
            <p><strong>( ........................................ )</strong></p>
        </div>
    </div>
</body>
</html>
