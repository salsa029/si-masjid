<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>E-Kuitansi Zakat</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 20px;
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

        .header p {
            margin: 4px 0 0 0;
            font-size: 14px;
            color: #374151;
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
            font-weight: 500;
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
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60px;
            color: rgba(5, 150, 105, 0.08);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .zakat-detail-box {
            margin-top: 12px;
            padding: 12px;
            background-color: #fefce8;
            border-radius: 4px;
            border-left: 3px solid #f59e0b;
        }

        .zakat-detail-box p {
            margin: 4px 0;
            font-size: 11px;
            color: #78350f;
        }
    </style>
</head>

<body>
    <div class="watermark">SI-MASJID</div>
    <div class="content">
        <div class="header">
            <h1>E-KUITANSI ZAKAT</h1>
            <p>{{ $mosqueProfile->name ?? 'SI-MASJID' }}</p>
            <p style="font-size: 10px; color: #6b7280; margin-top: 4px;">
                {{ $mosqueProfile->address ?? '' }}
            </p>
        </div>

        <table>
            <tr>
                <td class="label">No. Transaksi</td>
                <td>: {{ $zakat->transaction_number }}</td>
            </tr>
            <tr>
                <td class="label">Nama Muzakki</td>
                <td>: {{ $zakat->display_name }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Zakat</td>
                <td>: {{ $zakat->zakatType->name ?? '-' }}</td>
            </tr>
            @if ($zakat->number_of_souls)
                <tr>
                    <td class="label">Jumlah Jiwa</td>
                    <td>: {{ $zakat->number_of_souls }} jiwa</td>
                </tr>
            @endif
            @if ($zakat->calculation_details)
                <tr>
                    <td class="label">Detail Perhitungan</td>
                    <td>: {{ $zakat->calculation_details }}</td>
                </tr>
            @endif
            <tr>
                <td class="label">Metode Pembayaran</td>
                <td>: {{ ucfirst(str_replace('_', ' ', $zakat->payment_method)) }}</td>
            </tr>
            <tr>
                <td class="label">Status Pembayaran</td>
                <td>:
                    <span class="status-badge status-success">BERHASIL</span>
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal Bayar</td>
                <td>: {{ $zakat->paid_at?->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label total">Total Zakat</td>
                <td class="total">: Rp {{ number_format($zakat->amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if ($zakat->zakatType && $zakat->zakatType->calculation_unit === 'per_soul')
            <div class="zakat-detail-box">
                <p><strong>Detail Zakat Fitrah:</strong></p>
                <p>• Jumlah Jiwa: {{ $zakat->number_of_souls ?? 0 }} orang</p>
                <p>• Nominal per Jiwa: Rp
                    {{ number_format($zakat->amount / ($zakat->number_of_souls ?? 1), 0, ',', '.') }}</p>
                <p>• Total: Rp {{ number_format($zakat->amount, 0, ',', '.') }}</p>
            </div>
        @endif

        <div
            style="margin-top: 16px; padding: 12px; background-color: #f0fdf4; border-radius: 4px; border-left: 3px solid #059669;">
            <p style="margin: 0; font-size: 11px; color: #065f46;">
                <strong>Semoga zakat Anda diterima dan menjadi pembersih jiwa.</strong>
                Jazakumullahu khairan katsiran.
            </p>
        </div>

        <div class="footer">
            <p>Dokumen ini diterbitkan secara elektronik oleh sistem SI-MASJID dan sah tanpa tanda tangan basah.</p>
            <p style="margin-top: 4px;">
                Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </p>
            <p style="margin-top: 8px; font-size: 8px; color: #d1d5db;">
                Kode Verifikasi: {{ substr(md5($zakat->id . $zakat->transaction_number), 0, 8) }}
            </p>
        </div>
    </div>
</body>

</html>
