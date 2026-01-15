@extends('../layouts/' . $layout)

@section('subhead')
    <title>Inventory Calculation - Jewelry ERP</title>
@endsection
<style>
    /* Modal Footer Styling */
    #purchase-history-modal .modal-footer-box,
    #assign-history-modal .modal-footer-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 14px 0;
        border-top: 1px solid #e6e6e6;
        margin-top: 10px;
    }

    #purchase-history-modal .footer-stats,
    #assign-history-modal .footer-stats {
        font-size: 14px;
        line-height: 1.4;
    }

    #purchase-history-modal .footer-stats span,
    #assign-history-modal .footer-stats span {
        font-weight: 600;
        color: #333;
    }

    #purchase-history-modal .close-btn-custom,
    #assign-history-modal .close-btn-custom {
        padding: 8px 16px;
        border-radius: 6px;
        background: #f0f0f0;
        transition: 0.15s ease;
        cursor: pointer;
        font-size: 14px;
    }

    #purchase-history-modal .close-btn-custom:hover,
    #assign-history-modal .close-btn-custom:hover {
        background: #e0e0e0;
    }
    .bs-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 14px;
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        overflow: hidden;
    }

    .bs-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        padding: 10px 12px;
        text-align: left;
        white-space: nowrap;
    }

    .bs-table tbody td {
        border-top: 1px solid #dee2e6;
        padding: 10px 12px;
    }

    .bs-table tbody tr:hover {
        background: #f2f6ff;
    }

    /* Rounded corners for full table */
    .bs-table thead tr:first-child th:first-child {
        border-top-left-radius: 6px;
    }
    .bs-table thead tr:first-child th:last-child {
        border-top-right-radius: 6px;
    }

    .bs-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 6px;
    }
    .bs-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 6px;
    }
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
    padding: 14px 18px;
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
	width : 150%;
}

#product-search:focus {
    background: #fff;
    border-color: #6366f1;
}

