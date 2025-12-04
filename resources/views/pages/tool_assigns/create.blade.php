@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Tool Assign - Jewelry ERP</title>
@endsection
<style>
#history-content {
      position: fixed;
        top: 120px;
        right: 22px;
        z-index: 10;
    }
</style>
@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add Tool Assign</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">
                @if ($errors->any())
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger dark:border-danger/30">
                        <div class="font-medium">There were some problems with your input.</div>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('tool-assigns.store') }}" id="create-form">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">

                        <!-- Department -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Department</x-base.form-label>
                            <x-base.form-select name="d_id" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('d_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name ?? 'Unnamed Department' }}
                                    </option>
                                @endforeach
                            </x-base.form-select>
                        </div>

                        <!-- Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Date</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="date"
                                value="{{ old('date') }}"
								required
                            />
                        </div>

                    </div>

                    <!-- Dynamic Rows Section -->
                    <div class="mt-5">
                        <h3 class="text-md font-medium mb-3">Tool Assignments</h3>
                        <div id="rows-container">
                            <div class="grid grid-cols-12 gap-4 mb-4 row-item">
                                <!-- Product (Item) -->
                                <div class="col-span-4">
                                    <x-base.form-label>Product (Item)</x-base.form-label>
                                    <select
                                        class="product-select tom-select w-full"
                                        name="product_id[]"
                                        data-placeholder="Search & select product..."
                                    >
                                        <option value="">Select product...</option>
                                    </select>
                                </div>

                                <!-- Quantity -->
                                <div class="col-span-3">
                                    <x-base.form-label>Quantity</x-base.form-label>
                                    <x-base.form-input
                                        type="number"
                                        name="add_quantity[]"
                                        placeholder="Enter quantity"
										step="1" min="0"     
                                    />
                                </div>

                                <!-- Employee -->
                                <div class="col-span-4">
                                    <x-base.form-label>Employee</x-base.form-label>
                                    <select
                                        class="employee-select tom-select w-full"
                                        name="emp_id[]"
                                        data-placeholder="Search & select employee..."
                                    >
                                        <option value="">Select employee...</option>
                                    </select>
                                </div>

                                <!-- Remove Button -->
                                <div class="col-span-1 flex mt-4">
                                     <a class="flex items-center text-danger remove-row-btn" style="cursor: pointer;">
                                        <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                    </a>
                                </div>
                            </div>
                        </div>

                         <x-base.button type="button" id="add-row-btn" variant="primary">
                            Add Row
                        </x-base.button>
                    </div>
                    <div id="row-template" class="hidden">
                        <div class="grid grid-cols-12 gap-4 mb-4 row-item">
                            <div class="col-span-4">
                                <x-base.form-label>Product (Item)</x-base.form-label>
                                <select class="product-select tom-select w-full" name="product_id[]">
                                    <option value="">Select product...</option>
                                </select>
                            </div>

                            <div class="col-span-3">
                                <x-base.form-label>Quantity</x-base.form-label>
                                <x-base.form-input type="number" name="add_quantity[]" step="1" placeholder="Enter quantity"/>
                            </div>

                            <div class="col-span-4">
                                <x-base.form-label>Employee</x-base.form-label>
                                <select class="employee-select tom-select w-full" name="emp_id[]">
                                    <option value="">Select employee...</option>
                                </select>
                            </div>

                            <div class="col-span-1 flex mt-4">
                                <a class="flex items-center text-danger remove-row-btn" style="cursor: pointer;">
                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center">
                        <a href="{{ route('tool-assigns.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="success">Save Tool Assign</x-base.button>
                    </div>
                </form>
            </div>
        </div>
         
    </div>
	<div style="position: fixed; top: 120px; right: 22px; width: 26%; z-index: 1000;">
        <div class="intro-y box p-5">
            <h3 class="text-lg font-medium mb-4">Assignment History</h3>
            <div id="history-content">
                <p class="text-gray-500">Select both Product and Employee to view history.</p>
            </div>
        </div>
    </div>
    @push('vendors')
        @vite('resources/js/vendor/tom-select/index.js')
    @endpush

    @push('scripts')
        <style>
            .row-item {
                cursor: pointer;
                transition: background-color 0.2s;
            }
            .row-item:hover {
                background-color: #f3f4f6;
            }
            .row-item.selected {
                background-color: #dbeafe;
                border: 2px solid #3b82f6;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let itemCount = 0;

                // Function to initialize Tom Select for a product select
                function initializeProductSelect(productSelect) {
                    if (productSelect && !productSelect.isInitialized) {
                        productSelect.isInitialized = true;

                        // Initialize Tom Select with remote search
                        setTimeout(() => {
                            if (!productSelect.tomselect) {
                                const tomSelectInstance = new TomSelect(productSelect, {
                                    plugins: ['dropdown_input'],
                                    create: false,
                                    placeholder: 'Search & select product...',
                                    valueField: 'value',
                                    labelField: 'text',
                                    searchField: 'text',
                                    maxOptions: null,
                                    loadThrottle: 300,
                                    preload: 'focus',
                                    load: function(query, callback) {
                                        console.log('Loading products for query:', query);

                                        // Load products on keypress or when dropdown opens (empty query shows initial list)
                                        const searchQuery = query || '';
                                        const url = '/api/products/search?q=' + encodeURIComponent(searchQuery);
                                        console.log('Fetching from URL:', url);

                                        fetch(url)
                                            .then(response => {
                                                console.log('Response status:', response.status);
                                                if (!response.ok) {
                                                    throw new Error('Network response was not ok');
                                                }
                                                return response.json();
                                            })
                                            .then(data => {
                                                console.log('Received data:', data);
                                                if (data.success && data.data && Array.isArray(data.data)) {
                                                    const options = data.data.map(product => ({
                                                        value: product.id,
                                                        text: `${product.product_name}${product.barcode_number ? ' - ' + product.barcode_number : ''}${product.tool_code ? ' (' + product.tool_code + ')' : ''} (Remaining: ${product.remaining_quantity})`,
                                                        remaining_quantity: product.remaining_quantity,
                                                    }));
                                                    console.log('Mapped options:', options);
                                                    callback(options);
                                                } else {
                                                    console.warn('No data or not successful');
                                                    callback();
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Fetch error:', error);
                                                callback();
                                            });
                                    }
                                });

                                // Load products when dropdown opens
                                tomSelectInstance.on('dropdown_open', function() {
                                    // Load initial products if not already loaded
                                    if (!tomSelectInstance.loadedSearches || !tomSelectInstance.loadedSearches['']) {
                                        tomSelectInstance.load('');
                                    }
                                });

                                // Add change event to check for fetchHistory
                                tomSelectInstance.on('change', function() {
                                    checkAndFetchHistory(productSelect);
                                });
                            }
                        }, 100);
                    }
                }

                // Function to initialize Tom Select for an employee select
                function initializeEmployeeSelect(employeeSelect) {
                    if (employeeSelect && !employeeSelect.isInitialized) {
                        employeeSelect.isInitialized = true;

                        // Initialize Tom Select with remote search
                        setTimeout(() => {
                            if (!employeeSelect.tomselect) {
                                const tomSelectInstance = new TomSelect(employeeSelect, {
                                    plugins: ['dropdown_input'],
                                    create: false,
                                    placeholder: 'Search & select employee...',
                                    valueField: 'value',
                                    labelField: 'text',
                                    searchField: 'text',
                                    maxOptions: null,
                                    loadThrottle: 300,
                                    preload: 'focus',
                                    load: function(query, callback) {
                                        console.log('Loading employees for query:', query);

                                        // Load employees on keypress or when dropdown opens (empty query shows initial list)
                                        const searchQuery = query || '';
                                        const url = '/api/employees/search?q=' + encodeURIComponent(searchQuery);
                                        console.log('Fetching from URL:', url);

                                        fetch(url)
                                            .then(response => {
                                                console.log('Response status:', response.status);
                                                if (!response.ok) {
                                                    throw new Error('Network response was not ok');
                                                }
                                                return response.json();
                                            })
                                            .then(data => {
                                                console.log('Received data:', data);
                                                if (data.success && data.data && Array.isArray(data.data)) {
                                                    const options = data.data.map(employee => ({
                                                        value: employee.id,
                                                        text: `${employee.name}${employee.code ? ' (' + employee.code + ')' : ''}${employee.barcode ? ' - ' + employee.barcode : ''}`,
                                                    }));
                                                    console.log('Mapped options:', options);
                                                    callback(options);
                                                } else {
                                                    console.warn('No data or not successful');
                                                    callback();
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Fetch error:', error);
                                                callback();
                                            });
                                    }
                                });

                                // Load employees when dropdown opens
                                tomSelectInstance.on('dropdown_open', function() {
                                    // Load initial employees if not already loaded
                                    if (!tomSelectInstance.loadedSearches || !tomSelectInstance.loadedSearches['']) {
                                        tomSelectInstance.load('');
                                    }
                                });

                                // Add change event to check for fetchHistory
                                tomSelectInstance.on('change', function() {
                                    checkAndFetchHistory(employeeSelect);
                                });
                            }
                        }, 100);
                    }
                }

                // Function to check and fetch history when both product and employee are selected
                function checkAndFetchHistory(changedSelect) {
                    const row = changedSelect.closest('.row-item');
                    const productSelect = row.querySelector('.product-select');
                    const employeeSelect = row.querySelector('.employee-select');

                    const productId = productSelect ? productSelect.value : '';
                    const employeeId = employeeSelect ? employeeSelect.value : '';

                    if (productId && employeeId) {
                        fetchHistory(productId, employeeId);
                    } else {
                        document.getElementById('history-content').innerHTML = '<p class="text-gray-500">Select both Product and Employee to view history.</p>';
                    }
                }

                // Initialize Tom Select for the initial product select
                const initialProductSelect = document.querySelector('.product-select');
                if (initialProductSelect) {
                    initializeProductSelect(initialProductSelect);
                }

                // Initialize Tom Select for the initial employee select
                const initialEmployeeSelect = document.querySelector('.employee-select');
                if (initialEmployeeSelect) {
                    initializeEmployeeSelect(initialEmployeeSelect);
                }

                // Add row button event listener
               document.getElementById('add-row-btn').addEventListener('click', function() {
                    const container = document.getElementById('rows-container');
                    const template = document.getElementById('row-template').innerHTML;

                    // Insert brand-new row
                    const div = document.createElement('div');
                    div.innerHTML = template.trim();

                    const newRow = div.firstChild;
                    container.appendChild(newRow);

                    // Initialize TomSelect
                    initializeProductSelect(newRow.querySelector('.product-select'));
                    initializeEmployeeSelect(newRow.querySelector('.employee-select'));

                    // Add click event to new row
                    newRow.addEventListener('click', function() {
                        selectRow(this);
                    });
                });

                // Remove row button event listener
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-row-btn')) {
                        const container = document.getElementById('rows-container');
                        const rows = container.querySelectorAll('.row-item');
                        if (rows.length > 1) {
                            const rowToRemove = e.target.closest('.row-item');
                            // Destroy Tom Select instances before removing
                            const selects = rowToRemove.querySelectorAll('select');
                            selects.forEach(select => {
                                if (select.tomselect) {
                                    select.tomselect.destroy();
                                }
                            });
                            rowToRemove.remove();
                        } else {
                            alert('At least one row must remain.');
                        }
                    }
                });

                // Add validation for quantity input
                document.addEventListener('change', function(e) {
                    if (e.target.matches('input[name="add_quantity[]"]')) {
                        const quantityInput = e.target;
                        const row = quantityInput.closest('.row-item');
                        const productSelect = row.querySelector('.product-select');

                        if (productSelect && productSelect.tomselect) {
                            const selectedOption = productSelect.tomselect.getOption(productSelect.value);
                            if (selectedOption && selectedOption.dataset) {
                                const remainingQty = parseFloat(selectedOption.dataset.remainingQuantity || 0);
                                const enteredQty = parseFloat(quantityInput.value || 0);

                                /*if (enteredQty > remainingQty) {
                                    alert(`Cannot assign more than remaining quantity (${remainingQty}).`);
                                    quantityInput.value = '';
                                    quantityInput.focus();
                                }*/
                            }
                        }
                    }
                });

                // Function to fetch and display history
                function fetchHistory(productId, employeeId) {
                    const historyContent = document.getElementById('history-content');
                    historyContent.innerHTML = '<p class="text-gray-500">Loading history...</p>';

                    fetch(`/inventory-calculation/${productId}/assign-history?employee_id=${employeeId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.assigns && data.assigns.length > 0) {
                                let html = `
                                    <div class="p-4 bg-white rounded-xl shadow-md border border-slate-200">

                                        <table class="min-w-full border-collapse">
                                            <thead class="bg-slate-100 text-slate-700">
                                                <tr>
                                                    <th class="py-2 px-3 text-left font-semibold border-b">Date</th>
                                                    <th class="py-2 px-3 text-left font-semibold border-b">Department</th>
                                                    <th class="py-2 px-3 text-left font-semibold border-b">Quantity</th>
                                                    <th class="py-2 px-3 text-left font-semibold border-b">Rate</th>
                                                    <th class="py-2 px-3 text-left font-semibold border-b">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    `;

                                    data.assigns.forEach(assign => {
                                        html += `
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-2 px-3 border-b">${assign.date}</td>
                                                <td class="py-2 px-3 border-b">${assign.department}</td>
                                                <td class="py-2 px-3 border-b text-right">${assign.quantity}</td>
                                                <td class="py-2 px-3 border-b text-right">${assign.rate}</td>
                                                <td class="py-2 px-3 border-b text-right">${assign.amount}</td>
                                            </tr>
                                        `;
                                    });

                                    html += `
                                            </tbody>
                                        </table>

                                        <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-700">
                                            <div class="flex justify-between">
                                                <span class="font-semibold">Total Quantity:</span>
                                                <span>${data.total_qty}</span>
                                            </div>
                                            <div class="flex justify-between mt-1">
                                                <span class="font-semibold">Total Value:</span>
                                                <span>${data.total_value}</span>
                                            </div>
                                        </div>

                                    </div>`;

                                historyContent.innerHTML = html;
                            } else {
                                historyContent.innerHTML = '<p class="text-gray-500">No assignment history found for this product and employee.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching history:', error);
                            historyContent.innerHTML = '<p class="text-red-500">Error loading history.</p>';
                        });
                }

                // Function to select a row and fetch history
                function selectRow(row) {
                    // Remove selected class from all rows
                    document.querySelectorAll('.row-item').forEach(r => r.classList.remove('selected'));
                    // Add selected class to clicked row
                    row.classList.add('selected');

                    const productSelect = row.querySelector('.product-select');
                    const employeeSelect = row.querySelector('.employee-select');

                    const productId = productSelect ? productSelect.value : '';
                    const employeeId = employeeSelect ? employeeSelect.value : '';

                    if (productId && employeeId) {
                        fetchHistory(productId, employeeId);
                    } else {
                        document.getElementById('history-content').innerHTML = '<p class="text-gray-500">Select both Product and Employee to view history.</p>';
                    }
                }

                // Add click event to initial row
                const initialRow = document.querySelector('.row-item');
                if (initialRow) {
                    initialRow.addEventListener('click', function() {
                        selectRow(this);
                    });
                }
				
				// Form validation on submit
                document.getElementById('create-form').addEventListener('submit', function(e) {
                    const quantityInputs = document.querySelectorAll('input[name="add_quantity[]"]');
                    let isValid = true;
                    let errorMessage = '';

                    quantityInputs.forEach(input => {
                        const value = input.value.trim();
                        if (value !== '' && value.includes('.')) {
                            isValid = false;
                            errorMessage = 'Quantity must be a whole number (no decimal points allowed).';
                            input.focus();
                            return;
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert(errorMessage);
                    }
                });
            });
        </script>
    @endpush
@endsection
