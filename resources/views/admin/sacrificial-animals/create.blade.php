@extends('layouts.admin')

@section('title', 'Tambah Hewan Kurban')

@section('content')
    <div class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.sacrificial-animals.store') }}" enctype="multipart/form-data">
            @include('admin.sacrificial-animals._form')
        </form>
    </div>
@endsection