</style>

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Inventory Calculation</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Search Input -->
        <div class="intro-y col-span-12">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
               <div class="flex flex-col sm:flex-row sm:items-center gap-3"> 
                    <div class="relative w-full sm:w-64">
                        <x-base.lucide class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 text-slate-500" icon="Search" />
                        <input type="text" id="product-search" class="form-control box pl-10"
                            placeholder="Search products / Tool code / Barcode...">
                    </div>
                </div>
                <div style="display: flex; flex: auto; margin-left: 140px;">
                     <div class="box px-4 py-2 text-sm font-medium">
                        Total Remaining Value:
                        <span class="text-primary font-semibold" id="total-remaining-value">  {{ number_format($totalRemainingValue, 2) }}</span>
                    </div>
                </div>
                <div class="mt-3 sm:mt-0">
                    <x-base.menu>
                        <x-base.menu.button class="!box px-2" as="x-base.button">
                            <span class="flex h-5 w-5 items-center justify-center">
                                <x-base.lucide class="h-4 w-4" icon="Plus" />
                            </span>
                        </x-base.menu.button>
                        <x-base.menu.items class="w-40">
                            <x-base.menu.item>
                                <a href="javascript:void(0);" onclick="printInventory()" class="flex">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                                </a>
                            </x-base.menu.item>
                            <x-base.menu.item>
                                <a href="javascript:void(0);" onclick="exportInventoryToExcel()" class="flex">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to Excel
                                </a>
                            </x-base.menu.item>
                        </x-base.menu.items>
                    </x-base.menu>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data Table -->
       <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="opening-stock-table -mt-2 border-separate border-spacing-y-[10px]">
                <thead>
                    <tr>
                        <th style="white-space: nowrap; border-bottom: 0;">#</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Tool Code</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Product</th>
                    <!-- <th style="white-space: nowrap; border-bottom: 0;">Opening Stock Details</th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">Total Purchased Qty</th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">Total Purchased Value</th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">Total Assigned Qty</th>-->
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">Remaining Qty</th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">Remaining Value</th>
                        <th style="white-space: nowrap; border-bottom: 0; text-align: center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="inventory-table-body">
                    @forelse ($inventory as $inv)
                        <tr class="intro-x inventory-row">
                            <!-- ID -->
                            <td style="width: 6rem; border-bottom: 0; background-color: white; 
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05); 
                                border-radius: 0.375rem 0 0 0.375rem;">
                                <div class="whitespace-nowrap font-medium pl-3">
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            
                            
                            <!-- Product Tool Code -->
                            <td style="border-bottom: 0; background-color: white; 
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05); text-align: center;" >
                                <div class="font-medium whitespace-nowrap">
                                    {{ $inv['product']->tool_code}}
                                </div>
                            </td>
                            <!-- Product Name -->
                            <td style="border-bottom: 0; background-color: white; 
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05); text-align: center;" >
                                <div class="font-medium whitespace-nowrap">
                                    {{ $inv['product']->product_name }} (   {{ $inv['product']->barcode_number }})
                                </div>
                            </td>

                            <!-- Barcode
                            <td style="border-bottom: 0; background-color: white; 
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05); text-align: center;" >
                                <div class="font-medium whitespace-nowrap">
                                    {{ $inv['product']->barcode_number }}
                                </div>
                            </td>	
                            Opening Stock Details 
                            <td style="border-bottom: 0; background-color: white; 
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05); font-size: 12px;">
                                Qty: {{ number_format($inv['opening_stock']['qty'], 2) }}<br>
                                Sale Rate: {{ number_format($inv['opening_stock']['sale_rate'], 2) }}<br>
                                MRP: {{ number_format($inv['opening_stock']['mrp'], 2) }}<br>
                                Purchase Price: {{ number_format($inv['opening_stock']['purchase_price'], 2) }}
                            </td>-->

                            <!-- Purchased Qty 
                            <td style="border-bottom: 0; background-color: white; text-align: center;
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                {{ number_format($inv['total_purchased_qty'], 2) }}
                            </td>-->

                            <!-- Purchased Value
                            <td style="border-bottom: 0; background-color: white; text-align: center;
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                {{ number_format($inv['total_purchased_value'], 2) }}
                            </td> -->

                            <!-- Assigned Qty 
                            <td style="border-bottom: 0; background-color: white; text-align: center;
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                {{ number_format($inv['total_assigned_qty'], 2) }}
                            </td>--> 
                            
                            <td style="border-bottom: 0; background-color: white; text-align: center;
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                {{ number_format($inv['remaining_qty'], 2) }}
                            </td>
 
                            <td style="border-bottom: 0; background-color: white; text-align: center;
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05);">
                                {{ number_format($inv['remaining_value'], 2) }}
                            </td>
 
                            <td style="border-bottom: 0; background-color: white; position: relative;
                                text-align: center; 
                                box-shadow: 20px 3px 20px rgba(0,0,0,0.05); 
                                border-radius: 0 0.375rem 0.375rem 0;">
                                <div class="flex items-center justify-center py-2">

                                    <button type="button"
                                        class="flex items-center mr-3 text-primary purchase-history-btn"
                                        data-tw-toggle="modal"
                                        data-tw-target="#purchase-history-modal"
                                        data-product-id="{{ $inv['product']->id }}"
                                        data-product-name="{{ $inv['product']->product_name }}">
                                        <x-base.lucide class="mr-1 h-4 w-4" icon="FileText" /> Purchase History
                                    </button>

                                    <button type="button"
                                        class="flex items-center text-success assign-history-btn"
                                        data-tw-toggle="modal"
                                        data-tw-target="#assign-history-modal"
                                        data-product-id="{{ $inv['product']->id }}"
                                        data-product-name="{{ $inv['product']->product_name }}">
                                        <x-base.lucide class="mr-1 h-4 w-4" icon="List" /> Assign History
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="9"
                                style="text-align: center; padding: 1rem; color: #64748b;">
                                No inventory data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> 
        <div id="loading-indicator" class="col-span-12 text-center py-4 hidden">
            <div class="inline-flex items-center">
                <x-base.loading-icon class="animate-spin h-5 w-5 mr-2" />
                Loading more products...
            </div>
        </div>
    </div>
 
    <x-base.dialog id="purchase-history-modal">
        <x-base.dialog.panel>
            <x-base.dialog.title>
                <h2 class="font-medium text-base mr-auto">Purchase History - <span id="purchase-product-name"></span></h2>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <div class="overflow-x-auto">
                    <table class="-mt-2 border-separate border-spacing-y-[10px] w-full bs-table">
                        <thead>
                            <tr>
                                <th>Bill Date</th>
                                <th>Bill Number</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
 
                        <tbody id="purchase-history-body"> 
                        </tbody>
                    </table>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="modal-footer-box">

                    <div class="footer-stats">
                        <div>Total Qty: <span id="purchase-total-qty">0</span></div>
                        <div>Total Value: <span id="purchase-total-value">0</span></div>
                    </div>

                    <button class="close-btn-custom" data-tw-dismiss="modal">Close</button>

                </div>
            </x-base.dialog.footer>

        </x-base.dialog.panel>
    </x-base.dialog>
 
    <x-base.dialog id="assign-history-modal">
        <x-base.dialog.panel>
            <x-base.dialog.title>
                <h2 class="font-medium text-base mr-auto">Assign History - <span id="assign-product-name"></span></h2>
            </x-base.dialog.title>
            <x-base.dialog.description>
                <div class="overflow-x-auto">
                    <table class="-mt-2 border-separate border-spacing-y-[10px] w-full bs-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Employee</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
 
                        <tbody id="assign-history-body"> 
                        </tbody>
                    </table>
                </div>
            </x-base.dialog.description>
            <x-base.dialog.footer>
                <div class="modal-footer-box">

                    <div class="footer-stats">
                        <div>Total Qty: <span id="assign-total-qty">0</span></div>
                        <div>Total Value: <span id="assign-total-value">0</span></div>
                    </div>

                    <button class="close-btn-custom" data-tw-dismiss="modal">Close</button>

                </div>
            </x-base.dialog.footer>
        </x-base.dialog.panel>
    </x-base.dialog>

    <script>
        let totalRemainingValue = 0;
        document.addEventListener('DOMContentLoaded', function () {
            let currentPage = 1;
            let isLoading = false;  
            let currentSearch = '';

            const tableBody = document.getElementById('inventory-table-body');
            const loadingIndicator = document.getElementById('loading-indicator');
 
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

            function loadProducts(search = '', reset = false) {
                if (reset) {
                    currentPage = 1;
                    currentSearch = search;
                    tableBody.innerHTML = '';
                    hasMorePages = true;

                    totalRemainingValue = 0;
                    document.getElementById('total-remaining-value').textContent = '0.00';
                }

                if (isLoading || !hasMorePages) return;

                isLoading = true;
                if (!reset) {
                    loadingIndicator.classList.remove('hidden');
                    currentPage++;
                }

                let url = `{{ route('inventory-calculation.index') }}?page=${currentPage}`;
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
                        data.data.forEach((inv, index) => {
                            const rowNumber = reset ? (index + 1) : ((currentPage - 1) * 25 + index + 1);
                            const rowHtml = `
                                <tr class="intro-x inventory-row">
                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md"
									style='width: 6rem; height:60px; border-bottom: 0; background-color: white; 
										box-shadow: 20px 3px 20px rgba(0,0,0,0.05); 
										border-radius: 0.375rem 0 0 0.375rem;'
									>
									  <div class="whitespace-nowrap font-medium pl-3">
                                        ${rowNumber}
										</div>
                                    </td>
									<td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]"  style="text-align: center;">
                                        ${inv.product.tool_code}
                                    </td>
                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]"  style="text-align: center;">
                                        ${inv.product.product_name} ((${inv.product.barcode_number}))
                                    </td>
                                  <!--  <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                        Qty: ${parseFloat(inv.opening_stock.qty).toFixed(2)}<br>
                                        Sale Rate: ${parseFloat(inv.opening_stock.sale_rate).toFixed(2)}<br>
                                        MRP: ${parseFloat(inv.opening_stock.mrp).toFixed(2)}<br>
                                        Purchase Price: ${parseFloat(inv.opening_stock.purchase_price).toFixed(2)}
                                    </td>

                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]" style="text-align: center;">
                                        ${parseFloat(inv.total_purchased_qty).toFixed(2)}
                                    </td>

                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                        ${parseFloat(inv.total_purchased_value).toFixed(2)}
                                    </td>

                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                        ${parseFloat(inv.total_assigned_qty).toFixed(2)}
                                    </td>-->

                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]" style="text-align: center;">
                                        ${parseFloat(inv.remaining_qty).toFixed(2)}
                                    </td>

                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]" style="text-align: center;">
                                        ${parseFloat(inv.remaining_value).toFixed(2)}
                                    </td>

                                    <td
                                        class="relative border-b-0 bg-white py-0 text-center dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400"
                                    >
                                        <div class="flex items-center justify-center">
                                            <!-- Purchase History -->
                                            <button type="button" class="flex items-center mr-3 text-primary purchase-history-btn"
                                                data-tw-toggle="modal"
                                                data-tw-target="#purchase-history-modal"
                                                data-product-id="${inv.product.id}"
                                                data-product-name="${inv.product.product_name}">
                                              <svg class="mr-1 h-4 w-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14,2 14,8 20,8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg> Purchase History
                                            </button>
                                            <!-- Assign History -->
                                            <button type="button" class="flex items-center text-success assign-history-btn"
                                                data-tw-toggle="modal"
                                                data-tw-target="#assign-history-modal"
                                                data-product-id="${inv.product.id}"
                                                data-product-name="${inv.product.product_name}">
                                                 <svg class="mr-1 h-4 w-4 lucide lucide-list" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg> Assign History
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            totalRemainingValue += parseFloat(inv.remaining_value);
                            tableBody.insertAdjacentHTML('beforeend', rowHtml);
                        });
                    }
                    document.getElementById('total-remaining-value').textContent = totalRemainingValue.toFixed(2);
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

            // Infinite scroll functionality
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const windowHeight = window.innerHeight;
                const documentHeight = document.documentElement.scrollHeight;

                // Load more when user is within 200px of bottom
                if (documentHeight - (scrollTop + windowHeight) < 200) {
                    loadMoreData();
                }
            });

            // Event delegation for dynamically added buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.purchase-history-btn')) {
                    const button = e.target.closest('.purchase-history-btn');
                    const productId = button.getAttribute('data-product-id');
                    const productName = button.getAttribute('data-product-name');

                    document.getElementById('purchase-product-name').textContent = productName;

                    fetch(`/inventory-calculation/${productId}/purchase-history`)
                        .then(response => response.json())
                        .then(data => {
                            const purchases = Object.values(data.purchases);
                            let tbody = '';
                            purchases.forEach(purchase => {
                                tbody += `
                                    <tr>
                                        <td>${purchase.bill_date}</td>
                                        <td>${purchase.bill_number}</td>
                                        <td>${purchase.quantity}</td>
                                        <td>${purchase.rate}</td>
                                        <td>${purchase.amount}</td>
                                    </tr>
                                `;
                            });
                            document.getElementById('purchase-history-body').innerHTML = tbody;
                            document.getElementById('purchase-total-qty').textContent = data.total_qty;
                            document.getElementById('purchase-total-value').textContent = data.total_value;
                        });
                }

                if (e.target.closest('.assign-history-btn')) {
                    const button = e.target.closest('.assign-history-btn');
                    const productId = button.getAttribute('data-product-id');
                    const productName = button.getAttribute('data-product-name');

                    document.getElementById('assign-product-name').textContent = productName;

                    fetch(`/inventory-calculation/${productId}/assign-history`)
                        .then(response => response.json())
                        .then(data => {
                            const assigns = Object.values(data.assigns);
                            let tbody = '';
                            assigns.forEach(assign => {
                                tbody += `
                                    <tr>
                                        <td>${assign.date}</td>
                                        <td>${assign.department}</td>
                                        <td>${assign.employee}</td>
                                        <td>${assign.quantity}</td>
                                        <td>${assign.rate}</td>
                                        <td>${assign.amount}</td>
                                    </tr>
                                `;
                            });
                            document.getElementById('assign-history-body').innerHTML = tbody;
                            document.getElementById('assign-total-qty').textContent = data.total_qty;
                            document.getElementById('assign-total-value').textContent = data.total_value;
                        });
                }
            });
        });

        // Print function
        function printInventory() {
            const search = document.getElementById('product-search').value;
            const url = `{{ route('inventory-calculation.print') }}?search=${encodeURIComponent(search)}`;
            window.open(url, '_blank');
        }

        // Export to Excel function
        function exportInventoryToExcel() {
            const search = document.getElementById('product-search').value;
            const url = `{{ route('inventory-calculation.export.excel') }}?search=${encodeURIComponent(search)}`;
            window.location.href = url;
        }
    </script>
@endsection
