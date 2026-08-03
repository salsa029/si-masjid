@props(['prayerData' => null, 'cityCode' => null])

@php
    use App\Services\PrayerTimeService;

    $prayerService = app(PrayerTimeService::class);
    $prayerData = $prayerData ?? $prayerService->getPrayerData($cityCode);
@endphp

<div x-data="prayerTimer()" x-init="initPrayerTimes({{ json_encode($prayerData) }}, {{ json_encode($cityCode) }})" class="group fixed bottom-4 left-4 z-30">

    <!-- Main Card -->
    <div
        class="relative min-w-[200px] overflow-hidden rounded-2xl border border-white/20 bg-white/10 px-4 py-2.5 shadow-2xl shadow-black/20 backdrop-blur-xl backdrop-saturate-150 transition-all duration-300 hover:scale-105 hover:shadow-green-500/20">

        <!-- Animated Gradient Background -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-emerald-500/90 via-green-600/90 to-teal-700/90 opacity-95 transition-opacity duration-500 group-hover:opacity-100">
        </div>

        <!-- Animated Shimmer Effect -->
        <div
            class="absolute inset-0 w-1/2 -translate-x-full skew-x-12 bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-1000 ease-in-out group-hover:translate-x-full">
        </div>

        <!-- Content -->
        <div class="relative z-10 text-white">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <!-- Mosque Icon -->
                    <svg class="h-3.5 w-3.5 text-emerald-200/80" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 2a1 1 0 011 1v1.5a1 1 0 11-2 0V3a1 1 0 011-1zm0 14a1 1 0 01-1-1v-1.5a1 1 0 112 0V15a1 1 0 01-1 1zM5.5 5.5a1 1 0 011.414 0L8.5 7.086V10a1 1 0 11-2 0V7.086L5.5 6.914a1 1 0 010-1.414zm9 0a1 1 0 010 1.414L13.5 7.086V10a1 1 0 11-2 0V7.086l1.586-1.586a1 1 0 011.414 0zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                    </svg>
                    <span class="text-[10px] font-medium uppercase tracking-[0.15em] text-emerald-100/80">
                        Next Prayer
                    </span>
                </div>

                <!-- Live Indicator -->
                <div class="flex items-center gap-1.5">
                    <span class="relative flex h-1.5 w-1.5">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-75"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                    </span>
                    <span class="text-[8px] font-medium uppercase tracking-wider text-emerald-200/60"
                        x-text="currentTime"></span>
                </div>
            </div>

            <!-- Prayer Info -->
            <div class="mt-1 flex items-end justify-between gap-3">
                <div class="flex items-baseline gap-2">
                    <!-- Prayer Name -->
                    <span class="text-xl font-bold tracking-tight" x-text="nextPrayerName">--</span>

                    <!-- Prayer Time -->
                    <span class="text-base font-medium text-emerald-100/80" x-text="nextPrayerTime">--:--</span>
                </div>

                <!-- Countdown Badge -->
                <div x-show="timeRemaining"
                    class="rounded-full border border-white/10 bg-white/20 px-2.5 py-0.5 backdrop-blur-sm">
                    <span class="text-[10px] font-semibold tabular-nums tracking-wide" x-text="timeRemaining"></span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-1.5 h-0.5 w-full overflow-hidden rounded-full bg-white/10">
                <div class="h-full rounded-full bg-gradient-to-r from-emerald-300 to-teal-300 transition-all duration-1000 ease-linear"
                    :style="`width: ${progressPercentage}%`"></div>
            </div>

        </div>
    </div>
</div>

