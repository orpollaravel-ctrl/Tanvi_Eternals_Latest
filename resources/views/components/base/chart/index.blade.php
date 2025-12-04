<canvas
    {{ $attributes->whereDoesntStartWith('labels')->whereDoesntStartWith('data')->whereDoesntStartWith('colors')->class(merge(['chart', $attributes->whereStartsWith('class')->first()]))->merge($attributes->whereDoesntStartWith('class')->whereDoesntStartWith('labels')->whereDoesntStartWith('data')->whereDoesntStartWith('colors')->getAttributes()) }}
></canvas>

@once
    @push('vendors')
        @vite('resources/js/vendor/chartjs/index.js')
    @endpush
@endonce


