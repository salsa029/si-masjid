<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #1f2937;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header h1 {
            color: #059669;
            margin: 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .label {
            width: 35%;
            color: #6b7280;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>E-KUITANSI INFAQ</h1>
        <p>{{ $mosqueProfile->name ?? 'SI-MASJID' }}</p>
    </div>

    <table>
        <tr>
            <td class="label">No. Transaksi</td>
            <td>: {{ $infaq->transaction_number }}</td>
        </tr>
        <tr>
            <td class="label">Nama Donatur</td>
            <td>: {{ $infaq->display_name }}</td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td>: {{ $infaq->category->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Campaign</td>
            <td>: {{ $infaq->campaign->title ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td>: {{ $infaq->payment_method === 'midtrans' ? 'Online (Midtrans)' : 'Transfer Manual' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Bayar</td>
            <td>: {{ $infaq->paid_at?->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label total">Total Infaq</td>
            <td class="total">: Rp {{ number_format($infaq->amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if ($infaq->message)
        <p style="margin-top:16px;"><strong>Catatan/Doa:</strong> {{ $infaq->message }}</p>
    @endif

    <div class="footer">
        Dokumen ini diterbitkan secara elektronik oleh sistem SI-MASJID dan sah tanpa tanda tangan basah.
    </div>
</body>

</html>
