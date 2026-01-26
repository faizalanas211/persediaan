<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Opname</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 24px 28px;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            letter-spacing: 1px;
        }

        .header .subtitle {
            margin-top: 4px;
            font-size: 11px;
            color: #6b7280;
        }

        .divider {
            margin: 14px 0 18px;
            border-bottom: 1px solid #d1d5db;
        }

        /* ===== META INFO ===== */
        .meta {
            width: 100%;
            margin-bottom: 18px;
        }

        .meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta td {
            padding: 4px 0;
            vertical-align: top;
        }

        .meta .label {
            width: 140px;
            color: #6b7280;
        }

        .status {
            font-weight: bold;
            color: #065f46;
        }

        /* ===== TABLE ===== */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th,
        table.data td {
            border: 1px solid #c7c7c7;
            padding: 6px 6px;
        }

        table.data th {
            background: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }

        table.data td {
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* ===== FOOTER ===== */
        .note {
            margin-top: 18px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <h2>LAPORAN STOK OPNAME</h2>
        <div class="subtitle">
            Periode {{ \Carbon\Carbon::parse($stokOpname->periode_bulan)->translatedFormat('F Y') }}
        </div>
    </div>

    <div class="divider"></div>

    {{-- META --}}
    <div class="meta">
        <table>
            <tr>
                <td class="label">Tanggal Opname</td>
                <td>: {{ \Carbon\Carbon::parse($stokOpname->tanggal_opname)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Dicatat oleh</td>
                <td>: {{ $stokOpname->pencatat->name }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>: <span class="status">FINAL</span></td>
            </tr>
        </table>
    </div>

    {{-- TABEL DATA --}}
    <table class="data">
        <thead>
            <tr>
                <th width="4%">#</th>
                <th>Nama Barang</th>
                <th width="10%">Satuan</th>
                <th width="12%">Stok Sistem</th>
                <th width="12%">Stok Fisik</th>
                <th width="10%">Selisih</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokOpname->detail as $i => $d)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $d->barang->nama_barang }}</td>
                <td class="text-center">{{ $d->barang->satuan }}</td>
                <td class="text-center">{{ $d->stok_sistem }}</td>
                <td class="text-center">{{ $d->stok_fisik }}</td>
                <td class="text-center">{{ $d->selisih }}</td>
                <td>{{ $d->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- CATATAN --}}
    <div class="note">
        <strong>Catatan:</strong><br>
        Dokumen ini dihasilkan secara otomatis oleh sistem persediaan dan
        tidak dapat diubah setelah stok opname difinalisasi.
    </div>

</div>

</body>
</html>
