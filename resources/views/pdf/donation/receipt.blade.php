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
        <h1>E-KUITANSI {{ strtoupper($donation->type) }}</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <table>
        <tr>
            <td class="label">No. Transaksi</td>
            <td>{{ $donation->midtrans_order_id }}</td>
        </tr>
        <tr>
            <td class="label">Atas Nama</td>
            <td>{{ $donation->donor_name }}</td>
        </tr>
        <tr>
            <td class="label">Jenis</td>
            <td>{{ ucfirst($donation->type) }}</td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td>{{ $donation->payment_method ? strtoupper($donation->payment_method) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pembayaran</td>
            <td>{{ $donation->paid_at?->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        @if ($donation->message)
            <tr>
                <td class="label">Pesan/Doa</td>
                <td>{{ $donation->message }}</td>
            </tr>
        @endif
        <tr>
            <td class="label">Total Dibayar</td>
            <td class="total">Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Kuitansi ini dibuat otomatis oleh sistem dan sah tanpa tanda tangan basah.<br>
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

</body>

</html>
