@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')

    <form method="GET" class="mb-6 flex flex-wrap items-end gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
        <div>
            <label class="mb-1 block text-xs font-medium">Status</label>
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="success" @selected($filters['status'] === 'success')>Berhasil</option>
                <option value="pending" @selected($filters['status'] === 'pending')>Menunggu</option>
                <option value="awaiting_verification" @selected($filters['status'] === 'awaiting_verification')>Menunggu Verifikasi</option>
                <option value="failed" @selected($filters['status'] === 'failed')>Gagal</option>
                <option value="expired" @selected($filters['status'] === 'expired')>Kedaluwarsa</option>
                <option value="" @selected(empty($filters['status']))>Semua Status</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <button type="submit"
            class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
            Terapkan Filter
        </button>

        <div class="ml-auto flex gap-2">
            <a href="{{ route('admin.reports.export-excel', request()->query()) }}"
                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Export Excel</a>
            <a href="{{ route('admin.reports.export-csv', request()->query()) }}"
                class="rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Export CSV</a>
            <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" target="_blank"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Export PDF</a>
        </div>
    </form>

    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Infaq</p>
            <p class="mt-1 text-xl font-bold text-emerald-700">Rp {{ number_format($summary['total_infaq'], 0, ',', '.') }}
            </p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Zakat</p>
            <p class="mt-1 text-xl font-bold text-emerald-700">Rp {{ number_format($summary['total_zakat'], 0, ',', '.') }}
            </p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Kurban</p>
            <p class="mt-1 text-xl font-bold text-emerald-700">Rp {{ number_format($summary['total_qurban'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="mb-8 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-3 text-sm font-semibold">Data Infaq</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Donatur</th>
                    <th class="px-5 py-3">Kategori/Campaign</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($infaqs as $infaq)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">{{ $infaq->display_name }}</td>
                        <td class="px-5 py-3">{{ $infaq->category->name ?? ($infaq->campaign->title ?? 'Umum') }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($infaq->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">{{ ucfirst(str_replace('_', ' ', $infaq->payment_status)) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $infaq->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $infaqs->links() }}</div>
    </div>

    <div class="mb-8 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-3 text-sm font-semibold">Data Zakat</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Muzakki</th>
                    <th class="px-5 py-3">Jenis Zakat</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($zakats as $zakat)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">{{ $zakat->display_name }}</td>
                        <td class="px-5 py-3">{{ $zakat->zakatType->name }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($zakat->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">{{ ucfirst(str_replace('_', ' ', $zakat->payment_status)) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $zakat->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $zakats->links() }}</div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-3 text-sm font-semibold">Data Pesanan Kurban</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Hewan</th>
                    <th class="px-5 py-3">Jenis Pesanan</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($qurbanOrders as $order)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">{{ $order->animal->name }}</td>
                        <td class="px-5 py-3">{{ $order->order_type === 'full' ? 'Penuh' : 'Patungan' }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $qurbanOrders->links() }}</div>
    </div>

@endsection
