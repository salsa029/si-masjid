@props(['disabled' => false])

<input type="checkbox" @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'rounded-md border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0',
    ]) }}>
