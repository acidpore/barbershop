<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #171717; font-size: 11px; margin: 0; }
        .wrap { padding: 28px 32px; }
        .head { border-bottom: 2px solid #171717; padding-bottom: 12px; margin-bottom: 20px; }
        .brand { font-size: 22px; font-weight: bold; letter-spacing: -0.5px; }
        .muted { color: #737373; }
        h2 { font-size: 14px; margin: 24px 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: center; padding: 6px 8px; }
        thead th { background: #171717; color: #fff; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody tr:nth-child(even) td { background: #f5f5f5; }
        tbody td { border-bottom: 1px solid #ededed; }
        /* Bar chart pakai div lebar proporsional (aman di dompdf, tanpa JS). */
        .chart td { text-align: left; border: none; padding: 3px 8px; }
        .bar-label { width: 90px; color: #525252; }
        .bar-val { width: 90px; text-align: right; font-weight: bold; }
        .bar-track { background: #ededed; border-radius: 3px; }
        .bar-fill { background: #171717; height: 12px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="head">
        <table>
            <tr>
                <td style="text-align:left; border:none; padding:0;">
                    <div class="brand">DOS. Barber &amp; Supplies</div>
                    <div class="muted">Laporan Operasional</div>
                </td>
                <td style="text-align:right; border:none; padding:0;" class="muted">
                    Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}
                </td>
            </tr>
        </table>
    </div>

    @php $maxDay = $revenueByDay->max('total') ?: 1; @endphp

    <h2>Omzet 14 Hari Terakhir</h2>
    <table class="chart">
        @forelse ($revenueByDay as $row)
            <tr>
                <td class="bar-label">{{ \Illuminate\Support\Carbon::parse($row->day)->format('d M Y') }}</td>
                <td>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ max(2, round($row->total / $maxDay * 100)) }}%;"></div>
                    </div>
                </td>
                <td class="bar-val">Rp{{ number_format($row->total, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td class="muted">Belum ada data.</td></tr>
        @endforelse
    </table>

    <h2>Omzet per Barber</h2>
    <table>
        <thead><tr><th>Barber</th><th>Total Omzet</th></tr></thead>
        <tbody>
            @forelse ($revenueByBarber as $row)
                <tr><td>{{ $row->barber }}</td><td>Rp{{ number_format($row->total, 0, ',', '.') }}</td></tr>
            @empty
                <tr><td colspan="2" class="muted">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Performa per Paket</h2>
    <table>
        <thead><tr><th>Paket</th><th>Jumlah</th><th>Total Omzet</th></tr></thead>
        <tbody>
            @forelse ($revenueByService as $row)
                <tr>
                    <td>{{ $row->service }}</td>
                    <td>{{ $row->qty }}</td>
                    <td>Rp{{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
