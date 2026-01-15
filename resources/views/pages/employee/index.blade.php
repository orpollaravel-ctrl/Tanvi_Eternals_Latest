@extends('../layouts/' . $layout)

@section('subhead')
    <title>Employee List - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Employee List</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Header Actions -->
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            @if(auth()->check() && auth()->user()->hasPermission('create-employees'))
                <a href="{{ route('employees.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New Employee
                    </x-base.button>
                </a>
            @endif
            <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto md:ml-0">
                <div class="relative w-56 text-slate-500">
                    <input type="text" id="employee-search" class="form-control !box w-56 pr-10"
                        placeholder="Search employees...">
                    <x-base.lucide class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4" icon="Search" />
                </div>
            </div>
        </div>
        <!-- END: Header Actions -->

        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="opening-stock-table -mt-2 border-separate border-spacing-y-[10px]"
                style="width: 100%; text-align:center;">
                <thead>
                    <tr>
                        <th style="white-space: nowrap; border-bottom: 0;">#</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Image</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Name</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Department</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Code</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Barcode</th>
                        <th style="white-space: nowrap; border-bottom: 0;">Active</th>
                        @if(auth()->check() && (auth()->user()->hasPermission('edit-employees') || auth()->user()->hasPermission('delete-employees')))
                            <th style="white-space: nowrap; border-bottom: 0; text-align: center;">Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody id="employee-table-body">
                   
                </tbody>
            </table>
        </div>
        <!-- END: Data Table -->

        <!-- Loading indicator -->
        <div id="loading-indicator" class="col-span-12 text-center py-4 hidden">
            <div class="inline-flex items-center">
                <x-base.loading-icon class="animate-spin h-5 w-5 mr-2" />
                Loading more employees...
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
                    Do you really want to delete this employee?<br />
                    This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">
                    Cancel
                </x-base.button>
                <x-base.button class="w-24" type="button" variant="danger">
                    Delete
                </x-base.button>
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: Delete Confirmation Modal -->

    <!-- BEGIN: Print Barcode Modal -->
    <x-base.dialog id="barcodeModal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-primary" icon="Printer" />
                <div class="mt-4 text-2xl font-semibold">Print Barcode</div>

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
				 <!--<x-base.button class="w-24" onclick="window.print()" type="button" variant="primary">
                    <x-base.lucide icon="Printer" class="mr-1 h-4 w-4" /> Print
                </x-base.button> -->
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
@endsection
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let isLoading = false;
        let hasMorePages = true;
        let searchTimeout;

        const tableBody = document.getElementById('employee-table-body');
        const loadingIndicator = document.getElementById('loading-indicator');
        const searchInput = document.getElementById('employee-search');

        // Debounced search function
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                hasMorePages = true;
                loadEmployees(true); // Reset and load
            }, 500);
        });
        loadEmployees();
        // Infinite scroll
        window.addEventListener('scroll', function() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
                if (!isLoading && hasMorePages) {
                    loadEmployees();
                }
            }
        });

        function loadEmployees(reset = false) {
            if (isLoading) return;

            if (!tableBody) {
                console.error('Employee table body not found');
                return;
            }

            isLoading = true;
            loadingIndicator.classList.remove('hidden');

            const search = searchInput.value;

            fetch(`{{ route('employees.index') }}?page=${currentPage}&search=${encodeURIComponent(search)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (reset) {
                        tableBody.innerHTML = '';
                    }

                    if (data.data.length > 0) {
                        data.data.forEach((employee, index) => {
                            const rowNumber = (currentPage - 1) * 25 + index + 1;
                            const rowHtml = generateEmployeeRow(employee, rowNumber);
                            tableBody.insertAdjacentHTML('beforeend', rowHtml);
                        });

                        currentPage++;
                        hasMorePages = data.has_more;
                    } else if (reset) {
                        tableBody.innerHTML =
                            '<tr><td colspan="7" class="text-center text-slate-500 py-4">No employees found.</td></tr>';
                    }

                    // Re-bind status toggle events for new rows
                    bindStatusToggles();
                    bindBarcodeButtons();
                })
                .catch(error => {
                    console.error('Error loading employees:', error);
                })
                .finally(() => {
                    isLoading = false;
                    loadingIndicator.classList.add('hidden');
                });
        }

        function generateEmployeeRow(employee, rowNumber) {
            const imageSrc = employee.images ? `{{ asset('storage/') }}/${employee.images}` :
                'https://tanvierp.orpol.in/build/assets/logo-9a88cec5.svg';
            const activeChecked = employee.active ? 'checked' : '';
            const switchBg = employee.active ? 'background-color: #2196F3;' : '';
            const circleTransform = employee.active ? 'transform: translateX(26px);' : '';
            const textColor = employee.active ? '#2196F3' : '#666';
            const textContent = employee.active ? 'Active' : 'Inactive';

            return `
            <tr class="intro-x">
                <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md">
                    ${rowNumber}
                </td>
                <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                    <img src="${imageSrc}" width="50" height="20" style="border-radius:6px;object-fit:cover;">
                </td>
                <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] font-medium">
                    ${employee.name}
                </td>
                <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow">
                    ${employee.department  ?? '-'}
                </td>
                <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                    ${employee.code || '-'}
                </td>
                <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                    ${employee.barcode || '-'}
                </td>
                <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label style="position: relative; display: inline-block; width: 50px; height: 24px; margin: 0;">
                            <input class="employee-status-toggle" type="checkbox" id="status-${employee.id}" data-employee-id="${employee.id}" style="opacity: 0; width: 0; height: 0;" ${activeChecked}>
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; ${switchBg}"></span>
                            <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; ${circleTransform}"></span>
                        </label>
                        <span style="font-size: 14px; color: ${textColor};">${textContent}</span>
                    </div>
                </td>
                <td class="relative border-b-0 bg-white py-0 dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] text-center first:rounded-l-md last:rounded-r-md before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                    <div class="flex items-center justify-center">
                        <button type="button" class="flex mr-3 btn btn-sm btn-outline-primary print-barcode-btn" data-tw-toggle="modal" data-tw-target="#barcodeModal" data-barcode="${employee.barcode}" data-product_name="${employee.name}">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg> Barcode
                        </button>
                        <a href="/employees/${employee.id}" class="flex items-center mr-3 text-primary">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </a> 
                        ${@json(auth()->check() && auth()->user()->hasPermission('edit-employees')) ? `
                            <a href="/employees/${employee.id}/edit" class="flex items-center mr-3 text-success">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>` : ''}
                        ${@json(auth()->check() && auth()->user()->hasPermission('delete-employees')) ? `
                            <form action="/employees/${employee.id}" method="POST" class="inline mt-3">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="flex items-center text-danger"
                                    onclick="return confirm('Are you sure you want to delete this employee?')">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg> Delete
                                </button>
                            </form>` : ''}

                    </div>
                </td>
            </tr>
            `;
        }

        function bindStatusToggles() {
            document.querySelectorAll('.employee-status-toggle').forEach(function(toggle) {
                if (!toggle.hasAttribute('data-bound')) {
                    toggle.setAttribute('data-bound', 'true');
                    toggle.addEventListener('change', function() {
                        var employeeId = this.getAttribute('data-employee-id');
                        var isChecked = this.checked;
                        var switchElement = this.nextElementSibling;
                        var circleElement = switchElement.nextElementSibling;
                        var textElement = this.parentElement.nextElementSibling;

                        if (isChecked) {
                            switchElement.style.backgroundColor = '#2196F3';
                            circleElement.style.transform = 'translateX(26px)';
                            textElement.style.color = '#2196F3';
                            textElement.textContent = 'Active';
                        } else {
                            switchElement.style.backgroundColor = '#ccc';
                            circleElement.style.transform = 'translateX(0)';
                            textElement.style.color = '#666';
                            textElement.textContent = 'Inactive';
                        }

                        fetch('{{ route('employees.toggle-active', ':id') }}'.replace(':id',
                                employeeId), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: new URLSearchParams({
                                    _token: '{{ csrf_token() }}',
                                    _method: 'POST'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Status updated successfully');
                                } else {
                                    this.checked = !isChecked;
                                    if (!isChecked) {
                                        switchElement.style.backgroundColor = '#2196F3';
                                        circleElement.style.transform = 'translateX(26px)';
                                        textElement.style.color = '#2196F3';
                                        textElement.textContent = 'Active';
                                    } else {
                                        switchElement.style.backgroundColor = '#ccc';
                                        circleElement.style.transform = 'translateX(0)';
                                        textElement.style.color = '#666';
                                        textElement.textContent = 'Inactive';
                                    }
                                    alert('Failed to update status');
                                }
                            })
                            .catch(error => {
                                this.checked = !isChecked;
                                if (!isChecked) {
                                    switchElement.style.backgroundColor = '#2196F3';
                                    circleElement.style.transform = 'translateX(26px)';
                                    textElement.style.color = '#2196F3';
                                    textElement.textContent = 'Active';
                                } else {
                                    switchElement.style.backgroundColor = '#ccc';
                                    circleElement.style.transform = 'translateX(0)';
                                    textElement.style.color = '#666';
                                    textElement.textContent = 'Inactive';
                                }
                                alert('An error occurred while updating status');
                                console.error('Error:', error);
                            });
                    });
                }
            });
        }

        bindStatusToggles();

        bindBarcodeButtons();

        function bindBarcodeButtons() {
            document.querySelectorAll('.print-barcode-btn').forEach(button => {
                    button.setAttribute('data-bound', 'true');
                    button.addEventListener('click', function() {
                        const productName = this.dataset.product_name;
                        const barcode = this.dataset.barcode;

                        document.getElementById('modalProductName').textContent = productName;
                        document.getElementById('modalBarcodeNumber').textContent = barcode;
						 document.getElementById("modalBarcode").innerHTML = '<svg id="modalBarcodeSvg"></svg>';

                        JsBarcode("#modalBarcodeSvg", barcode, {
                            format: "CODE128",
                            width: 4,  
                            height: 80, 
                            displayValue: true
                        });

                        const modal = tailwind.Modal.getInstance(document.querySelector("#barcodeModal"));
                        modal.show();
                        setTimeout(() => {
                            printBarcodeSection();
                        }, 500);
                    });
            });
        }

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
							padding: 4px 2px;
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

					</style>

				</head>
				<body>

					<div class="label">
						${barcodeSVG}
						
						<div class="info">
							<span> ${product}</span> </br>
							<!--<span>Code:- ${code}</span> -->
						</div> 
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
    });
</script>

