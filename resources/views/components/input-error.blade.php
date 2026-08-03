@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'space-y-0.5']) }}>
        @foreach ((array) $messages as $message)
            <li class="form-error flex items-center gap-1">
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
                </svg>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
