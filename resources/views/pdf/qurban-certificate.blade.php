<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            color: #333;
        }

        .certificate {
            border: 8px double #047857;
            padding: 40px;
            text-align: center;
        }

        .certificate h1 {
            color: #047857;
            font-size: 22px;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .certificate .subtitle {
            font-size: 12px;
            color: #888;
            margin-bottom: 30px;
        }

        .certificate .name {
            font-size: 26px;
            font-weight: bold;
            margin: 20px 0;
            color: #111;
        }

        .certificate .detail {
            font-size: 13px;
            margin-top: 20px;
            line-height: 1.8;
        }

        .certificate .number {
            margin-top: 40px;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="certificate">
        <h1>SERTIFIKAT KURBAN</h1>
        <p class="subtitle">{{ config('app.name') }}</p>

        <p style="font-size: 13px;">Dengan ini menyatakan bahwa</p>
        <p class="name">{{ $qurbanOrder->user->name }}</p>
        <p style="font-size: 13px;">
            telah melaksanakan ibadah kurban
            @if ($qurbanOrder->order_type === 'patungan')
                secara patungan
            @endif
            atas seekor <strong>{{ ucfirst($qurbanOrder->animal->animal_type) }}</strong>
            ({{ $qurbanOrder->animal->name }})
        </p>

        <div class="detail">
            No. Invoice: {{ $qurbanOrder->invoice_number }}<br>
            Tanggal Pembayaran: {{ $qurbanOrder->paid_at?->translatedFormat('d F Y') }}<br>
            Nominal: Rp {{ number_format($qurbanOrder->total_amount, 0, ',', '.') }}
        </div>

        <p class="number">No. Sertifikat: {{ $qurbanOrder->certificate_number }}</p>
        <p class="number">Dicetak otomatis oleh sistem pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

</body>

</html>
