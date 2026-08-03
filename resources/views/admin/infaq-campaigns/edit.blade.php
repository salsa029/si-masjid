@extends('layouts.admin')

@section('title', 'Ubah Campaign Infaq')

@section('content')
    <div class="max-w-3xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.infaq-campaigns.update', $infaqCampaign) }}"
            enctype="multipart/form-data">
            @method('PUT')
            @include('admin.infaq-campaigns._form')
        </form>
    </div>
@endsection
