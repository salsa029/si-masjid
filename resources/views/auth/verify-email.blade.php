<x-guest-layout :title="'Verifikasi Email'">

    <div class="text-center" data-reveal>
        <div class="bg-primary-100 mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full">
            <svg class="text-primary-700 h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>

        <h1 class="section-title !text-2xl">Verifikasi Email Anda</h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-500">
            Terima kasih telah mendaftar. Sebelum memulai, mohon verifikasi email Anda dengan
            mengklik tautan yang baru saja kami kirimkan. Tidak menerima email?
            Kami akan dengan senang hati mengirimkan yang baru.
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="alert-success mt-6 justify-center text-left">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
                @csrf
                <x-primary-button class="w-full">Kirim Ulang Email Verifikasi</x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <x-secondary-button class="w-full" type="submit">Keluar</x-secondary-button>
            </form>
        </div>
    </div>
</x-guest-layout>
