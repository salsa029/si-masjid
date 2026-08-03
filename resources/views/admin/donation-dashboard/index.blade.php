@extends('layouts.admin')

@section('title', 'Dashboard Donasi')

@section('content')

    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Infaq Terkumpul</p>
            <p class="mt-1 text-2xl font-bold text-emerald-700">Rp {{ number_format($summary['total_infaq'], 0, ',', '.') }}
            </p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Zakat Terkumpul</p>
            <p class="mt-1 text-2xl font-bold text-emerald-700">Rp {{ number_format($summary['total_zakat'], 0, ',', '.') }}
            </p>
        </div>
        <a href="{{ route('admin.donation-verifications.infaq.index') }}"
            class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            <p class="text-sm text-gray-500">Menunggu Verifikasi Infaq</p>
            <p class="mt-1 text-2xl font-bold text-amber-600">{{ $summary['pending_infaq_verifications'] }}</p>
        </a>
        <a href="{{ route('admin.donation-verifications.zakat.index') }}"
            class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
            <p class="text-sm text-gray-500">Menunggu Verifikasi Zakat</p>
            <p class="mt-1 text-2xl font-bold text-amber-600">{{ $summary['pending_zakat_verifications'] }}</p>
        </a>
    </div>

    <div class="mb-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h3 class="mb-4 font-semibold text-gray-800">Campaign Infaq Aktif</h3>
        @forelse($activeCampaigns as $campaign)
            <div class="mb-4 last:mb-0">
                <div class="mb-1 flex justify-between text-sm">
                    <span class="font-medium">{{ $campaign->title }}</span>
                    <span class="text-gray-500">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }} / Rp
                        {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                </div>
                <x-quota-progress :booked="$campaign->collected_amount" :total="$campaign->target_amount" />
            </div>
        @empty
            <p class="text-sm text-gray-400">Belum ada campaign infaq yang aktif.</p>
        @endforelse
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-semibold text-gray-800">Breakdown Zakat per Jenis</h3>
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500">
                    <tr>
                        <th class="pb-2">Jenis</th>
                        <th class="pb-2">Transaksi</th>
                        <th class="pb-2 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($zakatByType as $row)
                        <tr>
                            <td class="py-2">{{ $row->name }}</td>
                            <td class="py-2">{{ $row->total_transactions }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($row->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-semibold text-gray-800">Tren Infaq 6 Bulan Terakhir</h3>
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500">
                    <tr>
                        <th class="pb-2">Bulan</th>
                        <th class="pb-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($monthlyTrend as $row)
                        <tr>
                            <td class="py-2">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->translatedFormat('F Y') }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
