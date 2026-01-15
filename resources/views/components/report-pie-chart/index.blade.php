@props(['width' => 'w-auto', 'height' => 'h-auto', 'id' => 'report-pie-chart', 'labels' => [], 'data' => [], 'colors' => []])

@php
$hasData = !empty($data) && array_sum($data) > 0;
@endphp

@if($hasData)
<div class="{{ $width }} {{ $height }}">
    <x-base.chart
        id="{{ $id }}"
        {{ $attributes->merge($attributes->whereDoesntStartWith('class')->whereDoesntStartWith('labels')->whereDoesntStartWith('data')->whereDoesntStartWith('colors')->getAttributes()) }}
    >
    </x-base.chart>
</div>

@php
$colorMap = [
    'primary' => 'bg-blue-500',
    'warning' => 'bg-yellow-500',
    'success' => 'bg-green-500',
    'danger' => 'bg-red-500',
    'pending' => 'bg-pending-500',
];
@endphp

<div class="flex justify-center mt-4 space-x-4">
    @foreach($labels as $index => $label)
        <div class="flex items-center">
            <div class="w-3 h-3 rounded-full mr-2 {{ $colorMap[$colors[$index]] ?? 'bg-gray-500' }}"></div>
            <span class="text-sm">{{ $label }}: {{ $data[$index] }}</span>
        </div>
    @endforeach
</div>
@else
<div class="{{ $width }} {{ $height }} flex items-center justify-center bg-gray-100 rounded">
    <span class="text-gray-500 text-lg">No data available</span>
</div>
@endif

@once
    @push('scripts')
        @vite('resources/js/components/report-pie-chart/index.js')
    @endpush
@endonce

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("{{ $id }}")) {
        const ctx = document.getElementById("{{ $id }}").getContext("2d");

        // Convert PHP arrays to JavaScript
        const labels = @json($labels);
        const data = @json($data);
        const colors = @json($colors);

        new Chart(ctx, {
            type: "pie",
            data: {
                labels: labels,
                datasets: [
                    {
                        data: data,
                        backgroundColor: colors.map(color => getColor(color, 0.9)),
                        hoverBackgroundColor: colors.map(color => getColor(color, 0.9)),
                        borderWidth: 5,
                        borderColor: $("html").hasClass("dark")
                            ? getColor("darkmode.700")
                            : getColor("white"),
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }
});
</script>


