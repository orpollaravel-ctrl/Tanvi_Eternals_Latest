@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Visit - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Visit Details</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        
        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="relative bg-white dark:bg-darkmode-600 rounded-xl shadow-lg overflow-hidden">
                
                <!-- Left Accent -->
                <div class="absolute left-0 top-0 h-full w-1 bg-primary"></div>
                
                <div style="padding: 15px;">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-800 dark:text-white">
                                {{ $visit->customer_name ?? 'N/A' }}
                            </h2>
                            <p class="text-sm text-slate-500">
                                Visit details summary
                            </p>
                        </div> 
                    </div>
                    
                    <!-- Date Highlight -->
                    <div class="mb-6">
                        <p class="text-sm text-slate-500">Visit Date</p>
                        <p class="text-md font-bold text-primary">
                            {{ $visit->target_date ? date('d M Y', strtotime($visit->target_date)) : 'N/A' }}
                        </p>
                    </div>
                    
                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        
                        <div class="flex items-center gap-3">
                            <x-base.lucide icon="Phone" class="w-5 h-5 text-slate-400" />
                            <div>
                                <p class="text-xs text-slate-500">Contact</p>
                                <p class="font-medium">
                                    {{ $visit->phone ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <x-base.lucide icon="Clock" class="w-5 h-5 text-slate-400" />
                            <div>
                                <p class="text-xs text-slate-500">Time</p>
                                <p class="font-medium">
                                    {{ $visit->time ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <x-base.lucide icon="User" class="w-5 h-5 text-slate-400" />
                            <div>
                                <p class="text-xs text-slate-500">Assigned User</p>
                                <p class="font-medium">
                                    {{ $visit->user->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <x-base.lucide icon="MessageSquare" class="w-5 h-5 text-slate-400" />
                            <div>
                                <p class="text-xs text-slate-500">Reason</p>
                                <p class="text-sm text-slate-700 dark:text-slate-300">
                                    {{ $visit->reason ?: 'No reason provided' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200 dark:border-darkmode-400">
                        <a href="{{ route('visits.index') }}">
                            <x-base.button variant="outline-secondary">
                                ← Back
                            </x-base.button>
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <!-- Location Map -->
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
        
        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="bg-white dark:bg-darkmode-600 rounded-xl shadow-lg overflow-hidden">
                <div class="p-5">
                    <h3 class="text-lg font-semibold mb-4">Visit Location</h3>
                    
                    @if($lat && $lng)
                        <div class="border rounded-lg overflow-hidden">
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
                            Coordinates: {{ $lat }}, {{ $lng }}
                        </div>
                    @else
                        <div class="text-center text-slate-400 py-10">
                            <x-base.lucide icon="MapPin" class="w-12 h-12 mx-auto mb-2 text-slate-300" />
                            <p>Location not available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
@endsection
