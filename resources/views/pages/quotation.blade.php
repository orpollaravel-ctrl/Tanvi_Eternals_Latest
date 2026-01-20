@extends('../layouts/' . $layout)

@section('subhead')
    <title>Quotations - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Quotations</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
            @if (auth()->check() && auth()->user()->hasPermission('create-quotations'))
                <a href="{{ route('quotations.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New Quotation
                    </x-base.button>
                </a>
            @endif
            <x-base.menu>
                <x-base.menu.button class="!box px-2" as="x-base.button">
                    <span class="flex h-5 w-5 items-center justify-center">
                        <x-base.lucide class="h-4 w-4" icon="Plus" />
                    </span>
                </x-base.menu.button>
                <x-base.menu.items class="w-40">
                    <x-base.menu.item>
                        <a href="javascript:void(0);" onclick="printQuotations()" class="flex">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                        </a>
                    </x-base.menu.item>
                    <x-base.menu.item>
                        <a href="javascript:void(0);" onclick="exportQuotationsToExcel()" class="flex">
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
                        <x-base.table.th class="whitespace-nowrap border-b-0">Quotation No.</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Customer </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Salesman</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Contact</x-base.table.th> 
                        <x-base.table.th class="whitespace-nowrap border-b-0">Metal</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Purity</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Diamond</x-base.table.th>
                        {{-- <x-base.table.th class="whitespace-nowrap border-b-0">Client PDF</x-base.table.th> --}}
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-quotations') || auth()->user()->hasPermission('delete-quotations')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>  
                </x-base.table.thead>
                <x-base.table.tbody>
                    @foreach ($quotations as $quotation)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="whitespace-nowrap font-medium text-primary">{{ $quotation->id }}</div>
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="whitespace-nowrap font-medium">{{ $quotation->customer_name ?? '-'}}</div>
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="whitespace-nowrap text-xs text-slate-500">{{ $quotation->salesman_name ?? '-' }}</div>
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="whitespace-nowrap text-xs text-slate-500">{{ $quotation->contact }}</div>
                            </x-base.table.td> 
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="whitespace-nowrap text-xs text-slate-500">{{ ucfirst($quotation->metal) }}</div>
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="whitespace-nowrap text-xs text-slate-500">{{ $quotation->purity }}</div>
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="whitespace-nowrap text-xs text-slate-500">{{ $quotation->diamond }}</div>
                            </x-base.table.td>
                            {{-- <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                @if($quotation->client_pdf)
                                    <a href="{{ asset($quotation->client_pdf) }}" target="_blank" class="flex items-center text-success">
                                        <x-base.lucide class="mr-1 h-4 w-4" icon="FileText" />
                                        View PDF
                                    </a>
                                @else
                                    <span class="text-slate-400">No PDF</span>
                                @endif
                            </x-base.table.td> --}}
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-quotations') || auth()->user()->hasPermission('delete-quotations')))
                                <x-base.table.td
                                    class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
                                    <div class="flex items-center justify-center">
                                        <a class="mr-3 flex items-center text-warning" data-tw-toggle="modal" data-tw-target="#import-pdf-modal" data-quotation-id="{{ $quotation->id }}" href="#">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="file" />
                                             PDF
                                        </a> 
                                         <a class="mr-3 flex items-center text-primary"
                                        href="{{ route('quotations.show', $quotation->id) }}">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="Eye" />
                                            View
                                        </a> 
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-quotations'))
                                            <a class="mr-3 flex items-center"
                                                href="{{ route('quotations.edit', $quotation->id) }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-quotations'))
                                            <a class="flex items-center text-danger" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal" href="#"
                                                data-delete-route="{{ route('quotations.destroy', $quotation->id) }}"
                                                data-delete-name="{{ $quotation->client->name ?? '' }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                            </a>
                                        @endif
                                    </div>
                                </x-base.table.td>
                            @endif
                        </x-base.table.tr>
                    @endforeach
                </x-base.table.tbody>
            </x-base.table>
        </div> 
    </div> 
     <x-base.dialog id="delete-confirmation-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
                <div class="mt-5 text-3xl">Are you sure?</div>
                <div class="mt-2 text-slate-500">
                    Do you really want to delete <span class="font-medium" id="delete-quotation-name"></span>?
                    <br />This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <form id="delete-quotation-form" method="POST" action="" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">
                        Cancel
                    </x-base.button>
                    <x-base.button class="w-24" type="submit" variant="danger">
                        Delete
                    </x-base.button>
                </form>
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
    <x-base.dialog id="import-pdf-modal">
        <x-base.dialog.panel>
            <form method="POST"
                action="{{ route('quotations.import.pdf') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="quotation_id" id="import-quotation-id">
                <input type="hidden" name="customer_id" id="import-client-id">
                <input type="hidden" name="customer_code" id="import-client-code">
                <input type="hidden" name="contact" id="import-client-contact">
                <div class="p-5">
                    <h3 class="text-lg font-medium mb-4">Import Quotation PDF</h3>
                   <input type="file"
                        name="pdf[]"
                        accept="application/pdf"
                        multiple
                        required
                        class="w-full border p-2 rounded">
                    <div class="mt-4 text-right">
                        <x-base.button type="submit" variant="primary">
                            Import
                        </x-base.button>
                    </div>
                </div>
            </form>
        </x-base.dialog.panel>
    </x-base.dialog>
 
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.body.addEventListener('click', function (e) {
                    const btn = e.target.closest('[data-quotation-id]');
                    if (!btn) return; 
                     document.getElementById('import-quotation-id').value = btn.getAttribute('data-quotation-id'); 
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                const deleteButtons = document.querySelectorAll('[data-delete-route]');
                const deleteForm = document.getElementById('delete-quotation-form');
                const deleteQuotationName = document.getElementById('delete-quotation-name');

                deleteButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const route = this.getAttribute('data-delete-route');
                        const name = this.getAttribute('data-delete-name');
                        
                        if (deleteForm && route) {
                            deleteForm.setAttribute('action', route);
                        }

                        if (deleteQuotationName) {
                            deleteQuotationName.textContent = name || 'this quotation';
                        }
                    });
                });
            });
 
            function printQuotations() {
                window.open('{{ route('quotations.print') }}', '_blank');
            }
 
            function exportQuotationsToExcel() {
                window.location.href = '{{ route('quotations.export.excel') }}';
            }
        </script>
    @endpush
@endsection
