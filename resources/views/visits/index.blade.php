@extends('../layouts/' . $layout)

@section('subhead')
    <title>Visits - Tanvi Eternals</title>
@endsection

@section('subcontent')

<h2 class="intro-y mt-10 text-lg font-medium">Visits</h2>

<div class="grid grid-cols-12 gap-6">
    <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-end sm:flex-nowrap"> 
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

    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
            <x-base.table.thead>
                <x-base.table.tr> 
                    <x-base.table.th class="border-b-0">Customer</x-base.table.th> 
                    <x-base.table.th class="border-b-0">Phone</x-base.table.th>
                    <x-base.table.th class="border-b-0">Visit Date</x-base.table.th>
                    <x-base.table.th class="border-b-0">Time</x-base.table.th>
                    <x-base.table.th class="border-b-0">Reason</x-base.table.th>
                    <x-base.table.th class="border-b-0 text-center">Actions</x-base.table.th>
                </x-base.table.tr>
            </x-base.table.thead>

            <x-base.table.tbody>
                @foreach($visits as $visit)
                <x-base.table.tr class="intro-x"> 
                    <x-base.table.td class="bg-white shadow">
                        {{ $visit->customer_name }}
                    </x-base.table.td> 
                    <x-base.table.td class="bg-white shadow">
                        {{ $visit->phone }}
                    </x-base.table.td>

                    <x-base.table.td class="bg-white shadow">
                        {{ $visit->target_date }}
                    </x-base.table.td>

                    <x-base.table.td class="bg-white shadow">
                        {{ $visit->time }}
                    </x-base.table.td>

                    <x-base.table.td class="bg-white shadow">
                        {{ Str::limit($visit->reason, 30) }}
                    </x-base.table.td>

                    <x-base.table.td class="bg-white shadow text-center last:rounded-r-md">
                        <div class="flex items-center text-primary">
                            <a class="mr-3 flex items-center text-primary text-primary" href="{{ route('visits.show', $visit->id) }}">
                                <x-base.lucide class="w-4 h-4" icon="Eye" />View
                            </a> 
                        </div>
                    </x-base.table.td>
                </x-base.table.tr>
                @endforeach
            </x-base.table.tbody>
        </x-base.table>
    </div>
</div>

{{-- Delete Modal --}}
<x-base.dialog id="delete-confirmation-modal">
    <x-base.dialog.panel>
        <div class="p-5 text-center">
            <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
            <div class="mt-5 text-3xl">Are you sure?</div>
            <div class="mt-2 text-slate-500">
                Delete <span class="font-medium" id="delete-name"></span> visit?
            </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <form id="delete-form" method="POST">
                @csrf @method('DELETE')
                <x-base.button data-tw-dismiss="modal" variant="outline-secondary">Cancel</x-base.button>
                <x-base.button variant="danger" type="submit">Delete</x-base.button>
            </form>
        </div>
    </x-base.dialog.panel>
</x-base.dialog>

@push('scripts')
<script>
    document.addEventListener('click', function(e){
        let btn = e.target.closest('[data-delete-route]');
        if(!btn) return;

        document.getElementById('delete-form').action = btn.dataset.deleteRoute;
        document.getElementById('delete-name').innerText = btn.dataset.deleteName;
    });

    function printVisits(){
        window.open('{{ route("visits.print") }}','_blank');
    }

    function exportVisitsToExcel(){
        window.location.href = '{{ route("visits.export.excel") }}';
    }
</script>
@endpush

@endsection
