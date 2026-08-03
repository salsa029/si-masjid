@extends('layouts.admin')

@section('title', 'Ubah Qurban Activity')

@section('content')
    <div class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-semibold text-gray-800">Ubah Qurban Activity</h2>
        <form method="POST" action="{{ route('admin.qurban-activities.update', $qurbanActivity) }}"
            enctype="multipart/form-data">
            @method('PUT')
            @include('admin.qurban-activities._form')
        </form>
    </div>
@endsection
