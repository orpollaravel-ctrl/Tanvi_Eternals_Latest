@extends('../layouts/' . $layout)

@section('subhead')
    <title>Visit Dashboard - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="flex justify-between items-center mb-6 mt-6">
                <h2 class="text-lg font-medium">Visit Locations by User</h2>
                <div class="flex items-center gap-2">
                    <div class="relative text-slate-500">
                        <x-base.lucide
                            class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4"
                            icon="Calendar"
                        />
                        <input type="text" name="map_date_filter" class="!box pl-10 w-64" placeholder="Select date range" readonly value="{{ request('from_date') && request('to_date') ? request('from_date') . ' to ' . request('to_date') : '' }}" />
                    </div>
                   <button onclick="filterByDate()" class="btn btn-primary btn-sm">
                        Filter
                    </button>

                    @if(request('from_date') && request('to_date'))
                        <button onclick="clearFilter()" class="btn btn-secondary btn-sm">
                            Clear
                        </button>
                    @endif
                </div>
            </div>
            
            @php
                $visitsByUser = $visits->groupBy('user_id');
            @endphp
            
            <div class="grid grid-cols-2 gap-6">
            @foreach($visitsByUser as $userId => $userVisits)
                <div class="intro-y box mb-6 p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">
                            {{ $userVisits->first()->user->name ?? 'Unknown User' }} 
                            <span class="text-sm text-slate-500">({{ $userVisits->count() }} visits)</span>
                        </h3>
                    </div>
                    <div id="visit-map-{{ $userId }}" class="h-[400px] rounded-md bg-slate-200"></div>
                </div>
            @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #164E63 !important;
            border-color: #164E63 !important;
        }
        .flatpickr-day.inRange {
            background: rgba(22, 78, 99, 0.1) !important;
            border-color: #164E63 !important;
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr date range picker
            flatpickr('input[name="map_date_filter"]', {
                mode: 'range',
                dateFormat: 'Y-m-d',
                conjunction: ' to ',
                allowInput: false
            });
        });
        
        function filterByDate() {
            const datePicker = document.querySelector('[name="map_date_filter"]');
            if (!datePicker || !datePicker.value) return;
            
            const value = datePicker.value.trim();
            if (!value.includes(' to ')) return;
            
            const [fromDate, toDate] = value.split(' to ');
            
            window.location.href = 
                '{{ route("visit.dashboard") }}' +
                '?from_date=' + encodeURIComponent(fromDate) +
                '&to_date=' + encodeURIComponent(toDate);
        }
        
        function clearFilter() {
            window.location.href = '{{ route("visit.dashboard") }}';
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Clear datepicker default value
            const datePicker = document.querySelector('[name="map_date_filter"]'); 
            
            var visits = @json($visits);
            
            // Only show maps if we have visits
            if (!visits || visits.length === 0) {
                return;
            }
            
            var visitsByUser = {};
            
            // Group visits by user_id
            visits.forEach(function(visit) {
                if (!visitsByUser[visit.user_id]) {
                    visitsByUser[visit.user_id] = [];
                }
                visitsByUser[visit.user_id].push(visit);
            });
            
            // Create separate map for each user
            Object.keys(visitsByUser).forEach(function(userId) {
                var mapId = 'visit-map-' + userId;
                var mapElement = document.getElementById(mapId);
                if (!mapElement) return;
                
                var map = L.map(mapId).setView([20.5937, 78.9629], 5);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                var userVisits = visitsByUser[userId];
                var bounds = [];
                
                userVisits.forEach(function(visit) {
                    if (visit.location) {
                        try {
                            var coords = visit.location.split(',');
                            if (coords.length === 2) {
                                var lat = parseFloat(coords[0].trim());
                                var lng = parseFloat(coords[1].trim());
                                
                                if (!isNaN(lat) && !isNaN(lng)) {
                                    var marker = L.marker([lat, lng]).addTo(map);
                                    bounds.push([lat, lng]);
                                    
                                    marker.bindPopup(`
                                        <div>
                                            <strong>${visit.customer_name || 'N/A'}</strong><br>
                                            <small>Date: ${visit.target_date}</small><br>
                                            <small>Time: ${visit.time || 'N/A'}</small><br>
                                            <small>Phone: ${visit.phone || 'N/A'}</small><br>
                                            <small>Reason: ${visit.reason || 'N/A'}</small>
                                        </div>
                                    `);
                                }
                            }
                        } catch (e) {
                            console.log('Error parsing location for visit:', visit.id);
                        }
                    }
                });
                
                // Fit map to show all markers
                if (bounds.length > 0) {
                    map.fitBounds(bounds, { padding: [10, 10] });
                }
            });
        });
    </script>
    @endpush
@endsection