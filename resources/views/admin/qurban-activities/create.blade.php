@extends('layouts.admin')

@section('title', 'Tambah Qurban Activity')

@section('content')
    <div class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-semibold text-gray-800">Tambah Qurban Activity</h2>
        <form method="POST" action="{{ route('admin.qurban-activities.store') }}" enctype="multipart/form-data">
            @include('admin.qurban-activities._form')
        </form>
    </div>
@endsection