<script>
    function prayerTimer() {
        return {
            prayerTimes: [],
            currentCityCode: null,
            nextPrayer: null,
            nextPrayerName: '--',
            nextPrayerTime: '--:--',
            timeRemaining: '',
            currentTime: '',
            progressPercentage: 0,
            countdownTimer: null,
            refreshTimer: null,
            isRefreshing: false,

            initPrayerTimes(prayerData, cityCode) {
                this.prayerTimes = prayerData.prayer_times || [];
                this.currentCityCode = cityCode || prayerData.city_code || null;
                this.updateNextPrayer();
                this.updateCountdown();
                this.updateCurrentTime();
                this.startTimers();

                // Listen for city changes from parent
                this.listenForCityChanges();
            },

            startTimers() {
                // Update setiap detik
                this.countdownTimer = setInterval(() => {
                    this.updateCountdown();
                    this.updateCurrentTime();
                }, 1000);

                // Refresh data setiap 3 menit
                this.refreshTimer = setInterval(() => {
                    this.refreshPrayerTimes();
                }, 180000); // 3 menit
            },

            listenForCityChanges() {
                // Listen for custom event from city selector
                document.addEventListener('prayerCityChanged', (event) => {
                    const newCityCode = event.detail.cityCode;
                    if (newCityCode !== this.currentCityCode) {
                        this.currentCityCode = newCityCode;
                        this.refreshPrayerTimes(newCityCode);
                    }
                });

                // Also listen for Alpine.js event if using Alpine
                window.addEventListener('cityChanged', (event) => {
                    const newCityCode = event.detail.cityCode;
                    if (newCityCode !== this.currentCityCode) {
                        this.currentCityCode = newCityCode;
                        this.refreshPrayerTimes(newCityCode);
                    }
                });
            },

            updateNextPrayer() {
                if (!this.prayerTimes || this.prayerTimes.length === 0) {
                    this.nextPrayer = null;
                    this.nextPrayerName = '--';
                    this.nextPrayerTime = '--:--';
                    return;
                }

                const now = new Date();
                const currentTime = this.formatTime(now);

                // Cari sholat berikutnya
                let next = null;
                for (const prayer of this.prayerTimes) {
                    if (prayer.time > currentTime) {
                        next = prayer;
                        break;
                    }
                }

                // Jika tidak ada, ambil sholat pertama (esok hari)
                if (!next) {
                    next = this.prayerTimes[0];
                }

                this.nextPrayer = next;
                this.nextPrayerName = next.name;
                this.nextPrayerTime = next.time;
            },

            updateCountdown() {
                if (!this.nextPrayer) {
                    this.timeRemaining = '';
                    this.progressPercentage = 0;
                    return;
                }

                const now = new Date();
                const prayerTime = this.parseTime(this.nextPrayer.time);

                // Jika waktu sholat sudah lewat, update ke sholat berikutnya
                if (prayerTime <= now) {
                    this.updateNextPrayer();
                    // Rekursif dengan delay untuk menghindari infinite loop
                    setTimeout(() => this.updateCountdown(), 100);
                    return;
                }

                // Hitung sisa waktu
                const diff = prayerTime - now;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                // Format countdown
                if (hours > 0) {
                    this.timeRemaining = `${hours}h ${minutes}m`;
                } else if (minutes > 0) {
                    this.timeRemaining = `${minutes}m ${seconds}s`;
                } else {
                    this.timeRemaining = `${seconds}s`;
                }

                // Update progress bar
                this.updateProgressBar(now, prayerTime);
            },

            updateProgressBar(now, prayerTime) {
                if (!this.nextPrayer || !this.prayerTimes || this.prayerTimes.length === 0) {
                    this.progressPercentage = 0;
                    return;
                }

                // Cari sholat sebelumnya
                const currentIndex = this.prayerTimes.indexOf(this.nextPrayer);
                const prevIndex = currentIndex > 0 ? currentIndex - 1 : this.prayerTimes.length - 1;
                const prevPrayer = this.prayerTimes[prevIndex];

                let startTime = this.parseTime(prevPrayer.time);
                let endTime = this.parseTime(this.nextPrayer.time);

                // Handle kasus overnight (misal Isya -> Subuh)
                if (startTime >= endTime) {
                    startTime.setDate(startTime.getDate() - 1);
                }

                // Jika sekarang sebelum start time
                if (now < startTime) {
                    this.progressPercentage = 0;
                    return;
                }

                // Jika sekarang setelah end time
                if (now >= endTime) {
                    this.progressPercentage = 100;
                    return;
                }

                // Hitung progress
                const totalDuration = endTime - startTime;
                const elapsed = now - startTime;
                this.progressPercentage = Math.min((elapsed / totalDuration) * 100, 100);
            },

            updateCurrentTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            async refreshPrayerTimes(cityCode = null) {
                if (this.isRefreshing) return;

                this.isRefreshing = true;
                const code = cityCode || this.currentCityCode;

                try {
                    const url = code ?
                        `/api/prayer/times?city_code=${code}` :
                        '/api/prayer/times';

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    if (result.success) {
                        this.prayerTimes = result.data.prayer_times || [];
                        this.currentCityCode = result.data.city_code || code;
                        this.updateNextPrayer();
                        this.updateCountdown();
                    } else {
                        console.warn('Failed to refresh prayer times:', result.message);
                    }
                } catch (error) {
                    console.error('Failed to refresh prayer times:', error);
                    // Keep using existing data
                } finally {
                    this.isRefreshing = false;
                }
            },

            parseTime(timeStr) {
                const [hour, minute] = timeStr.split(':').map(Number);
                const date = new Date();
                date.setHours(hour, minute, 0, 0);
                return date;
            },

            formatTime(date) {
                return date.getHours().toString().padStart(2, '0') +
                    ':' +
                    date.getMinutes().toString().padStart(2, '0');
            },

            destroy() {
                if (this.countdownTimer) {
                    clearInterval(this.countdownTimer);
                }
                if (this.refreshTimer) {
                    clearInterval(this.refreshTimer);
                }
            }
        }
    }
</script>
