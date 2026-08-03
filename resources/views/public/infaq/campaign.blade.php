@extends('layouts.app')

@section('title', $infaqCampaign->title)

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10">
        @if ($infaqCampaign->thumbnail)
            <img src="{{ Storage::url($infaqCampaign->thumbnail) }}" class="mb-6 h-64 w-full rounded-xl object-cover"
                alt="{{ $infaqCampaign->title }}">
        @else
            <div
                class="mb-6 flex h-64 w-full items-center justify-center rounded-xl bg-gradient-to-br from-green-100 to-green-200">
                <i class="fas fa-hand-holding-heart text-8xl text-green-400" aria-hidden="true"></i>
            </div>
        @endif
        <h1 class="mb-2 text-2xl font-bold text-gray-800">{{ $infaqCampaign->title }}</h1>
        <p class="mb-3 text-sm text-gray-500">Rp {{ number_format($infaqCampaign->collected_amount, 0, ',', '.') }} terkumpul
            dari target Rp {{ number_format($infaqCampaign->target_amount, 0, ',', '.') }}</p>
        <x-quota-progress :booked="$infaqCampaign->collected_amount" :total="$infaqCampaign->target_amount" />
        <p class="mt-4 leading-relaxed text-gray-600">{{ $infaqCampaign->description }}</p>
        <a href="{{ route('public.infaq.index', ['campaign_id' => $infaqCampaign->id]) }}"
            class="mt-6 inline-block rounded-lg bg-emerald-700 px-6 py-3 text-sm font-medium text-white hover:bg-emerald-800">
            Infaq untuk Campaign Ini
        </a>
    </div>
@endsection
