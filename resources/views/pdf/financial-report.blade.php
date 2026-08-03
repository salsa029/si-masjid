<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #047857;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .header h1 {
            color: #047857;
            margin: 0;
            font-size: 16px;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            padding: 6px 10px;
        }

        .summary .box {
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .summary .value {
            font-size: 13px;
            font-weight: bold;
            color: #047857;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        table.data th,
        table.data td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        table.data th {
            background: #f3f4f6;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 6px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>{{ config('app.name') }}</p>
        <p style="font-size: 10px; color: #888;">
            Periode: {{ $filters['date_from'] ?? 'Awal' }} s/d {{ $filters['date_to'] ?? 'Sekarang' }}
            &middot; Status: {{ $filters['status'] ? ucfirst($filters['status']) : 'Semua' }}
        </p>
    </div>

    <table class="summary">
        <tr>
            <td width="33%" class="box">
                <div>Total Infaq</div>
                <div class="value">Rp {{ number_format($summary['total_infaq'], 0, ',', '.') }}</div>
            </td>
            <td width="33%" class="box">
                <div>Total Zakat</div>
                <div class="value">Rp {{ number_format($summary['total_zakat'], 0, ',', '.') }}</div>
            </td>
            <td width="33%" class="box">
                <div>Total Kurban</div>
                <div class="value">Rp {{ number_format($summary['total_qurban'], 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <p class="section-title">Data Infaq</p>
    <table class="data">
        <thead>
            <tr>
                <th>No. Transaksi</th>
                <th>Donatur</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($infaqs as $infaq)
                <tr>
                    <td>{{ $infaq->midtrans_order_id }}</td>
                    <td>{{ $infaq->donor_name }}</td>
                    <td>{{ ucfirst($infaq->type) }}</td>
                    <td>Rp {{ number_format($infaq->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($infaq->payment_status) }}</td>
                    <td>{{ $infaq->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data infaq.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="section-title">Data Zakat</p>
    <table class="data">
        <thead>
            <tr>
                <th>No. Transaksi</th>
                <th>Donatur</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($zakats as $zakat)
                <tr>
                    <td>{{ $zakat->midtrans_order_id }}</td>
                    <td>{{ $zakat->donor_name }}</td>
                    <td>{{ ucfirst($zakat->type) }}</td>
                    <td>Rp {{ number_format($zakat->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($zakat->payment_status) }}</td>
                    <td>{{ $zakat->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data zakat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="section-title">Data Pesanan Kurban</p>
    <table class="data">
        <thead>
            <tr>
                <th>No. Transaksi</th>
                <th>Hewan</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($qurbanOrders as $order)
                <tr>
                    <td>{{ $order->midtrans_order_id }}</td>
                    <td>{{ $order->animal->name }}</td>
                    <td>{{ $order->order_type === 'full' ? 'Penuh' : 'Patungan' }}</td>
                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                    <td>{{ $order->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data kurban.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="font-size: 10px; color: #999; text-align: center; margin-top: 20px;">
        Laporan dicetak otomatis oleh sistem pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </p>

</body>

</html>
