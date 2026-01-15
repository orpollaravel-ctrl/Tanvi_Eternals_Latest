@extends('../layouts/' . $layout)

@section('subhead')
    <title>{{ $product->product_name }} - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="mt-10 flex items-center">
        <h2 class="text-lg font-medium">{{ $product->product_name }}</h2>
        <a href="{{ route('products.edit', $product->id) }}" class="ml-auto">
            <x-base.button variant="primary">Edit Product</x-base.button>
        </a>
    </div>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Product Details Card -->
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box">
                <div class="flex items-center border-b border-slate-200 p-5 dark:border-darkmode-400">
                    <h2 class="text-base font-medium">Product Information</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4 gap-y-6">
                        <!-- Product Name -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Product Name</div>
                            <div class="mt-2 font-medium">{{ $product->product_name }}</div>
                        </div>

                        <!-- Category 
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Category</div>
                            <div class="mt-2 font-medium">{{ $product->category->name ?? '-' }}</div>
                        </div>-->

                        <!-- Barcode Number -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Barcode Number</div>
                            <div class="mt-2 font-medium">{{ $product->barcode_number ?? '-' }}</div>
                        </div>
						
						    <!-- Tool Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Product type</div>
                            <div class="mt-2 font-medium">{{ $product->product_type ?? '-' }}</div>
                        </div>
                        <!-- Tool Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Tool Code</div>
                            <div class="mt-2 font-medium">{{ $product->tool_code ?? '-' }}</div>
                        </div>

                        <!-- Product Company 
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Product Company</div>
                            <div class="mt-2 font-medium">{{ $product->product_company ?? '-' }}</div>
                        </div>-->

                        <!-- HSN Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">HSN Code</div>
                            <div class="mt-2 font-medium">{{ $product->hsn_code ?? '-' }}</div>
                        </div>

                        <!-- Minimum Rate -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Minimum Rate</div>
                            <div class="mt-2 font-medium">{{ $product->minimum_rate ? number_format($product->minimum_rate, 2) : '-' }}</div>
                        </div>

                        <!-- Maximum Rate -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Maximum Rate</div>
                            <div class="mt-2 font-medium">{{ $product->maximum_rate ? number_format($product->maximum_rate, 2) : '-' }}</div>
                        </div>

                        <!-- Minimum Quantity -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Minimum Quantity</div>
                            <div class="mt-2 font-medium">{{ $product->minimum_quantity }}</div>
                        </div>

                        <!-- Reorder Quantity 
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Reorder Quantity</div>
                            <div class="mt-2 font-medium">{{ $product->reorder_quantity }}</div>
                        </div>-->

                        <!-- Unit Type -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Unit Type</div>
                            <div class="mt-2 font-medium">{{ $product->unit->name ?? '-' }} ({{ $product->unit->symbol ?? '-' }})</div>
                        </div>

                        <!-- Created Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Created Date</div>
                            <div class="mt-2 font-medium">{{ $product->created_at->format('M d, Y H:i') }}</div>
                        </div>

                        <!-- Last Updated -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="text-xs text-slate-500">Last Updated</div>
                            <div class="mt-2 font-medium">{{ $product->updated_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Photo Card -->
        <div class="intro-y col-span-12 lg:col-span-4">
            <div class="box">
                <div class="flex items-center border-b border-slate-200 p-5 dark:border-darkmode-400">
                    <h2 class="text-base font-medium">Product Photo</h2>
                </div>
                <div class="flex flex-col items-center justify-center p-5">
                    @if ($product->product_photo)
                        <img src="{{ asset('media/product/' . $product->id . '/' . $product->product_photo) }}"
                            alt="Product Photo" class="h-64 w-64 rounded-md object-cover">
                    @else
                        <img src="{{ asset('media-example/no-image.png') }}"
                            alt="No Image" class="h-64 w-64 rounded-md object-cover">
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 flex flex-col gap-2">
                <a href="{{ route('products.edit', $product->id) }}" class="block">
                    <x-base.button class="w-full" variant="primary">Edit Product</x-base.button>
                </a>
                <a href="{{ route('products.index') }}" class="block">
                    <x-base.button class="w-full" variant="outline-secondary">Back to Products</x-base.button>
                </a>
                <a href="#" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal" class="block"
                    data-delete-route="{{ route('products.delete', $product->id) }}"
                    data-delete-name="{{ $product->product_name }}">
                    <x-base.button class="w-full" variant="danger">Delete Product</x-base.button>
                </a>
            </div>
        </div>
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
