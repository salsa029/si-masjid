@extends('layouts.admin')

@section('title', 'Tambah Campaign Infaq')

@section('content')
    <div class="max-w-3xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.infaq-campaigns.store') }}" enctype="multipart/form-data">
            @include('admin.infaq-campaigns._form')
        </form>
    </div>
@endsection
