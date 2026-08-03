<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-outline w-full']) }}>
    {{ $slot }}
</button>
