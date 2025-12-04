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
            <div class="mx-auto hidden text-slate-500 md:block">
                @isset($products)
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                @endisset
            </div>
            <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto md:ml-0">
				<div class="relative w-56 text-slate-500">
					<x-base.form-input 
						class="!box w-56 pr-10" 
						type="text" 
						placeholder="Search..." 
						id="productSearch" 
						autocomplete="off"
					/>
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
                         {{-- <x-base.table.th class="whitespace-nowrap border-b-0"> Category </x-base.table.th>
                       <x-base.table.th class="whitespace-nowrap border-b-0"> Company </x-base.table.th> --}}
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
                <tbody  id="productTableBody">
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
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->tool_code ?? '-' }}</div>
                                </x-base.table.td>
                               {{-- <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->category->name ?? '-' }}</div>
                                </x-base.table.td>
                                 <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $product->product_company ?? '-' }}</div>
                                </x-base.table.td> --}}
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
                                        <a href="javascript:void(0);" 
                                            class="flex mr-3 btn btn-sm btn-outline-primary print-barcode-btn"
                                            data-barcode="{{ $product->barcode_number }}"
											data-code="{{ $product->code }}"
                                            data-product_name="{{ $product->product_name }}">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="Printer" /> Barcode
                                        </a>

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
                </tbody >
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
        <!-- BEGIN: Pagination -->
        @isset($products)
            <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
                <div id="paginationLinks">
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
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
	<script>
	// Global function for printing barcode
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
						max-height: 0.6in;
						display: block;
						margin: 0 auto 3px auto;
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
				<div class="barcode-number">${code}</div>
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

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.print-barcode-btn').forEach(button => {
			button.addEventListener('click', function () {
				const barcode = this.dataset.barcode;
				const name = this.dataset.product_name;

				// Set modal content
				   document.getElementById('modalProductName').textContent = name;
				document.getElementById('modalBarcodeNumber').textContent = barcode;

				document.getElementById("modalBarcode").innerHTML = '<svg id="modalBarcodeSvg"></svg>';

				JsBarcode("#modalBarcodeSvg", barcode, {
					format: "CODE128",
					displayValue: false,
					width: 1.5,
					height: 40,
					margin: 0,
					marginTop: 0,
					marginBottom: 0,
					marginLeft: 0,
					marginRight: 0
				});
				// Open modal (Midone/Tailwind syntax)
				const modal = tailwind.Modal.getInstance(document.querySelector("#barcodeModal"));
				modal.show();
				setTimeout(() => {
					printBarcodeSection();
				}, 800);
			});
		});


			function printModal() {
				const modal = document.getElementById('barcodeModal');

				// Ensure modal is open before printing
				modal.classList.add('print-open');

				window.print();

				// Remove after printing
				setTimeout(() => modal.classList.remove('print-open'), 500);
			}
	});
