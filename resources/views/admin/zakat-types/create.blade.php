@extends('layouts.admin')

@section('title', 'Tambah Jenis Zakat')

@section('content')
    <div class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.zakat-types.store') }}">
            @include('admin.zakat-types._form')
        </form>
    </div>
@endsection
