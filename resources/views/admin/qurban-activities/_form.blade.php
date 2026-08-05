@csrf

<div class="space-y-5">
    {{-- Row 1: Nama Kegiatan --}}
    <div>
        <label class="mb-1 block text-sm font-medium">Qurban Nama</label>
        <input type="text" name="name" value="{{ old('name', $qurbanActivity->name ?? '') }}"
            placeholder="Contoh: Qurban 2026" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Row 1b: Periode (Tanggal Mulai & Tanggal Selesai) --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Tanggal Mulai</label>
            <input type="date" name="start_date"
                value="{{ old('start_date', isset($qurbanActivity) ? $qurbanActivity->start_date?->format('Y-m-d') : '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <p class="mt-1 text-xs text-gray-400">Katalog kurban otomatis tampil di halaman publik mulai tanggal ini.
            </p>
            @error('start_date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Tanggal Selesai</label>
            <input type="date" name="end_date"
                value="{{ old('end_date', isset($qurbanActivity) ? $qurbanActivity->end_date?->format('Y-m-d') : '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <p class="mt-1 text-xs text-gray-400">Katalog & pemesanan otomatis ditutup sehari setelah tanggal ini.</p>
            @error('end_date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 2: Deskripsi --}}
    <div>
        <label class="mb-1 block text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
            placeholder="Contoh: Terima kasih kami ucapkan atas kepercayaannya menitipkan hewan qurban di Masjid...">{{ old('description', $qurbanActivity->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Row 3: Ketua DKM & TTD Ketua DKM --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Ketua DKM</label>
            <input type="text" name="dkm_chairman_name"
                value="{{ old('dkm_chairman_name', $qurbanActivity->dkm_chairman_name ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('dkm_chairman_name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">TTD Ketua DKM</label>

            @if (!empty($qurbanActivity) && $qurbanActivity->dkm_chairman_signature)
                <img src="{{ Storage::url($qurbanActivity->dkm_chairman_signature) }}"
                    class="mb-2 h-16 w-32 rounded-lg border border-gray-200 object-contain bg-white">
            @endif

            <input type="file" name="dkm_chairman_signature" accept="image/*" class="w-full text-sm">
            <p class="mt-1 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
            @error('dkm_chairman_signature')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 4: Ketua Kurban & Foto Ketua Kurban --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Ketua Kurban</label>
            <input type="text" name="qurban_chairman_name"
                value="{{ old('qurban_chairman_name', $qurbanActivity->qurban_chairman_name ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('qurban_chairman_name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Foto/TTD Ketua Kurban</label>

            @if (!empty($qurbanActivity) && $qurbanActivity->qurban_chairman_photo)
                <img src="{{ Storage::url($qurbanActivity->qurban_chairman_photo) }}"
                    class="mb-2 h-16 w-32 rounded-lg border border-gray-200 object-contain bg-white">
            @endif

            <input type="file" name="qurban_chairman_photo" accept="image/*" class="w-full text-sm">
            <p class="mt-1 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
            @error('qurban_chairman_photo')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 4b: Pengaturan Posisi & Ukuran Teks Sertifikat --}}
    @php
        $certFields = [
            'name' => ['label' => 'Nama Penerima', 'default_top' => 87, 'default_left' => 53, 'default_size' => 36, 'width_mm' => 190],
            'year' => ['label' => 'Tahun Hijriah', 'default_top' => 55, 'default_left' => 166, 'default_size' => 30, 'width_mm' => 38],
            'animal' => ['label' => 'Jenis Hewan', 'default_top' => 110, 'default_left' => 179, 'default_size' => 21, 'width_mm' => 70],
            'dkm_name' => ['label' => 'Nama Ketua DKM', 'default_top' => 165.5, 'default_left' => 50, 'default_size' => 20, 'width_mm' => 60],
            'panitia_name' => ['label' => 'Nama Ketua Panitia', 'default_top' => 165.5, 'default_left' => 188, 'default_size' => 20, 'width_mm' => 60],
        ];
        $currentBackgroundUrl = !empty($qurbanActivity) && $qurbanActivity->certificate_background
            ? Storage::url($qurbanActivity->certificate_background)
            : asset('images/qurban-certificate-bg.png');
    @endphp
    <div class="rounded-xl border border-gray-200 p-4">
        <p class="mb-1 text-sm font-semibold text-gray-700">Pengaturan Sertifikat</p>
        <p class="mb-4 text-xs text-gray-400">Atur foto latar, posisi (mm dari pojok kiri-atas), dan ukuran font untuk
            tiap teks di sertifikat. Geser nilainya dan lihat pratinjau di bawah — nilai default sudah sesuai desain
            sertifikat standar.</p>

        {{-- Foto Latar Sertifikat --}}
        <div class="mb-4 rounded-lg bg-gray-50 p-3">
            <label class="mb-1 block text-xs font-semibold text-gray-600">Foto Latar Sertifikat</label>
            <p class="mb-2 text-[11px] text-gray-400">Kosongkan untuk tetap memakai desain default. Ukuran ideal 2000x1414px
                (rasio A4 landscape), format JPG/PNG/WebP, maksimal 5MB.</p>
            <input type="file" name="certificate_background" id="cert-background-input" accept="image/*"
                class="w-full text-xs">
            @error('certificate_background')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($certFields as $key => $field)
                <div class="rounded-lg bg-gray-50 p-3">
                    <p class="mb-2 text-xs font-semibold text-gray-600">{{ $field['label'] }}</p>
                    <div class="space-y-2">
                        <div>
                            <label class="mb-0.5 block text-[11px] text-gray-500">Posisi Atas (mm)</label>
                            <input type="number" step="0.1" name="certificate_{{ $key }}_top"
                                data-cert-input="{{ $key }}-top"
                                value="{{ old('certificate_' . $key . '_top', $qurbanActivity->{'certificate_' . $key . '_top'} ?? $field['default_top']) }}"
                                class="w-full rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="mb-0.5 block text-[11px] text-gray-500">Posisi Kiri (mm)</label>
                            <input type="number" step="0.1" name="certificate_{{ $key }}_left"
                                data-cert-input="{{ $key }}-left"
                                value="{{ old('certificate_' . $key . '_left', $qurbanActivity->{'certificate_' . $key . '_left'} ?? $field['default_left']) }}"
                                class="w-full rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="mb-0.5 block text-[11px] text-gray-500">Ukuran Font (px)</label>
                            <input type="number" step="1" name="certificate_{{ $key }}_font_size"
                                data-cert-input="{{ $key }}-font_size"
                                value="{{ old('certificate_' . $key . '_font_size', $qurbanActivity->{'certificate_' . $key . '_font_size'} ?? $field['default_size']) }}"
                                class="w-full rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs">
                        </div>
                    </div>
                    @error('certificate_' . $key . '_top')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('certificate_' . $key . '_left')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('certificate_' . $key . '_font_size')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        {{-- Pratinjau --}}
        {{-- Pratinjau dibangun dengan satuan ASLI (mm untuk posisi, px untuk font-size) persis seperti
             resources/views/pdf/qurban-certificate.blade.php, lalu diperkecil pakai CSS transform: scale().
             Ini memastikan pratinjau identik dengan hasil PDF (dompdf & browser sama-sama memakai acuan
             96 DPI untuk 'px'), bukan hasil kalkulasi ulang manual yang rawan meleset. --}}
        <div class="mt-4">
            <p class="mb-2 text-xs font-semibold text-gray-600">Pratinjau</p>
            <div id="cert-preview-viewport" class="w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                <div id="cert-preview-page" style="position: relative; width: 297mm; height: 210mm; transform-origin: top left;">
                    <img id="cert-preview-bg" src="{{ $currentBackgroundUrl }}" alt="Latar Sertifikat"
                        style="position: absolute; top: 0; left: 0; width: 297mm; height: 210mm; display: block;">
                    @foreach ($certFields as $key => $field)
                        <div id="cert-preview-{{ $key }}" class="font-bold"
                            style="position: absolute; color:#1e3a5f; width: {{ $field['width_mm'] }}mm; text-align: {{ $key === 'animal' ? 'left' : 'center' }}; {{ in_array($key, ['dkm_name', 'panitia_name']) ? 'white-space: nowrap;' : '' }}">
                            {{ $field['label'] }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const FIELD_KEYS = @json(array_keys($certFields));
            const page = document.getElementById('cert-preview-page');
            const viewport = document.getElementById('cert-preview-viewport');
            const bgInput = document.getElementById('cert-background-input');
            const bgImg = document.getElementById('cert-preview-bg');
            const overlays = {};
            FIELD_KEYS.forEach(function (key) {
                overlays[key] = document.getElementById('cert-preview-' + key);
            });

            function inputValue(key, suffix) {
                const el = document.querySelector('[data-cert-input="' + key + '-' + suffix + '"]');
                return el ? parseFloat(el.value) || 0 : 0;
            }

            function renderTextPositions() {
                FIELD_KEYS.forEach(function (key) {
                    const el = overlays[key];
                    el.style.top = inputValue(key, 'top') + 'mm';
                    el.style.left = inputValue(key, 'left') + 'mm';
                    el.style.fontSize = inputValue(key, 'font_size') + 'px';
                });
            }

            function updateScale() {
                // Lebar page 297mm dalam px browser (96dpi), dibagi lebar kontainer supaya pas.
                const pageWidthPx = (297 / 25.4) * 96;
                const pageHeightPx = (210 / 25.4) * 96;
                const scale = viewport.clientWidth / pageWidthPx;
                page.style.transform = 'scale(' + scale + ')';
                viewport.style.height = (pageHeightPx * scale) + 'px';
            }

            document.querySelectorAll('[data-cert-input]').forEach(function (el) {
                el.addEventListener('input', renderTextPositions);
            });

            if (bgInput) {
                bgInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        bgImg.src = URL.createObjectURL(this.files[0]);
                    }
                });
            }

            renderTextPositions();
            updateScale();
            window.addEventListener('resize', updateScale);
        })();
    </script>

    {{-- Row 5: Total Balance (Kg) - hanya tampil saat edit, dihitung otomatis dari total bobot Hewan Kurban terkait --}}
    @if (!empty($qurbanActivity))
        <div>
            <label class="mb-1 block text-sm font-medium">Total Balance (Kg)</label>
            <input type="text" value="{{ number_format($qurbanActivity->total_balance_kg, 2, ',', '.') }} kg" disabled
                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-600">
            <p class="mt-1 text-xs text-gray-400">Dihitung otomatis dari total bobot seluruh Hewan Kurban pada Activity
                ini.</p>
        </div>
    @endif

    {{-- Submit Button --}}
    <button type="submit"
        class="rounded-lg bg-emerald-700 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-800">
        Simpan
    </button>
</div>
