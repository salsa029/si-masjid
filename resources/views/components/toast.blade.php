@if (session('success') || session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="{{ session('success') ? 'bg-emerald-600' : 'bg-red-600' }} fixed right-5 top-5 z-50 rounded-lg px-5 py-3 text-sm font-medium text-white shadow-lg">
        {{ session('success') ?? session('error') }}
    </div>
@endif
