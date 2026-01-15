@extends('../layouts/' . $layout)

@section('subhead')
    <title>Opening Stock - Jewelry ERP</title>
@endsection
<style>
    /* Whole table area */
.opening-stock-table {
    width: 100%;
    border-collapse: separate !important;
    border-spacing: 0 12px !important;
}

/* Each table row becomes a “card” */
.opening-stock-table tbody tr {
    background: #ffffff;
    box-shadow: 0px 4px 14px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
}

/* First + last td rounded */
.opening-stock-table tbody tr td:first-child {
    border-radius: 12px 0 0 12px;
}
.opening-stock-table tbody tr td:last-child {
    border-radius: 0 12px 12px 0;
}

/* Header styling */
.opening-stock-table thead th {
    font-weight: 600;
    color: #475569;
    padding-bottom: 8px;
    font-size: 13px;
}

/* Table cells */
.opening-stock-table tbody td {
    padding: 7px 18px;
    font-size: 13px;
    vertical-align: middle;
    background-color: #fff;
}

/* Inputs styling */
.opening-stock-table input.form-control {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 8px;
    font-size: 13px;
    transition: 0.2s ease;
}

/* Hover & focus for inputs */
.opening-stock-table input.form-control:focus {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
}

/* Product name bold */
.product-name {
    font-weight: 600;
    font-size: 14px;
}

/* Barcode text */
.barcode-text {
    font-size: 12px;
    color: #6b7280;
}

/* Smooth row hover */
.opening-stock-table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0px 6px 18px rgba(0,0,0,0.12);
}

/* Search input style */
#product-search {
    border-radius: 10px;
    padding-left: 38px !important;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}

#product-search:focus {
    background: #fff;
    border-color: #6366f1;
}

