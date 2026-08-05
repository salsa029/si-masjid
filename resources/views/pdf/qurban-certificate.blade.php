<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        /* Gambar latar (Canva) berukuran 2000x1414px = rasio A4 landscape (297x210mm) persis,
           jadi 1px pada gambar = 0.1485mm pada halaman PDF. Semua posisi overlay di bawah
           dihitung dari koordinat piksel pada gambar asli dikali 0.1485. */
        @page {
            size: a4 landscape;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: sans-serif;
            color: #1e293b;
        }

        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
        }

        .bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
        }

        .overlay {
            position: absolute;
        }

        /* top/left/font-size elemen .year, .recipient-name, .animal-type diatur lewat inline style
           di bawah, dari pengaturan sertifikat per Qurban Activity (bisa diubah admin). */
        .year {
            width: 38mm;
            font-weight: bold;
            color: #1e3a5f;
            text-align: center;
        }

        .recipient-name {
            width: 190mm;
            text-align: center;
            font-weight: bold;
            color: #1e3a5f;
        }

        .animal-type {
            width: 70mm;
            text-align: left;
            font-weight: bold;
            color: #1e3a5f;
        }

        .dkm-signature-img {
            top: 147mm;
            left: 58mm;
            width: 45mm;
            text-align: center;
        }

        .dkm-signature-img img {
            max-height: 15mm;
            max-width: 45mm;
        }

        .dkm-name {
            width: 60mm;
            text-align: center;
            font-weight: bold;
            color: #1e3a5f;
            white-space: nowrap;
        }

        .panitia-signature-img {
            top: 147mm;
            left: 195mm;
            width: 45mm;
            text-align: center;
        }

        .panitia-signature-img img {
            max-height: 15mm;
            max-width: 45mm;
        }

        .panitia-name {
            width: 60mm;
            text-align: center;
            font-weight: bold;
            color: #1e3a5f;
            white-space: nowrap;
        }

        .qr {
            top: 174mm;
            left: 253mm;
            width: 24mm;
            text-align: center;
        }

        .qr img {
            width: 22mm;
            height: 22mm;
        }

        .qr-label {
            top: 196.5mm;
            left: 253mm;
            width: 24mm;
            text-align: center;
            font-size: 6.5px;
            color: #6b7280;
        }

        .cert-number {
            top: 202mm;
            left: 10mm;
            width: 150mm;
            text-align: left;
            font-size: 7px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

    @php
        $certificateActivity = $qurbanOrder->animal->activity;
    @endphp

    <div class="page">
        <img class="bg" src="{{ $backgroundDataUri }}" alt="Sertifikat Qurban">

        <div class="overlay year"
            style="top: {{ $certificateActivity->certificate_year_top ?? 55 }}mm; left: {{ $certificateActivity->certificate_year_left ?? 166 }}mm; font-size: {{ $certificateActivity->certificate_year_font_size ?? 30 }}px;">
            {{ $hijriYear }}</div>

        <div class="overlay recipient-name"
            style="top: {{ $certificateActivity->certificate_name_top ?? 87 }}mm; left: {{ $certificateActivity->certificate_name_left ?? 53 }}mm; font-size: {{ $certificateActivity->certificate_name_font_size ?? 36 }}px;">
            {{ $qurbanOrder->user->name }}</div>

        <div class="overlay animal-type"
            style="top: {{ $certificateActivity->certificate_animal_top ?? 110 }}mm; left: {{ $certificateActivity->certificate_animal_left ?? 179 }}mm; font-size: {{ $certificateActivity->certificate_animal_font_size ?? 21 }}px;">
            {{ ucfirst($qurbanOrder->animal->animal_type) }}</div>

        @if ($dkmSignatureDataUri)
            <div class="overlay dkm-signature-img"><img src="{{ $dkmSignatureDataUri }}" alt="TTD Ketua DKM"></div>
        @endif
        <div class="overlay dkm-name"
            style="top: {{ $certificateActivity->certificate_dkm_name_top ?? 165.5 }}mm; left: {{ $certificateActivity->certificate_dkm_name_left ?? 50 }}mm; font-size: {{ $certificateActivity->certificate_dkm_name_font_size ?? 20 }}px;">
            {{ $certificateActivity?->dkm_chairman_name ?? '' }}</div>

        @if ($qurbanChairmanPhotoDataUri)
            <div class="overlay panitia-signature-img"><img src="{{ $qurbanChairmanPhotoDataUri }}"
                    alt="TTD Ketua Panitia"></div>
        @endif
        <div class="overlay panitia-name"
            style="top: {{ $certificateActivity->certificate_panitia_name_top ?? 165.5 }}mm; left: {{ $certificateActivity->certificate_panitia_name_left ?? 188 }}mm; font-size: {{ $certificateActivity->certificate_panitia_name_font_size ?? 20 }}px;">
            {{ $certificateActivity?->qurban_chairman_name ?? '' }}</div>

        @if ($qrCodeDataUri)
            <div class="overlay qr"><img src="{{ $qrCodeDataUri }}" alt="QR Verifikasi"></div>
            <div class="overlay qr-label">Scan untuk verifikasi</div>
        @endif

        <div class="overlay cert-number">No. Sertifikat: {{ $qurbanOrder->certificate_number }}</div>
    </div>

</body>

</html>
