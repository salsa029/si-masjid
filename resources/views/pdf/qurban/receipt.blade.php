<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #047857;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #047857;
            margin: 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        table td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .label {
            color: #888;
            width: 40%;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            color: #047857;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>E-KUITANSI KURBAN</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <table>
        <tr>
            <td class="label">No. Transaksi</td>
            <td>{{ $qurbanOrder->midtrans_order_id }}</td>
        </tr>
        <tr>
            <td class="label">Hewan Kurban</td>
            <td>{{ $qurbanOrder->animal->name }} ({{ ucfirst($qurbanOrder->animal->animal_type) }})</td>
        </tr>
        <tr>
            <td class="label">Jenis Pesanan</td>
            <td>{{ $qurbanOrder->order_type === 'full' ? 'Beli Penuh' : 'Patungan' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pembayaran</td>
            <td>{{ $qurbanOrder->paid_at?->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Total Dibayar</td>
            <td class="total">Rp {{ number_format($qurbanOrder->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Kuitansi ini dibuat otomatis oleh sistem dan sah tanpa tanda tangan basah.<br>
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

</body>

</html>
