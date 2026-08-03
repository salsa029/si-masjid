<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-danger w-full']) }}>
    {{ $slot }}
</button>
