@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto max-w-3xl px-4">
            <!-- Header -->
            <div class="mb-8 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Profil</span>
                <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Profil <span
                        class="text-green-600">Saya</span></h2>
                <p class="mt-1 text-gray-500">Kelola informasi akun Anda</p>
            </div>

            <div class="space-y-6">
                <!-- Update Profile Information -->
                <div class="rounded-2xl bg-white p-6 shadow-lg md:p-8">
                    <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-800">
                        <i class="fas fa-user text-green-600" aria-hidden="true"></i>
                        Informasi Profil
                    </h3>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="rounded-2xl bg-white p-6 shadow-lg md:p-8">
                    <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-800">
                        <i class="fas fa-key text-green-600" aria-hidden="true"></i>
                        Ubah Kata Sandi
                    </h3>
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account -->
                <div class="rounded-2xl border border-red-100 bg-white p-6 shadow-lg md:p-8">
                    <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-red-600">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        Hapus Akun
                    </h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section>
@endsection
