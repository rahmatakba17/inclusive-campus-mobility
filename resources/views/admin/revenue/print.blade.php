<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemasukan (Print)</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; padding: 20px; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #555; }
        .meta-info { margin-bottom: 20px; }
        .meta-info table { width: 100%; border: none; }
        .meta-info td { padding: 3px 0; }
        
        .stats-box { border: 1px solid #000; padding: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; }
        .stat-item { text-align: center; }
        .stat-item h4 { margin: 0; font-size: 12px; font-weight: normal; text-transform: uppercase; color: #555; }
        .stat-item p { margin: 5px 0 0; font-size: 18px; font-weight: bold; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
        .data-table th { background-color: #f0f0f0; font-weight: bold; text-transform: uppercase; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .footer { margin-top: 40px; display: flex; justify-content: flex-end; }
        .signature { text-align: center; width: 250px; }
        .signature p { margin: 0; }
        .signature .line { margin-top: 60px; border-bottom: 1px solid #000; width: 100%; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">⬇ Print Dokumen</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">Tutup</button>
    </div>

    <div class="header">
        <h1>LAPORAN PEMASUKAN BUS KAMPUS</h1>
        <p>Kampus Non-Merdeka Terintegrasi</p>
    </div>

    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120"><strong>Periode Laporan</strong></td>
                <td>: {{ $periodeLabel }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>: {{ date('d F Y H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Pencetak</strong></td>
                <td>: {{ auth()->user()->name }} (Administrator)</td>
            </tr>
        </table>
    </div>

    <div class="stats-box">
        <div class="stat-item">
            <h4>Total Pemasukan</h4>
            <p>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="stat-item">
            <h4>Total Pembayaran QRIS</h4>
            <p>Rp {{ number_format($stats['total_qris'], 0, ',', '.') }}</p>
        </div>
        <div class="stat-item">
            <h4>Total Pembayaran E-Toll</h4>
            <p>Rp {{ number_format($stats['total_etoll'], 0, ',', '.') }}</p>
        </div>
        <div class="stat-item">
            <h4>Tiket Terjual</h4>
            <p>{{ collect($revenues)->count() }} Transaksi</p>
        </div>
    </div>

    <h3 style="margin-bottom: 5px; font-size: 14px; text-transform: uppercase;">Detail Riwayat Pemasukan (Harga > Rp0)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th>Tanggal Transaksi</th>
                <th>Kode Booking</th>
                <th>Nama Penumpang</th>
                <th>Tipe/Nomor Bus</th>
                <th class="text-center">Metode Bayar</th>
                <th class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenues as $index => $r)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $r->booking_date->format('d/m/Y') }} {{ $r->created_at->format('H:i') }}</td>
                <td>{{ $r->booking_code }}</td>
                <td>{{ $r->passenger_name }}</td>
                <td>Bus {{ $r->bus->bus_number }} ({{ $r->bus->name }})</td>
                <td class="text-center" style="text-transform: uppercase;">{{ $r->payment_method }}</td>
                <td class="text-right">{{ number_format($r->price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada catatan pemasukan tarif pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($revenues) > 0)
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">TOTAL KESELURUHAN</th>
                <th class="text-right">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <div class="signature">
            <p>Makassar, {{ date('d F Y') }}</p>
            <p style="margin-top: 5px;">Mengetahui,</p>
            <div class="line"></div>
            <p><strong>Pusat Administrasi Bus Kampus</strong></p>
        </div>
    </div>

</body>
</html>
