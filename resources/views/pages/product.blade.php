@extends('../layouts/' . $layout)

@section('subhead')
    <title>Products - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Products</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <a href="{{ route('products.create') }}">
                <x-base.button class="mr-2 shadow-md" variant="primary">
                    Add New Product
                </x-base.button>
            </a>
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
                @isset($products)
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
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
                            Product Name
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Barcode </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Category </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Company </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                            Min Rate
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                            Max Rate
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                            ACTIONS
                        </x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @isset($products)
                        @foreach ($products as $product)
                            <x-base.table.tr class="intro-x">
                                <x-base.table.td
                                    class="w-40 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="flex">
                                        <div class="whitespace-nowrap font-medium">{{ $product->product_name }}</div>
                                    </div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->barcode_number ?? '-' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->category->name ?? '-' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->product_company ?? '-' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->minimum_rate ? number_format($product->minimum_rate, 2) : '-' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->maximum_rate ? number_format($product->maximum_rate, 2) : '-' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
                                    <div class="flex items-center justify-center">
                                        <a class="mr-3 flex items-center" href="{{ route('products.show', $product->id) }}">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="Eye" />
                                            View
                                        </a>
                                        <a class="mr-3 flex items-center" href="{{ route('products.edit', $product->id) }}">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                            Edit
                                        </a>
                                        <a class="flex items-center text-danger" data-tw-toggle="modal"
                                            data-tw-target="#delete-confirmation-modal" href="#"
                                            data-delete-route="{{ route('products.delete', $product->id) }}"
                                            data-delete-name="{{ $product->product_name }}">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                        </a>
                                    </div>
                                </x-base.table.td>
                            </x-base.table.tr>
                        @endforeach
                    @endisset
                </x-base.table.tbody>
            </x-base.table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        @isset($products)
            <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
                <div class="w-full sm:mr-auto sm:w-auto">
                    {{ $products->onEachSide(1)->links() }}
                </div>
                <x-base.form-select class="!box mt-3 w-20 sm:mt-0">
                    <option>10</option>
                    <option>25</option>
                    <option>35</option>
                    <option>50</option>
                </x-base.form-select>
            </div>
        @endisset
        <!-- END: Pagination -->
    </div>
    <!-- BEGIN: Delete Confirmation Modal -->
    <x-base.dialog id="delete-confirmation-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
                <div class="mt-5 text-3xl">Are you sure?</div>
                <div class="mt-2 text-slate-500">
                    Do you really want to delete <span class="font-medium" id="delete-product-name"></span>?
                    <br />This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <form id="delete-product-form" method="POST" action="" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button"
                        variant="outline-secondary">
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
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('[data-delete-route]');
                const deleteForm = document.getElementById('delete-product-form');
                const deleteProductName = document.getElementById('delete-product-name');

                deleteButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        const route = this.getAttribute('data-delete-route');
                        const name = this.getAttribute('data-delete-name');

                        if (deleteForm && route) {
                            deleteForm.setAttribute('action', route);
                        }

                        if (deleteProductName) {
                            deleteProductName.textContent = name || 'this product';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
