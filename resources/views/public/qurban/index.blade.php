@extends('layouts.app')

@section('title', 'Layanan Kurban')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="mb-10 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Kurban</span>
                <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Katalog <span class="text-green-600">Hewan
                        Kurban</span></h2>
                <p class="mx-auto mt-2 max-w-2xl text-gray-500">Pilih hewan kurban yang tersedia dan tunaikan ibadah kurban
                    Anda melalui masjid</p>
            </div>

            <!-- Katalog Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($sacrificialAnimals as $animal)
                    <a href="{{ route('public.qurban.show', $animal) }}"
                        class="group overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                        <!-- Image -->
                        <div class="relative h-52 overflow-hidden">
                            @if ($animal->photo)
                                <img src="{{ Storage::url($animal->photo) }}" alt="{{ $animal->name }}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy">
                            @else
                                <div
                                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                                    <i class="fas fa-cow text-5xl text-green-400" aria-hidden="true"></i>
                                </div>
                            @endif
                            <!-- Status Badge -->
                            @if ($animal->status === 'fully_booked')
                                <span
                                    class="absolute right-3 top-3 rounded-full bg-amber-500 px-3 py-1 text-xs font-bold text-white shadow-lg">
                                    <i class="fas fa-clock mr-1" aria-hidden="true"></i> Kuota Penuh
                                </span>
                            @elseif($animal->status === 'slaughtered')
                                <span
                                    class="absolute right-3 top-3 rounded-full bg-gray-500 px-3 py-1 text-xs font-bold text-white shadow-lg">
                                    <i class="fas fa-check-circle mr-1" aria-hidden="true"></i> Selesai
                                </span>
                            @endif
                            <!-- Type Badge -->
                            <span
                                class="absolute bottom-3 left-3 rounded-full bg-black/50 px-3 py-1 text-xs font-semibold text-white backdrop-blur-sm">
                                {{ ucfirst($animal->animal_type) }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 transition group-hover:text-green-700">
                                {{ $animal->name }}</h3>

                            <!-- Package -->
                            @if ($animal->package_name)
                                <p class="mt-1 text-xs font-medium text-green-600">
                                    <i class="fas fa-box mr-1" aria-hidden="true"></i>
                                    {{ $animal->package_name }}
                                </p>
                            @endif

                            <!-- Details -->
                            <div class="mt-2 flex items-center gap-3 text-sm text-gray-500">
                                <span>{{ $animal->weight }} kg</span>
                                <span>•</span>
                                <span>{{ $animal->age }} bulan</span>
                            </div>

                            <!-- Price -->
                            <p class="mt-2 text-lg font-bold text-green-700">Rp
                                {{ number_format($animal->price, 0, ',', '.') }}</p>

                            <!-- Max Participants -->
                            @if ($animal->max_participants > 1)
                                <p class="mt-1 text-xs text-gray-400">
                                    <i class="fas fa-users mr-1" aria-hidden="true"></i>
                                    Bisa patungan hingga {{ $animal->max_participants }} orang
                                </p>
                            @endif

                            <!-- Progress Bar -->
                            <div class="mt-3">
                                <x-quota-progress :booked="$animal->booked_slots_count ?? 0" :total="$animal->max_participants" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <div class="mb-4 text-6xl text-gray-300">
                            <i class="fas fa-cow" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-500">Belum Ada Hewan Kurban</h3>
                        <p class="mt-2 text-gray-400">Hewan kurban akan segera tersedia. Silakan cek kembali nanti.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $sacrificialAnimals->links() }}
            </div>

            <!-- Info Section -->
            <div class="mx-auto mt-12 max-w-3xl rounded-2xl bg-white p-6 shadow-lg md:p-8">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-green-100 p-3">
                            <i class="fas fa-info-circle text-xl text-green-600" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Informasi Kurban</h3>
                        <ul class="mt-2 space-y-1 text-sm text-gray-600">
                            <li>• Harga sudah termasuk biaya pemotongan, pengemasan, dan distribusi</li>
                            <li>• Untuk sapi, Anda dapat berpatungan dengan maksimal 7 orang</li>
                            <li>• Kambing/domba hanya dapat dipesan secara penuh (1 pemesan = 1 ekor)</li>
                            <li>• Sertifikat kurban akan diterbitkan setelah hewan selesai disembelih</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
