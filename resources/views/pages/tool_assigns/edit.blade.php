@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Tool Assign - Jewelry ERP</title>
@endsection
<style>
    .quantity-field {
        min-height: 38px !important;
        height: 38px !important;
    }
    [data-tw-merge]{
        visibility: unset !important;
    }
	#history-content {
      position: fixed;
        top: 120px;
        right: 22px;
        z-index: 10;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Tool Assign
        </h2>
    </div>

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

			<form action="{{ route('tool-assigns.update', $toolAssign->id) }}" method="POST" id="edit-form">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-6">

                <!-- Department -->
                <div class="col-span-12 sm:col-span-6">
                    <x-base.form-label>Department</x-base.form-label>
                    <x-base.form-select name="d_id" required>
                        <option value="">Select Department</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('d_id', $toolAssign->d_id) == $dept->id ? 'selected' : '' }}>
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
                        value="{{ old('date', $toolAssign->date ? \Carbon\Carbon::parse($toolAssign->date)->format('Y-m-d') : '') }}"
						required
                    />
                </div>

            </div>

            <!-- Dynamic Rows Section -->
            <div class="mt-5">
                <h3 class="text-md font-medium mb-3">Tool Assignments</h3>
                <div id="rows-container">
                    @php
                        $oldProductIds = old('product_id', []);
                        $oldQuantities = old('add_quantity', []);
                        $oldEmpIds = old('emp_id', []);
                        $hasOldData = !empty($oldProductIds) || !empty($oldQuantities) || !empty($oldEmpIds);
                        
                        if ($hasOldData) {
                            $rowCount = max(count($oldProductIds), count($oldQuantities), count($oldEmpIds));
                        } else {
                            $rowCount = $toolAssign->items->count();
                        }
                    @endphp
                    
                    @if($hasOldData)
                        @for ($i = 0; $i < $rowCount; $i++)
                            <div class="grid grid-cols-12 gap-4 mb-4 row-item">
                                <!-- Product (Item) -->
                                <div class="col-span-3">
                                    <x-base.form-label>Product (Item)</x-base.form-label>
                                    <select
                                        class="product-select tom-select w-full"
                                        name="product_id[]"
                                        data-placeholder="Search & select product..." >
                                        <option value="">Select product...</option>
                                        @if(isset($oldProductIds[$i]) && $oldProductIds[$i])
                                            @php
                                                $product = \App\Models\Product::find($oldProductIds[$i]);
                                            @endphp
                                            @if($product)
                                                <option value="{{ $product->id }}" selected>{{ $product->product_name }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>

                                <!-- Serial Number -->
                                <div class="col-span-2">
                                    <x-base.form-label>Serial Number</x-base.form-label>
                                    <x-base.form-input
                                        type="text"
                                        name="serial_number[]"
                                        placeholder="Enter serial number"
                                        value="{{ old('serial_number')[$i] ?? '' }}"
                                    />
                                </div>

                                <!-- Quantity -->
                                <div class="col-span-2">
                                    <x-base.form-label>Quantity</x-base.form-label>
                                    <x-base.form-input
                                        type="number"
                                        name="add_quantity[]"
                                        placeholder="Enter quantity"
                                        value="{{ $oldQuantities[$i] ?? '' }}"
                                        step="1" min="0"
                                    />
                                </div>

                                <!-- Employee -->
                                <div class="col-span-3">
                                    <x-base.form-label>Employee</x-base.form-label>
                                    <select
                                        class="employee-select tom-select w-full"
                                        name="emp_id[]"
                                        data-placeholder="Search & select employee..."
                                    >
                                        <option value="">Select employee...</option>
                                        @if(isset($oldEmpIds[$i]) && $oldEmpIds[$i])
                                            @php
                                                $employee = \App\Models\Employee::find($oldEmpIds[$i]);
                                            @endphp
                                            @if($employee)
                                                <option value="{{ $employee->id }}" selected>{{ $employee->name }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>

                                <!-- Remove Button -->
                                <div class="col-span-1 flex mt-3">
                                      <a class="flex items-center text-danger remove-row-btn" style="cursor: pointer;">
                                        <i class="fa fa-trash mr-1 text-red-600"></i> Delete
                                    </a>
                                </div>
                            </div>
                        @endfor
                    @else
                        @forelse ($toolAssign->items as $index => $item)
                            <div class="grid grid-cols-12 gap-4 mb-4 row-item">
                                <!-- Product (Item) -->
                                <div class="col-span-3">
                                    <x-base.form-label>Product (Item)</x-base.form-label>
                                    <select
                                        class="product-select tom-select w-full"
                                        name="product_id[]"
                                        data-placeholder="Search & select product..." >
                                        <option value="{{ $item->product_id }}" selected>{{ $item->product->product_name ?? 'Unnamed Product' }}</option>
                                    </select>
                                </div>

                                <!-- Serial Number -->
                                <div class="col-span-2">
                                    <x-base.form-label>Serial Number</x-base.form-label>
                                    <x-base.form-input
                                        type="text"
                                        name="serial_number[]"
                                        placeholder="Enter serial number"
                                        value="{{ $item->serial_number }}"
                                    />
                                </div>

                                <!-- Quantity -->
                                <div class="col-span-2">
                                    <x-base.form-label>Quantity</x-base.form-label>
                                    <x-base.form-input
                                        type="number"
                                        name="add_quantity[]"
                                        placeholder="Enter quantity"
                                        value="{{ $item->quantity }}"
                                        step="1" min="0"
                                    />
                                </div>

                                <!-- Employee -->
                                <div class="col-span-3">
                                    <x-base.form-label>Employee</x-base.form-label>
                                    <select
                                        class="employee-select tom-select w-full"
                                        name="emp_id[]"
                                        data-placeholder="Search & select employee..."
                                    >
                                        <option value="{{ $item->emp_id }}" selected>{{ $item->employee->name ?? 'Unnamed Employee' }}</option>
                                    </select>
                                </div>

                                <!-- Remove Button -->
                                <div class="col-span-1 flex mt-3">
                                      <a class="flex items-center text-danger remove-row-btn" style="cursor: pointer;">
                                        <i class="fa fa-trash mr-1 text-red-600"></i> Delete
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="grid grid-cols-12 gap-4 mb-4 row-item">
                                <!-- Product (Item) -->
                                <div class="col-span-3">
                                    <x-base.form-label>Product (Item)</x-base.form-label>
                                    <select
                                        class="product-select tom-select w-full"
                                        name="product_id[]"
                                        data-placeholder="Search & select product..."
                                    >
                                        <option value="">Select product...</option>
                                    </select>
                                </div>

                                <!-- Serial Number -->
                                <div class="col-span-2">
                                    <x-base.form-label>Serial Number</x-base.form-label>
                                    <x-base.form-input
                                        type="text"
                                        name="serial_number[]"
                                        placeholder="Enter serial number"
                                    />
                                </div>

                                <!-- Quantity -->
                                <div class="col-span-2">
                                    <x-base.form-label>Quantity</x-base.form-label>
                                    <x-base.form-input
                                        type="number"
                                        name="add_quantity[]"
                                        placeholder="Enter quantity"
                                        step="1" min="0"
                                    />
                                </div>

                                <!-- Employee -->
                                <div class="col-span-3">
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
                                <div class="col-span-1 flex mt-3">
                                    <a class="flex items-center text-danger remove-row-btn" style="cursor: pointer;">
                                       <i class="fa fa-trash mr-1 text-red-600"></i> Delete
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    @endif

                    <!-- CLEAN TEMPLATE OUTSIDE rows-container -->
                        <template id="row-template">
                            <div class="grid grid-cols-12 gap-4 mb-4 row-item">
                                <div class="col-span-3">
                                    <label class="form-label">Product (Item)</label>
                                    <select class="product-select tom-select w-full" name="product_id[]" data-placeholder="Search & select product...">
                                        <option value="">Select product...</option>
                                    </select>
                                </div>

                                <div class="col-span-2">
                                    <label class="form-label">Serial Number</label>
                                    <input type="text" name="serial_number[]" placeholder="Enter serial number" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80"/>
                                </div>

                                <div class="col-span-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="add_quantity[]" placeholder="Enter quantity" step="1" min="0" class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80"/>
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">Employee</label>
                                    <select class="employee-select tom-select w-full" name="emp_id[]" data-placeholder="Search & select employee...">
                                        <option value="">Select employee...</option>
                                    </select>
                                </div>

                                <div class="col-span-1 flex mt-3">
                                    <a class="flex items-center text-danger remove-row-btn" style="cursor: pointer;">
                                      <i class="fa fa-trash mr-1 text-red-600"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </template>

                </div>

                 <x-base.button type="button" id="add-row-btn" variant="primary">
                    Add Row
                </x-base.button>
            </div>

            <div class="flex mt-8">
                <a href="{{ route('tool-assigns.index') }}" class="mr-3">
                    <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                </a>
                <x-base.button type="submit" variant="primary">Save Changes</x-base.button>
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
            document.addEventListener('DOMContentLoaded', function () {
                let itemCount = {{ $toolAssign->items->count() > 0 ? $toolAssign->items->count() : 1 }};
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
                // Initialize Tom Select for ALL existing product and employee selects
                const allProductSelects = document.querySelectorAll('.product-select');
                allProductSelects.forEach(select => {
                    initializeProductSelect(select);
                });

                const allEmployeeSelects = document.querySelectorAll('.employee-select');
                allEmployeeSelects.forEach(select => {
                    initializeEmployeeSelect(select);
                });

                // Add click event to all existing rows
                const allRows = document.querySelectorAll('.row-item');
                allRows.forEach(row => {
                    row.addEventListener('click', function() {
                        selectRow(this);
                    });
                });

                // Add row button event listener
               document.getElementById('add-row-btn').addEventListener('click', function () {
                    const container = document.getElementById('rows-container');
                    const template = document.getElementById('row-template');
                    
                    const newRow = template.content.cloneNode(true);
                    container.appendChild(newRow);

                    const row = container.lastElementChild;

                    // Initialize TomSelect for new fields
                    initializeProductSelect(row.querySelector('.product-select'));
                    initializeEmployeeSelect(row.querySelector('.employee-select'));
                });


                // Remove row button event listener
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-row-btn')) {
                        const container = document.getElementById('rows-container');
                        const rows = container.querySelectorAll('.row-item:not(.hidden)');
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


				// Form validation on submit
                document.getElementById('edit-form').addEventListener('submit', function(e) {
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
