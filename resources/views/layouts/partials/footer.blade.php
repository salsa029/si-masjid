<footer class="bg-green-900 text-white" role="contentinfo">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- About -->
            <div>
                <div class="mb-4 flex items-center gap-2">
                    <i class="fas fa-mosque text-2xl text-green-300" aria-hidden="true"></i>
                    <span class="text-xl font-extrabold">{{ $mosque->name ?? 'MASJID AN-NUR' }}</span>
                </div>
                <p class="mb-4 text-sm text-green-200">
                    {{ $mosque->history ?? 'Pusat Ibadah dan Peradaban Umat Islam.' }}
                </p>
                <div class="flex gap-3">
                    @if (! empty($mosque->facebook_url))
                        <a href="{{ $mosque->facebook_url }}" target="_blank" rel="noopener noreferrer"
                            class="text-green-300 transition hover:text-white" aria-label="Facebook">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if (! empty($mosque->instagram_url))
                        <a href="{{ $mosque->instagram_url }}" target="_blank" rel="noopener noreferrer"
                            class="text-green-300 transition hover:text-white" aria-label="Instagram">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if (! empty($mosque->youtube_url))
                        <a href="{{ $mosque->youtube_url }}" target="_blank" rel="noopener noreferrer"
                            class="text-green-300 transition hover:text-white" aria-label="YouTube">
                            <i class="fab fa-youtube" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if (! empty($mosque->whatsapp_url))
                        <a href="{{ $mosque->whatsapp_url }}" target="_blank" rel="noopener noreferrer"
                            class="text-green-300 transition hover:text-white" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="mb-4 border-b border-green-700 pb-2 text-lg font-bold">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-green-200 transition hover:text-white">Beranda</a>
                    </li>
                    <li><a href="{{ route('public.committees.index') }}"
                            class="text-green-200 transition hover:text-white">Pengurus</a></li>
                    <li><a href="{{ route('public.events.index') }}"
                            class="text-green-200 transition hover:text-white">Kegiatan</a></li>
                    <li><a href="{{ route('public.articles.index') }}"
                            class="text-green-200 transition hover:text-white">Artikel</a></li>
                    <li><a href="{{ route('public.infaq.index') }}"
                            class="text-green-200 transition hover:text-white">Infaq</a></li>
                    <li><a href="{{ route('public.zakat.index') }}"
                            class="text-green-200 transition hover:text-white">Zakat</a></li>
                    <li><a href="{{ route('public.qurban.index') }}"
                            class="text-green-200 transition hover:text-white">Kurban</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="mb-4 border-b border-green-700 pb-2 text-lg font-bold">Kontak Kami</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-0.5 text-green-300" aria-hidden="true"></i>
                        <span
                            class="text-green-200">{{ $mosque->address ?? 'Jl. Kebajikan No. 123, Jakarta Selatan' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone-alt text-green-300" aria-hidden="true"></i>
                        <a href="tel:{{ $mosque->contact ?? '' }}" class="text-green-200 transition hover:text-white">
                            {{ $mosque->contact ?? '(021) 7654321' }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-clock text-green-300" aria-hidden="true"></i>
                        <span class="text-green-200">Buka 24 Jam</span>
                    </li>
                </ul>
            </div>

            <!-- Prayer Times Quick -->
            <div>
                <h4 class="mb-4 border-b border-green-700 pb-2 text-lg font-bold">Jadwal Sholat</h4>
                <ul class="space-y-1.5 text-sm" id="footer-prayer-times">
                    <li class="flex justify-between"><span class="text-green-200">Subuh</span><span
                            class="text-white">04:30</span></li>
                    <li class="flex justify-between"><span class="text-green-200">Dzuhur</span><span
                            class="text-white">11:45</span></li>
                    <li class="flex justify-between"><span class="text-green-200">Ashar</span><span
                            class="text-white">15:00</span></li>
                    <li class="flex justify-between"><span class="text-green-200">Maghrib</span><span
                            class="text-white">18:30</span></li>
                    <li class="flex justify-between"><span class="text-green-200">Isya</span><span
                            class="text-white">19:45</span></li>
                </ul>
                <div class="mt-4">
                    <a href="#prayer-times-section" class="text-sm text-green-300 transition hover:text-white">
                        <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i> Lihat jadwal lengkap
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div
            class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-green-800 pt-6 text-center text-sm text-green-300 md:flex-row">
            <p>&copy; {{ date('Y') }} {{ $mosque->name ?? 'MASJID AN-NUR' }}. All rights reserved.</p>
            <div class="flex flex-wrap gap-4">
                <a href="#" class="transition hover:text-white">Kebijakan Privasi</a>
                <a href="#" class="transition hover:text-white">Syarat & Ketentuan</a>
                <a href="#" class="transition hover:text-white">Sitemap</a>
            </div>
        </div>
    </div>

    <!-- Script untuk update footer prayer times -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update footer prayer times when changed by main script
            const footerTimes = document.getElementById('footer-prayer-times');

            // Fungsi ini akan dipanggil dari main script
            window.updateFooterPrayerTimes = function(prayerTimes) {
                if (!footerTimes) return;
                footerTimes.innerHTML = prayerTimes.map(p =>
                    `<li class="flex justify-between">
                        <span class="text-green-200">${p.name}</span>
                        <span class="text-white">${p.time}</span>
                    </li>`
                ).join('');
            };
        });
    </script>
</footer>
