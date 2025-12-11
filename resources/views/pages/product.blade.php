@extends('../layouts/' . $layout)

@section('subhead')
    <title>Products - Jewelry ERP</title>
@endsection
<style>
    /* Screen view — hidden */
    #printArea {
        display: none;
    }
</style>

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Products</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-between gap-3 sm:flex-nowrap">
            <div class="flex items-center gap-2">
                @if (auth()->check() && auth()->user()->hasPermission('create-products'))
                    <a href="{{ route('products.create') }}">
                        <x-base.button class="shadow-md" variant="primary">
                            Add New Product
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
                            <a href="javascript:void(0);" onclick="printProductTable()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                            </a>
                        </x-base.menu.item>
                        <x-base.menu.item>
                            <a href="javascript:void(0);" onclick="exportToExcel()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to Excel
                            </a>
                        </x-base.menu.item>
                    </x-base.menu.items>
                </x-base.menu>
            </div>
            <div class="relative w-56 text-slate-500">
                <x-base.form-input class="!box w-56 pr-10" type="text" placeholder="Search..." id="productSearch"
                    autocomplete="off" />
                <x-base.lucide class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4" icon="Search" />
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
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Tool Code </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Barcode </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                            Min Rate
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                            Max Rate
                        </x-base.table.th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-products') || auth()->user()->hasPermission('delete-products')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                                ACTIONS
                            </x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>
                <tbody id="productTableBody">
                </tbody>
            </x-base.table>
        </div>
        <!-- END: Data List -->

        <!-- Print Area (Hidden) -->
        <div id="printArea" style="display: none;">
            <h2>Products List</h2>
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Barcode</th>
                        <th>Tool Code</th>
                        <th>Min Rate</th>
                        <th>Max Rate</th>
                    </tr>
                </thead>
                <tbody id="printTableBody">
                    <!-- Content will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- BEGIN: Print Barcode Modal -->
    <x-base.dialog id="barcodeModal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-primary" icon="Printer" />
                <div class="mt-4 text-2xl font-semibold">Print Barcode</div>

                <!-- Barcode Section -->
                <div class="mt-6 flex justify-center">
                    <div id="modalBarcode">
                        <svg id="modalBarcodeSvg"></svg>
                    </div>
                </div>

                <!-- Product Info Row -->
                <div class="mt-6 grid grid-cols-1 gap-4">
                    <div>
                        <div class="text-sm text-slate-500">Product Name</div>
                        <div id="modalProductName" class="font-semibold text-base mt-1"></div>
                    </div>
                    <div class="">
                        <div class="text-sm text-slate-500">Code Value</div>
                        <div id="modalBarcodeNumber" class="font-semibold text-base mt-1"></div>
                    </div>
                </div>
            </div>
            <!-- Footer Buttons -->
            <div class="px-5 pb-8 text-center">
                <x-base.button class="mr-2 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">
                    Close
                </x-base.button>
                <!-- <x-base.button class="w-24" variant="primary" onclick="printBarcodeNow()">
         <x-base.lucide icon="Printer" class="mr-1 h-4 w-4" /> Print
        </x-base.button> -->
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: Print Barcode Modal -->
    <div id="printArea">
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
            let offset = 0;
            let isLoading = false;
            let hasMore = true;
            let searchQuery = '';
            const tdClass =
                'border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600';
            const iconSvg = {
                printer: '<svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>',
                eye: '<svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>',
                edit: '<svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
                trash: '<svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>'
            };

            function renderRow(p) {
                return `<tr class="intro-x">
                    <td class="w-40 ${tdClass} px-5 py-3"><div class="whitespace-nowrap font-medium">${p.product_name}</div></td>
                    <td class="${tdClass} px-5 py-3"><div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${p.tool_code || '-'}</div></td>
                    <td class="${tdClass} px-5 py-3"><div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${p.barcode_number || '-'}</div></td>
                    <td class="${tdClass} px-5 py-3 text-center"><div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${p.minimum_rate ? parseFloat(p.minimum_rate).toFixed(2) : '-'}</div></td>
                    <td class="${tdClass} px-5 py-3 text-center"><div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${p.maximum_rate ? parseFloat(p.maximum_rate).toFixed(2) : '-'}</div></td>
                    ${@json(auth()->check() && (auth()->user()->hasPermission('edit-products') || auth()->user()->hasPermission('delete-products'))) ? `
                        <td class="relative w-56 ${tdClass} px-5 py-3 before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                            <div class="flex items-center justify-center">
                                <a href="javascript:void(0);" class="flex mr-3 btn btn-sm btn-outline-primary print-barcode-btn" data-barcode="${p.barcode_number || ''}" data-product_name="${p.product_name}">${iconSvg.printer} Barcode</a>
                                <a class="mr-3 flex items-center" href="/products/${p.id}">${iconSvg.eye} View</a>
                                ${@json(auth()->check() && auth()->user()->hasPermission('edit-products')) ? `
                                    <a class="mr-3 flex items-center" href="/products/${p.id}/edit">${iconSvg.edit} Edit</a>` : ''}
                                ${@json(auth()->check() && auth()->user()->hasPermission('delete-products')) ? `
                                    <a class="flex items-center text-danger" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal" href="#" data-delete-route="/products/${p.id}" data-delete-name="${p.product_name}">${iconSvg.trash} Delete</a>` : ''}
                            </div>
                        </td>` : ''}
                </tr>`;
            }

            function loadProducts() {
                if (isLoading || !hasMore) return;
                isLoading = true;

                fetch(`{{ route('products.index') }}?offset=${offset}&search=${encodeURIComponent(searchQuery)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const tbody = document.getElementById('productTableBody');
                        data.products.forEach(p => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = renderRow(p).replace(/<\/?tr[^>]*>/g, '');
                            tbody.appendChild(tr);
                        });
                        hasMore = data.hasMore;
                        offset += data.products.length;
                        isLoading = false;
                        attachEvents();
                    })
                    .catch(() => isLoading = false);
            }

            function attachEvents() {
                document.querySelectorAll('.print-barcode-btn:not([data-bound])').forEach(btn => {
                    btn.dataset.bound = '1';
                    btn.onclick = function() {
                        const barcode = this.dataset.barcode;
                        if (!barcode) return alert('No barcode available');
                        document.getElementById('modalProductName').textContent = this.dataset.product_name;
                        document.getElementById('modalBarcodeNumber').textContent = barcode;
                        document.getElementById('modalBarcode').innerHTML = '<svg id="modalBarcodeSvg"></svg>';
                        JsBarcode('#modalBarcodeSvg', barcode, {
                            format: 'CODE128',
                            width: 4,
                            height: 80,
                            displayValue: false
                        });
                        tailwind.Modal.getInstance(document.querySelector('#barcodeModal')).show();
                        setTimeout(() => printBarcodeSection(), 800);
                    };
                });
                document.querySelectorAll('[data-delete-route]:not([data-bound])').forEach(btn => {
                    btn.dataset.bound = '1';
                    btn.onclick = function() {
                        document.getElementById('delete-product-form').setAttribute('action', this.dataset
                            .deleteRoute);
                        document.getElementById('delete-product-name').textContent = this.dataset.deleteName;
                    };
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                loadProducts();

                const observer = new IntersectionObserver(entries => {
                    if (entries[0].isIntersecting) loadProducts();
                }, {
                    rootMargin: '200px'
                });
                const sentinel = document.createElement('div');
                document.querySelector('table').parentElement.appendChild(sentinel);
                observer.observe(sentinel);

                let searchTimer;
                document.getElementById('productSearch').addEventListener('input', function(e) {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        searchQuery = e.target.value;
                        offset = 0;
                        hasMore = true;
                        document.getElementById('productTableBody').innerHTML = '';
                        loadProducts();
                    }, 300);
                });
            });
        </script>
    @endpush
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        function printBarcodeSection() {
            const barcodeSVG = document.getElementById('modalBarcodeSvg').outerHTML;
            const product = document.getElementById('modalProductName').textContent;
            const code = document.getElementById('modalBarcodeNumber').textContent;

            const printWindow = window.open('', '', 'width=400,height=400');

            printWindow.document.write(`
			<html>
			<head>
				<title>Print Barcode</title>
				<style>
					@page {
						size: 2.50in 1.13in;
						margin: 0;
					}
					* {
						-webkit-print-color-adjust: exact !important;
						print-color-adjust: exact !important;
					}
					html, body {
						margin: 0;
						padding: 0;
						width: 2.50in;
						height: 1.13in;
					}
					.label {
						width: 2.50in;
						height: 1.13in;
						overflow: visible;
						padding: 6px 8px;
						text-align: center;
						box-sizing: border-box;
						font-family: Arial, sans-serif;
						display: flex;
						flex-direction: column;
						justify-content: center;
						align-items: center;
					}
					svg {
						max-width: 100%;
						height: auto !important;
						max-height: 1.05in;
						display: block;
						margin: 0 auto;
					}
					.barcode-number {
						font-size: 11px;
						font-weight: bold;
						margin: 2px 0;
						color: #000;
						line-height: 1;
					}
					.product-name {
						font-size: 10px;
						margin: 2px 0 0 0;
						color: #000;
						line-height: 1.2;
					}
				</style>
			</head>
			<body>
				<div class="label">
				${barcodeSVG}
				<div class="product-name">${product}</div>
				</div>
			</body>
			</html>
		`);

            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }

        window.printBarcodeNow = function() {

            // Make sure print area exists
            const printArea = document.getElementById("printArea");
            if (!printArea) {
                console.error("printArea missing in DOM");
                return;
            }

            // Build the print content cleanly
            const barcodeSVG = document.getElementById("modalBarcodeSvg")?.outerHTML || "";
            const productName = document.getElementById("modalProductName")?.textContent || "";
            const codeValue = document.getElementById("modalBarcodeNumber")?.textContent || "";

            printArea.innerHTML = `
		<style>
			@page {
				size: 2.50in 1.13in;
				margin: 0;
			}
			* {
				-webkit-print-color-adjust: exact !important;
				print-color-adjust: exact !important;
			}
			html, body {
				margin: 0 !important;
				padding: 0 !important;
				width: 2.50in;
				height: 1.13in;
			}
			svg {
				max-width: 100%;
				height: auto !important;
				max-height: 2.05in;
				display: block;
				margin: 0 auto;
			}
		</style>
        <div style="
            width: 2.50in;
            height: 1.13in;
            padding: 4px 2px;
			overflow: visible;
			box-sizing: border-box;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        ">
            <div style="width: 100%; text-align: center;">
                ${barcodeSVG}
            </div>

            <!-- <div style="
                display:flex;
                justify-content:space-between;
                font-size:7px;
                margin-top:2px;
                width: 100%;
                padding: 0 2px;
                line-height: 1;
            ">
                <span style="flex:1; text-align:center;">Name: ${productName}</span>
                <span style="flex:1; text-align:center;">Code: ${codeValue}</span>
            </div> -->
        </div>

        
    `;

            window.print();
        };

        function printProductTable() {
            const search = document.getElementById('productSearch').value;
            const url = `{{ route('products.print') }}?search=${encodeURIComponent(search)}`;
            window.open(url, '_blank');
        }
    </script>
    <script>
        window.exportToExcel = function() {
            const search = document.getElementById('productSearch').value;
            const url = `{{ route('products.export.excel') }}?search=${encodeURIComponent(search)}`;
            window.location.href = url;
        };
    </script>
@endsection
