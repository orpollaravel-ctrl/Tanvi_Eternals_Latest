@extends('../layouts/' . $layout)

@section('subhead')
    <title>Purchases - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Purchases</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            @if (auth()->check() && auth()->user()->hasPermission('create-tool-purchases'))
                <a href="{{ route('purchases.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New Purchase
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
                        <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                    </x-base.menu.item>
                    <x-base.menu.item>
                        <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to Excel
                    </x-base.menu.item>
                    <x-base.menu.item>
                        <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to PDF
                    </x-base.menu.item>
                </x-base.menu.items>
            </x-base.menu>
            <div class="mx-auto hidden text-slate-500 md:block">
                @isset($purchases)
                    Showing {{ $purchases->count() }} entries
                @endisset
            </div>
            <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto md:ml-0">
                <div class="relative w-56 text-slate-500">
                    <x-base.form-input class="!box w-56 pr-10" type="text" placeholder="Search..." />
                    <x-base.lucide class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4" icon="Search" />
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">
                            #
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">
                            Bill Number
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Vendor </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Bill Date </x-base.table.th>
                        <!-- <x-base.table.th class="whitespace-nowrap border-b-0"> Delivery Date </x-base.table.th> -->
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-right">
                            Total Amount
                        </x-base.table.th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-tool-purchases') || auth()->user()->hasPermission('delete-tool-purchases')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                                ACTIONS
                            </x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @isset($purchases)
                        @foreach ($purchases as $purchase)
                            <x-base.table.tr class="intro-x">
                                <x-base.table.td
                                    class="w-40 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="flex">
                                        <div class="whitespace-nowrap font-medium"> {{ $loop->iteration }}</div>
                                    </div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="w-40 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="flex">
                                        <div class="whitespace-nowrap font-medium">{{ $purchase->bill_number }}</div>
                                    </div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                        {{ $purchase->vendor->name ?? '-' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                        {{ $purchase->bill_date?->format('d/m/Y') ?? '-' }}</div>
                                </x-base.table.td>
                                <!-- <x-base.table.td
                                            class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                            <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $purchase->delivery_date?->format('d/m/Y') ?? '-' }}</div>
                                        </x-base.table.td> -->
                                <x-base.table.td
                                    class="border-b-0 bg-white text-right shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                        ₹{{ number_format($purchase->total_invoice_amount, 2) }}</div>
                                </x-base.table.td>
                                @if (auth()->check() && (auth()->user()->hasPermission('edit-tool-purchases') || auth()->user()->hasPermission('delete-tool-purchases')))
                                    <x-base.table.td
                                        class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            @if (auth()->check() && auth()->user()->hasPermission('edit-tool-purchases'))
                                                <a class="mr-3 flex items-center"
                                                    href="{{ route('purchases.edit', $purchase->id) }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                    Edit
                                                </a>
                                            @endif
                                            @if (auth()->check() && auth()->user()->hasPermission('delete-tool-purchases'))
                                                <a class="flex items-center text-danger" data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal" href="#"
                                                    data-delete-route="{{ route('purchases.delete', $purchase->id) }}"
                                                    data-delete-name="{{ $purchase->bill_number }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                                </a>
                                            @endif
                                        </div>
                                    </x-base.table.td>
                                @endif
                            </x-base.table.tr>
                        @endforeach
                    @endisset
                </x-base.table.tbody>
            </x-base.table>
        </div>
        <!-- END: Data List -->
    </div>
    <!-- BEGIN: Delete Confirmation Modal -->
    <x-base.dialog id="delete-confirmation-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
                <div class="mt-5 text-3xl">Are you sure?</div>
                <div class="mt-2 text-slate-500">
                    Do you really want to delete <span class="font-medium" id="delete-purchase-name"></span>?
                    <br />This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <form id="delete-purchase-form" method="POST" action="" class="inline">
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
    <!-- END: Delete Confirmation Modal -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteButtons = document.querySelectorAll('[data-delete-route]');
                const deleteForm = document.getElementById('delete-purchase-form');
                const deletePurchaseName = document.getElementById('delete-purchase-name');

                deleteButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const route = this.getAttribute('data-delete-route');
                        const name = this.getAttribute('data-delete-name');

                        if (deleteForm && route) {
                            deleteForm.setAttribute('action', route);
                        }

                        if (deletePurchaseName) {
                            deletePurchaseName.textContent = name || 'this purchase';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
