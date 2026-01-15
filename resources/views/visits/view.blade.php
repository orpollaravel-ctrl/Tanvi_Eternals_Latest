@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Visit - Tanvi Eternals</title>
@endsection
@php
    $lat = null;
    $lng = null;

    if (!empty($visit->location)) {
        $coords = explode(',', $visit->location);

        if (count($coords) == 2) {
            $lat = trim($coords[0]);
            $lng = trim($coords[1]);
        }
    }
@endphp
@section('subcontent')

<div class="intro-y mt-10 flex items-center justify-between">
    <h2 class="text-lg font-medium">Visit Details</h2>

    <a href="{{ route('visits.index') }}">
        <x-base.button variant="secondary">Back</x-base.button>
    </a>
</div>

<div class="intro-y box mt-5 p-5">
    <div class="grid grid-cols-12 gap-6">

        {{-- Customer --}}
        <div class="col-span-6 sm:col-span-6">
            <label class="text-sm text-slate-500">Customer Name</label>
            <div class="mt-1 font-semibold">{{ $visit->customer_name ?? '-' }}</div>
        </div> 
        {{-- Contact --}}
        <div class="col-span-6 sm:col-span-6">
            <label class="text-sm text-slate-500">Contact</label>
            <div class="mt-1 font-semibold">{{ $visit->phone ?? '-' }}</div>
        </div>

        {{-- Visit Date --}}
        <div class="col-span-6 sm:col-span-6">
            <label class="text-sm text-slate-500">Visit Date</label>
            <div class="mt-1 font-semibold">
                {{ $visit->target_date ? date('d M Y', strtotime($visit->target_date)) : '-' }}
            </div>
        </div>

        {{-- Remarks --}}
        <div class="col-span-6 sm:col-span-6">
            <label class="text-sm text-slate-500">Reason</label>
            <div class="mt-1 font-semibold">{{ $visit->reason ?? '-' }}</div>
        </div>

        {{-- Location --}}
        <div class="col-span-6">
            <label class="text-sm text-slate-500">Visit Location</label>

            @if($lat && $lng)
                <div class="mt-3 border rounded-lg overflow-hidden">
                    <iframe
                        width="100%"
                        height="300"
                        frameborder="0"
                        style="border:0"
                        src="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&z=15&output=embed"
                        allowfullscreen>
                    </iframe>
                </div>

                <div class="text-xs text-slate-400 mt-2">
                    Lat: {{ $lat }}, Lng: {{ $lng }}
                </div>
            @else
                <div class="text-slate-400 mt-2">Location not available</div>
            @endif
        </div>
    </div>
</div> 

@endsection
