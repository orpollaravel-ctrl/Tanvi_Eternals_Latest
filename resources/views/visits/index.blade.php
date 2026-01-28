@extends('../layouts/' . $layout)

@section('subhead')
    <title>Visits - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <style>
        .visit-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 640px) {
            .visit-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .visit-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1280px) {
            .visit-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .visit-card {
            width: 100%;
            max-width: 100%;
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .visit-card:hover {
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
        }
    </style>
    <h2 class="intro-y mt-10 text-lg font-medium">Visits</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-start gap-3 sm:flex-nowrap">
            <div class="flex items-center gap-2">
                <x-base.menu>
                    <x-base.menu.button class="!box px-2" as="x-base.button">
                        <span class="flex h-5 w-5 items-center justify-center">
                            <x-base.lucide class="h-4 w-4" icon="Plus" />
                        </span>
                    </x-base.menu.button>
                    <x-base.menu.items class="w-40">
                        <x-base.menu.item>
                            <a href="javascript:void(0);" onclick="printVisits()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                            </a>
                        </x-base.menu.item>
                        <x-base.menu.item>
                            <a href="javascript:void(0);" onclick="exportVisitsToExcel()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to Excel
                            </a>
                        </x-base.menu.item>
                    </x-base.menu.items>
                </x-base.menu>
            </div>
        </div>
        <div class="intro-y col-span-12">
            <div class="visit-grid">
                @forelse($visits->groupBy('user_id') as $userId => $userVisits)
                    @php
                        $user = $userVisits->first()->user;
                        $totalVisits = $userVisits->count();
                    @endphp
                    <a href="{{ route('visits.show', $userId) }}" class="visit-card block">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-semibold text-primary">
                                {{ ucwords($user->name ?? 'Unknown User') }}
                            </span>
                        </div>
                        <div class="text-2xl font-bold mb-4">
                            {{ $totalVisits }} Visits
                        </div>
                        
                        <div class="text-sm text-slate-600 mb-3">
                            Latest: {{ $userVisits->first()->customer_name ?? 'N/A' }}
                        </div>
                        
                        <div class="text-sm text-slate-600 mb-3">
                            Date: {{ $userVisits->first()->target_date ?? 'N/A' }}
                        </div> 
                    </a>
                @empty
                    <div class="col-span-full text-center text-slate-500 py-10">
                        No visits found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function printVisits(){
                window.open('{{ route("visits.print") }}','_blank');
            }

            function exportVisitsToExcel(){
                window.location.href = '{{ route("visits.export.excel") }}';
            }
        </script>
    @endpush
@endsection
