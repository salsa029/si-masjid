@props(['message' => 'Belum ada data.', 'icon' => 'inbox'])

<div class="empty-state">
    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50">
        <svg class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125Z" />
        </svg>
    </div>
    <p class="text-sm font-medium">{{ $message }}</p>
    @isset($action)
        <div class="mt-4">{{ $action }}</div>
    @endisset
</div>