</style>
@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Opening Stock</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Search Input -->
        <div class="intro-y col-span-12">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="relative w-full sm:w-64">
                    <x-base.lucide class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 text-slate-500" icon="Search" />
                    <input type="text" id="product-search" class="form-control box pl-10" placeholder="Search products...">
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="opening-stock-table -mt-2 border-separate border-spacing-y-[10px]">
                <thead>
                    <tr>
                        <th style="white-space: nowrap; border-bottom: 0;">
                            Product Name
                        </th>
                        <th style="white-space: nowrap; border-bottom: 0;">
                            Product Barcode
                        </th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">
                            Quantity
                        </th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">
                            MRP
                        </th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">
                            Sale Rate
                        </th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">
                            Purchase Price
                        </th>
                       <!-- <th style="white-space: nowrap; border-bottom: 0; text-align: center;">
                            ACTIONS
                        </th> -->
                    </tr>
                </thead>
                <tbody id="opening-stock-table-body">
                    @isset($products)
                        @foreach ($products as $product)
                            <tr class="intro-x opening-stock-row">
                                <td style="width: 10rem; border-bottom: 0; background-color: white; box-shadow: 20px 3px 20px rgba(0,0,0,0.05); border-radius: 0.375rem 0 0 0.375rem;">
                                    <div class="flex">
                                        <div class="whitespace-nowrap font-medium">{{ $product->product_name }}</div>
                                    </div>
                                </td>
                                <td style="border-bottom: 0; background-color: white; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->barcode_number ?? '-' }}</div>
                                </td>
                                <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                    <input type="number" step="0.01" min="0" class="form-control text-center quantity-input" data-product-id="{{ $product->id }}" value="{{ $product->quantity ? number_format($product->quantity, 2, '.', '') : '0.00' }}" style="font-size: 12px;">
                                </td>
                                <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                    <input type="number" step="0.01" min="0" class="form-control text-center mrp-input" data-product-id="{{ $product->id }}" value="{{ $product->mrp ? number_format($product->mrp, 2, '.', '') : '' }}" placeholder="-" style="font-size: 12px;">
                                </td>
                                <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                    <input type="number" step="0.01" min="0" class="form-control text-center sale-rate-input" data-product-id="{{ $product->id }}" value="{{ $product->sale_rate ? number_format($product->sale_rate, 2, '.', '') : '' }}" placeholder="-" style="font-size: 12px;">
                                </td>
                                <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05); border-radius: 0 0.375rem 0.375rem 0;">
                                    <input type="number" step="0.01" min="0" class="form-control text-center purchase-price-input" data-product-id="{{ $product->id }}" value="{{ $product->purchase_price ? number_format($product->purchase_price, 2, '.', '') : '' }}" placeholder="-" style="font-size: 12px;">
                                </td>
                              <!--  <td style="position: relative; width: 14rem; border-bottom: 0; background-color: white; padding: 0; box-shadow: 20px 3px 20px rgba(0,0,0,0.05); border-radius: 0.375rem 0 0 0.375rem;">
                                    <div style="display: flex; align-items: center; justify-content: center;">
                                       
                                            <a class="mr-3 flex items-center" href="{{ route('opening-stock.show', $product->id) }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Eye" />
                                                View
                                            </a>
                                            <a class="mr-3 flex items-center" href="{{ route('opening-stock.edit', $product->id) }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                Edit
                                            </a>
                                            <a class="flex items-center text-danger" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal" href="#"
                                                data-delete-route="{{ route('opening-stock.destroy', $product->id) }}"
                                                data-delete-name="{{ $product->product_name }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                            </a>
                                       
                                    </div>
                                </td> -->
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->

        <!-- Loading indicator -->
        <div id="loading-indicator" class="col-span-12 text-center py-4 hidden">
            <div class="inline-flex items-center">
                <x-base.loading-icon class="animate-spin h-5 w-5 mr-2" />
                Loading more products...
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
                    Do you really want to delete opening stock for <span class="font-medium" id="delete-product-name"></span>?
                    <br />This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <form id="delete-opening-stock-form" method="POST" action="" class="inline">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('[data-delete-route]');
    const deleteForm = document.getElementById('delete-opening-stock-form');
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

    // Search functionality
    const searchInput = document.getElementById('product-search');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.trim();
            currentPage = 1;
            loadProducts(searchTerm, true);
        }, 500);
    });

    // AJAX update functionality for input fields
    function updateOpeningStock(productId, field, value) {
        fetch(`/api/opening-stock/${productId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                [field]: value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Updated successfully');
            } else {
                console.error('Update failed');
            }
        })
        .catch(error => {
            console.error('Error updating:', error);
        });
    }

    // Add event listeners to input fields
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('quantity-input') ||
            e.target.classList.contains('mrp-input') ||
            e.target.classList.contains('sale-rate-input') ||
            e.target.classList.contains('purchase-price-input')) {

            const productId = e.target.getAttribute('data-product-id');
            const field = e.target.classList.contains('quantity-input') ? 'quantity' :
                         e.target.classList.contains('mrp-input') ? 'mrp' :
                         e.target.classList.contains('sale-rate-input') ? 'sale_rate' : 'purchase_price';
            const value = e.target.value;

            updateOpeningStock(productId, field, value);
        }
    });

    // Infinite scroll functionality
    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = {{ $products->hasMorePages() ? 'true' : 'false' }};
    let currentSearch = '';

    const tableBody = document.getElementById('opening-stock-table-body');
    const loadingIndicator = document.getElementById('loading-indicator');

    function loadProducts(search = '', reset = false) {
        if (reset) {
            currentPage = 1;
            currentSearch = search;
            tableBody.innerHTML = '';
            hasMorePages = true;
        }

        if (isLoading || !hasMorePages) return;

        isLoading = true;
        if (!reset) {
            loadingIndicator.classList.remove('hidden');
            currentPage++;
        }

        let url = `{{ route('opening-stock.index') }}?page=${currentPage}`;
        if (currentSearch) {
            url += `&search=${encodeURIComponent(currentSearch)}`;
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.data && data.data.length > 0) {
                data.data.forEach(product => {
                    const quantity = product.quantity ? parseFloat(product.quantity).toFixed(2) : '0.00';
                    const mrp = product.mrp ? parseFloat(product.mrp).toFixed(2) : '-';
                    const saleRate = product.sale_rate ? parseFloat(product.sale_rate).toFixed(2) : '-';
                    const purchasePrice = product.purchase_price ? parseFloat(product.purchase_price).toFixed(2) : '-';

                    let actionsHtml = '';
                   

                    const rowHtml = `
                       <tr class="intro-x opening-stock-row">
                        <td class="col-name">
                            <div class="product-name">${product.product_name}</div>
                        </td>

                         <td style="border-bottom: 0; background-color: white; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                            <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                ${product.barcode_number ?? '-'}
                            </div>
                        </td>

                        <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                            <input type="number" step="0.01" min="0"
                                class="form-control text-center quantity-input"
                                data-product-id="${product.id}"
                                value="${quantity}"
                                style="font-size: 12px;">
                        </td>

                        <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                            <input type="number" step="0.01" min="0"
                                class="form-control text-center mrp-input"
                                data-product-id="${product.id}"
                                value="${mrp !== '-' ? mrp : ''}"
                                placeholder="-"
                                style=" font-size: 12px;">
                        </td>

                        <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                            <input type="number" step="0.01" min="0"
                                class="form-control text-center sale-rate-input"
                                data-product-id="${product.id}"
                                value="${saleRate !== '-' ? saleRate : ''}"
                                placeholder="-"
                                style="font-size: 12px;">
                        </td>

                        <td style="border-bottom: 0; background-color: white; text-align: center; box-shadow: 20px 3px 20px rgba(0,0,0,0.05); border-radius: 0 0.375rem 0.375rem 0;">
                            <input type="number" step="0.01" min="0"
                                class="form-control text-center purchase-price-input"
                                data-product-id="${product.id}"
                                value="${purchasePrice !== '-' ? purchasePrice : ''}"
                                placeholder="-"
                                style="font-size: 12px;">
                        </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', rowHtml);
                });
            }

            hasMorePages = data.has_more;
            isLoading = false;
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error loading more data:', error);
            isLoading = false;
            loadingIndicator.classList.add('hidden');
        });
    }

    function loadMoreData() {
        loadProducts(currentSearch, false);
    }

    // Detect when user scrolls near bottom
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        // Load more when user is within 200px of bottom
        if (documentHeight - (scrollTop + windowHeight) < 200) {
            loadMoreData();
        }
    });
});
</script>
@endpush