</script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const searchInput = document.getElementById('productSearch');
		const tableBody = document.getElementById('productTableBody');
		const paginationDiv = document.getElementById('paginationLinks');
		const sortSelect = document.getElementById('sortSelect');
		const perPageSelect = document.getElementById('perPageSelect');
		let currentQuery = '';
		let currentSort = 'latest';
		let currentPerPage = 10;
		
		if (sortSelect) {
			sortSelect.addEventListener('change', function () {
				currentSort = this.value;
				performSearch(currentQuery);
			});
		}

		if (perPageSelect) {
			perPageSelect.addEventListener('change', function () {
				currentPerPage = this.value;
				performSearch(currentQuery);
			});
		}
		let timer = null;

		searchInput.addEventListener('input', function () {
			clearTimeout(timer);
			timer = setTimeout(() => {
				performSearch(searchInput.value.trim());
			}, 300); // delay to prevent over-fetching
		});

		function performSearch(query) {
		currentQuery = query;
		const tableBody = document.getElementById('productTableBody');
		const paginationDiv = document.getElementById('pagination');
		const url = new URL(`{{ route('products.search') }}`);
		url.searchParams.append('q', query);
		url.searchParams.append('sort', currentSort);
		url.searchParams.append('per_page', currentPerPage);
		fetch(url)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					tableBody.innerHTML = '';

					if (data.data.length === 0) {
						tableBody.innerHTML = `
							<tr>
								<td colspan="7" class="text-center text-slate-500 py-3 dark:bg-darkmode-600">
									No matching products found.
								</td>
							</tr>`;
						if (paginationDiv) paginationDiv.innerHTML = '';
						return;
					}

					data.data.forEach(product => {
						const tdClass = `px-5 py-3 dark:border-darkmode-300 border-b-0 
							bg-white shadow-[20px_3px_20px_#0000000b] 
							first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600`;

						const row = `
							<tr class="intro-x">
								<td class="${tdClass}">
									<div class="flex">
										<div class="whitespace-nowrap font-medium">
											${product.product_name}
										</div>
									</div>
								</td>
								<td class="${tdClass}">
									<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
										${product.barcode_number ?? '-'}
									</div>
								</td>
								<td class="${tdClass} text-center">
									<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
										${product.minimum_rate ? parseFloat(product.minimum_rate).toFixed(2) : '0'}
									</div>
								</td>
								<td class="${tdClass} text-center">
									<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
										${product.minimum_quantity ?? '0'}
									</div>
								</td>
								<td class="px-5 dark:border-darkmode-300 relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
									<div class="flex items-center justify-center">
										<a href="javascript:void(0);"
											class="flex mr-3 btn btn-sm btn-outline-primary print-barcode-btn"
											data-barcode="${product.barcode_number ?? ''}"
											data-code="${product.code ?? ''}"
											data-product_name="${product.product_name ?? ''}">
											<i data-lucide="printer" class="mr-1 h-4 w-4"></i> Barcode
										</a>

										<a class="mr-3 flex items-center" href="/products/${product.id}">
											<i data-lucide="eye" class="mr-1 h-4 w-4"></i> View
										</a>

										<a class="mr-3 flex items-center" href="/products/${product.id}/edit">
											<i data-lucide="check-square" class="mr-1 h-4 w-4"></i> Edit
										</a>

										<a class="flex items-center text-danger" data-tw-toggle="modal"
											data-tw-target="#delete-confirmation-modal" href="#"
											data-delete-route="/products/${product.id}"
											data-delete-name="${product.product_name ?? ''}">
											<i data-lucide="trash" class="mr-1 h-4 w-4"></i> Delete
										</a>
									</div>
								</td>
							</tr>
						`;

						tableBody.insertAdjacentHTML('beforeend', row);
					});

					if (paginationDiv) paginationDiv.innerHTML = '';
				   
					if (window.lucide) {
						lucide.createIcons();
					}
					bindBarcodeButtons();
					bindDeleteButtons();
					setTimeout(() => {
						if (window.lucide && typeof window.lucide.createIcons === 'function') {
							window.lucide.createIcons();
						} else if (typeof Lucide !== 'undefined' && typeof Lucide.createIcons === 'function') {
							Lucide.createIcons();
						} else {
							// fallback: re-execute all <i data-lucide=""> manually
							document.querySelectorAll('[data-lucide]').forEach(icon => {
								icon.innerHTML = ''; // clear
								const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
								svg.setAttribute('width', '1em');
								svg.setAttribute('height', '1em');
								svg.setAttribute('stroke', 'currentColor');
								icon.appendChild(svg);
							});
						}
					}, 10);
				}
			})
			.catch(err => console.error('Search Error:', err));
		}
		function bindBarcodeButtons() {
			document.querySelectorAll('.print-barcode-btn').forEach(button => { 
				button.onclick = null;
				
				button.addEventListener('click', function () {
					const barcode = this.dataset.barcode;
					const name = this.dataset.product_name;
 
					document.getElementById('modalProductName').textContent = name;
					document.getElementById('modalBarcodeNumber').textContent = barcode;
 
					document.getElementById("modalBarcode").innerHTML = '<svg id="modalBarcodeSvg"></svg>';

					JsBarcode("#modalBarcodeSvg", barcode, {
						format: "CODE128",
						displayValue: false,
						width: 5,
						height: 40,
						margin: 0,
						marginTop: 0,
						marginBottom: 0,
						marginLeft: 0,
						marginRight: 0
					});
 
					const modal = tailwind.Modal.getInstance(document.querySelector("#barcodeModal"));
					modal.show();
 
					setTimeout(() => {
						printBarcodeSection();
					}, 800);
				});
			});
		}

		function bindDeleteButtons() {
			const deleteButtons = document.querySelectorAll('.delete-btn');
			const deleteForm = document.getElementById('delete-product-form');
			const deleteProductName = document.getElementById('delete-product-name');

			deleteButtons.forEach(button => {
				button.onclick = function () {
					const route = this.getAttribute('data-delete-route');
					const name = this.getAttribute('data-delete-name');
					if (deleteForm && route) deleteForm.setAttribute('action', route);
					if (deleteProductName) deleteProductName.textContent = name || 'this product';
				};
			});
		}
		
	});
	window.printBarcodeNow = function () {

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

// Print product table function
function printProductTable() {
    const printWindow = window.open('', '', 'width=800,height=600');
    const table = document.querySelector('table');
    
    // Build clean table HTML without actions column
    let tableHTML = '<table border="1" style="width:100%; border-collapse: collapse;">';
    
    // Headers (exclude last column - ACTIONS)
    tableHTML += '<thead><tr>';
    const headers = table.querySelectorAll('thead th');
    for(let i = 0; i < headers.length - 1; i++) {
        tableHTML += `<th style="padding: 8px; background-color: #f2f2f2;">${headers[i].textContent.trim()}</th>`;
    }
    tableHTML += '</tr></thead>';
    
    // Data rows (exclude last column - ACTIONS)
    tableHTML += '<tbody>';
    table.querySelectorAll('tbody tr').forEach(tr => {
        const cells = tr.querySelectorAll('td');
        if(cells.length > 0) {
            tableHTML += '<tr>';
            for(let i = 0; i < cells.length - 1; i++) {
                tableHTML += `<td style="padding: 8px; border: 1px solid #ddd;">${cells[i].textContent.trim()}</td>`;
            }
            tableHTML += '</tr>';
        }
    });
    tableHTML += '</tbody></table>';
    
    printWindow.document.write(`
        <html>
        <head>
            <title>Products List</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { text-align: center; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
            </style>
        </head>
        <body>
            <h1>Products List</h1>
            ${tableHTML}
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



	
	
</script>
<script src="https://unpkg.com/lucide@latest"></script>

<script> 
    window.printProducts = function() {
        const search = document.getElementById('productSearch').value;
        const url = `{{ route('products.print') }}?search=${encodeURIComponent(search)}`;
        window.open(url, '_blank');
    };

    window.exportToExcel = function() {
        const search = document.getElementById('productSearch').value;
        const url = `{{ route('products.export.excel') }}?search=${encodeURIComponent(search)}`;
        window.location.href = url;
    };
</script>

@endsection
