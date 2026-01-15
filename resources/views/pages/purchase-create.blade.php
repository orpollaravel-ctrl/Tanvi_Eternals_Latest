@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Purchase - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create Purchase</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12">
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

                <form method="POST" action="{{ route('purchases.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Vendor -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Vendor *</x-base.form-label>
                            <x-base.form-select name="vendor_id" required>
                                <option value="">Select Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </x-base.form-select>
                        </div>

                        <!-- Bill Number -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Bill Number *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="bill_number"
                                value="{{ old('bill_number') }}"
                                placeholder="Enter bill number"
                                required
                            />
                        </div>

                        <!-- Bill Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Bill Date *</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="bill_date"
                                value="{{ old('bill_date') }}"
                                required
                            />
                        </div>

                        <!-- Delivery Date 
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Delivery Date *</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="delivery_date"
                                value="{{ old('delivery_date') }}"
                                required
                            />
                        </div>-->

                        <!-- Total Invoice Amount
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Total Invoice Amount *</x-base.form-label>  </div>-->
                            <x-base.form-input
                                type="hidden"
                                step="0.01"
                                name="total_invoice_amount"
                                id="total_invoice_amount"
                                value="{{ old('total_invoice_amount') }}"
                                placeholder="0.00"
                                required
                                readonly
                            />
                        

                        <!-- Bill Photo -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Bill Photo</x-base.form-label>
                            <x-base.form-file-input
                                name="bill_photo"
                                type="file"
                                accept="image/*"
                            />
                            <p class="text-xs text-slate-500 mt-2">Max file size: 2MB. Supported: JPEG, PNG, JPG, GIF</p>
                        </div>
                    </div>

                    <!-- Purchase Items Section -->
                    <div class="mt-8 mb-4">
                        <h3 class="text-md font-medium">Purchase Items</h3>
                        <p class="text-sm text-slate-500 mb-4">Select products from dropdown and fill in the details. Press Enter to navigate between fields.</p>
                    </div>

                    <!-- Horizontal Table Format -->
                    <div class="border rounded-lg">
                        <table class="w-full">
                            <!-- Table Header -->
                            <thead class="bg-slate-200 border-b">
                                <tr class="text-left text-sm font-medium">
                                    <th class="px-4 py-3" style="min-width: 250px;">Product Name *</th>
                                    <th class="px-4 py-3" style="min-width: 120px;">Serial Number</th>
                                    <th class="px-4 py-3" style="min-width: 100px;">Quantity *</th>
                                    <th class="px-4 py-3" style="min-width: 100px;">Rate *</th>
                                    <th class="px-4 py-3">Amount</th>
									  <th class="px-4 py-3">GST %</th>
                                    <th class="px-4 py-3">GST Value</th>
                                    <th class="px-4 py-3">Final Amount</th>
                                    <th class="px-4 py-3 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <!-- Table Body -->
                            <tbody id="items-container" class="divide-y">
                                {{--<tr class="item-row hover:bg-slate-50">
                                    <!-- Product Search Dropdown -->
                                    <td class="px-4 py-3">
                                        <x-base.tom-select
                                            class="product-select w-full"
                                            name="items[0][product_id]"
                                            data-item-index="0"
                                            data-placeholder="Search & select product..."
                                            required
                                        >
                                            <option value="">Select product...</option>
                                        </x-base.tom-select>
                                    </td>

                                   <!-- <td class="px-4 py-3">
                                        <input
                                            type="date"
                                            class="expiry-date-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            name="items[0][expiry_date]"
                                            data-item-index="0"
                                        />
                                    </td> -->

                                    <td class="px-4 py-3">
                                        <input
                                            type="number"
                                            step="0.01"
                                            class="quantity-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            name="items[0][quantity]"
                                            placeholder="0.00"
                                            data-item-index="0"
                                            required
                                        />
                                    </td>

                                    <td class="px-4 py-3">
                                        <input
                                            type="number"
                                            step="0.01"
                                            class="rate-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            name="items[0][rate]"
                                            placeholder="0.00"
                                            data-item-index="0"
                                            required
                                        />
                                    </td>

                                    <td class="px-4 py-3">
                                        <input
                                            type="number"
                                            step="0.01"
                                            class="amount-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            name="items[0][amount]"
                                            placeholder="0.00"
                                            data-item-index="0"
                                            readonly
                                        />
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="remove-item-btn text-danger hover:text-danger/70 p-1">
                                            <x-base.lucide class="h-4 w-4" icon="Trash" />
                                        </button>
                                    </td>
                                </tr>--}}
                            </tbody>
                            <!-- Table Footer -->
                            <tfoot class="bg-slate-100 border-t">
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-right font-medium">Total Amount:</td>
                                    <td class="px-4 py-3">
                                        <input
                                            type="number"
                                            step="0.01"
                                            id="total-amount-display"
                                            class="w-full border rounded px-3 py-2 text-sm bg-gray-50"
                                            value="0.00"
                                            readonly
                                        />
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <x-base.button type="button" id="add-item-btn" variant="outline-primary" class="mt-4">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="Plus" />
                        Add Item
                    </x-base.button>

                    <div class="mt-5 flex items-center">
                        <a href="{{ route('purchases.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary" id="submit-btn">Create Purchase</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('vendors')
        @vite('resources/js/vendor/tom-select/index.js')
    @endpush

    @push('scripts')
        @vite('resources/js/components/tom-select/index.js')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let itemCount = 0;
                const addItemBtn = document.getElementById('add-item-btn');
                const itemsContainer = document.getElementById('items-container');

                // Function to calculate amount for a row
                function calculateAmount(row) {
                    const quantityInput = row.querySelector('.quantity-input');
                    const rateInput = row.querySelector('.rate-input');
                    const amountInput = row.querySelector('.amount-input');
					const gstPercentageInput = row.querySelector('.gst-percentage-input');
                    const gstValueInput = row.querySelector('.gst-value-input');
                    const finalAmountInput = row.querySelector('.final-amount-input');

                    const quantity = parseFloat(quantityInput.value) || 0;
                    const rate = parseFloat(rateInput.value) || 0;
                    const amount = quantity * rate;
					const gstPercentage = parseFloat(gstPercentageInput.value) || 0;
                    const gstValue = (amount * gstPercentage) / 100;
                    const finalAmount = amount + gstValue;

                    amountInput.value = amount.toFixed(2);
                    gstValueInput.value = gstValue.toFixed(2);
                    finalAmountInput.value = finalAmount.toFixed(2);
                    return finalAmount;
                }

                // Function to calculate total amount
                function calculateTotal() {
                    const finalAmountInputs = document.querySelectorAll('.final-amount-input');
                    let total = 0;
                    finalAmountInputs.forEach(input => {
                        total += parseFloat(input.value) || 0;
                    });
                    const totalAmountDisplay = document.getElementById('total-amount-display');
                    const totalInvoiceAmount = document.getElementById('total_invoice_amount');

                    totalAmountDisplay.value = total.toFixed(2);
                    totalInvoiceAmount.value = total.toFixed(2);
                }

                // Initialize event handlers for first item
                //initializeItemHandlers(0);

                addItemBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Add Item clicked');
                    const itemRow = document.createElement('tr');
                    itemRow.className = 'item-row hover:bg-slate-50';
                    itemRow.innerHTML = `
                        <td class="px-4 py-3">
                            <select
                                class="product-select tom-select w-full"
                                name="items[${itemCount}][product_id]"
                                data-item-index="${itemCount}"
                                data-placeholder="Search & select product..."
                                required
                            >
                                <option value="">Select product...</option>
                            </select>
                            <input type="hidden" name="items[${itemCount}][product_name]" class="product-name-input" data-item-index="${itemCount}" />
                        </td>

                        <td class="px-4 py-3">
                            <input type="text" name="items[${itemCount}][serial_number]" placeholder="Enter serial number" class="serial-number-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}" />
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][quantity]" placeholder="0.00" class="quantity-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}" required />
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][rate]" placeholder="0.00" class="rate-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}" required />
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][amount]" placeholder="0.00" class="amount-input w-full border rounded px-3 py-2 text-sm bg-gray-50" data-item-index="${itemCount}" readonly />
                        </td>
						<td class="px-4 py-3">
                            <select name="items[${itemCount}][gst_percentage]" class="gst-percentage-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}">
                                <option value="">Select GST %</option>
								<option value="0">0%</option>
                                <option value="3">3%</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18">18%</option>
                                <option value="28">28%</option>
                            </select>
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][gst_value]" placeholder="0.00" class="gst-value-input w-full border rounded px-3 py-2 text-sm bg-gray-50" data-item-index="${itemCount}" readonly />
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][final_amount]" placeholder="0.00" class="final-amount-input w-full border rounded px-3 py-2 text-sm bg-gray-50" data-item-index="${itemCount}" readonly />
                        </td>

                        <td class="px-4 py-3 text-center">
                            <button type="button" class="remove-item-btn text-danger hover:text-danger/70 p-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    `;

                    itemsContainer.appendChild(itemRow);
                    initializeItemHandlers(itemCount);
                    itemCount++;

                    // Calculate total after adding new row
                    calculateTotal();
                });

                function initializeItemHandlers(index) {
                    // Product dropdown handler
                    const productSelect = document.querySelector(`.product-select[data-item-index="${index}"]`);

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
                                                        text: `${product.product_name}${product.barcode_number ? ' - ' + product.barcode_number : ''}${product.tool_code ? ' (' + product.tool_code + ')' : ''}`,
                                                        product_name: product.product_name,
                                                        rate: product.minimum_rate || 0,
                                                        minimum_quantity: product.minimum_quantity || 0
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

                                // Handle product selection - set rate, minimum quantity, product_name and focus next field
                                productSelect.addEventListener('change', function () {
                                    console.log('Product selected:', this.value);
                                    if (this.value && tomSelectInstance.options) {
                                        const selectedData = tomSelectInstance.options[this.value];
                                        console.log('Selected data:', selectedData);
                                        const row = this.closest('tr');
                                        
                                        // Set product name
                                        if (selectedData && selectedData.product_name) {
                                            const productNameInput = row.querySelector('.product-name-input');
                                            if (productNameInput) {
                                                productNameInput.value = selectedData.product_name;
                                            }
                                        }
                                        
                                        // Set minimum rate if available
                                        if (selectedData && selectedData.rate) {
                                            const rateInput = row.querySelector('.rate-input');
                                            rateInput.value = selectedData.rate;
                                        }
                                        
                                        // Set minimum quantity if available
                                        if (selectedData && selectedData.minimum_quantity) {
                                            const quantityInput = row.querySelector('.quantity-input');
                                            quantityInput.value = selectedData.minimum_quantity;
                                        }
                                        calculateAmount(row);
                                        calculateTotal();
                                        focusNextField(index, 'expiry-date-input');
                                    }
                                });
                            }
                        }, 100);
                    }

                    // Enter key navigation between fields
                    const fields = [
                        { selector: `.expiry-date-input[data-item-index="${index}"]`, nextField: 'quantity-input' },
                        { selector: `.quantity-input[data-item-index="${index}"]`, nextField: 'rate-input' },
                        { selector: `.rate-input[data-item-index="${index}"]`, nextField: 'amount-input' },
                        { selector: `.amount-input[data-item-index="${index}"]`, nextField: null }
                    ];

                    fields.forEach(field => {
                        const input = document.querySelector(field.selector);
                        if (input) {
                            input.addEventListener('keydown', function (e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    if (field.nextField) {
                                        focusNextField(index, field.nextField);
                                    }
                                }
                            });
                        }
                    });

                    // Add calculation event listeners
                    const quantityInput = document.querySelector(`.quantity-input[data-item-index="${index}"]`);
                    const rateInput = document.querySelector(`.rate-input[data-item-index="${index}"]`);
					  const gstPercentageInput = document.querySelector(`.gst-percentage-input[data-item-index="${index}"]`);

                    if (quantityInput) {
                        quantityInput.addEventListener('input', function() {
                            const row = this.closest('tr');
                            calculateAmount(row);
                            calculateTotal();
                        });
                    }

                    if (rateInput) {
                        rateInput.addEventListener('input', function() {
                            const row = this.closest('tr');
                            calculateAmount(row);
                            calculateTotal();
                        });
                    }
					  if (gstPercentageInput) {
                        gstPercentageInput.addEventListener('input', function() {
                            const row = this.closest('tr');
                            calculateAmount(row);
                            calculateTotal();
                        });
                    }

                    // Remove item handler
                    const removeBtn = document.querySelector(`.item-row .remove-item-btn:not([data-item-index])`);
                    const allRemoveBtns = document.querySelectorAll('.item-row .remove-item-btn');
                    allRemoveBtns.forEach(btn => {
                        if (!btn.hasListener) {
                            btn.addEventListener('click', function (e) {
                                e.preventDefault();
                                const itemRows = document.querySelectorAll('.item-row');
                                if (itemRows.length > 1) {
                                    this.closest('.item-row').remove();
                                    // Recalculate total after removing row
                                    calculateTotal();
                                } else {
                                    alert('At least one item is required');
                                }
                            });
                            btn.hasListener = true;
                        }
                    });
                }

                function focusNextField(index, fieldClass) {
                    const input = document.querySelector(`.${fieldClass}[data-item-index="${index}"]`);
                    if (input) {
                        input.focus();
                        input.select();
                    }
                }

                // Form submission validation
                document.querySelector('form').addEventListener('submit', function (e) {
                    const itemRows = document.querySelectorAll('.item-row');
                    let isValid = true;
                    let invalidField = null;

                    itemRows.forEach((row, index) => {
                        const productSelect = row.querySelector('.product-select');
                        const quantityInput = row.querySelector('.quantity-input');
                        const rateInput = row.querySelector('.rate-input');

                        // Check if product is selected
                        if (!productSelect.value) {
                            isValid = false;
                            invalidField = productSelect;
                            productSelect.style.borderColor = '#dc2626';
                            alert('Row ' + (index + 1) + ': Please select a product');
                        } else {
                            productSelect.style.borderColor = '';
                        }

                        // Check if quantity is filled
                        if (!quantityInput.value) {
                            isValid = false;
                            invalidField = quantityInput;
                            quantityInput.style.borderColor = '#dc2626';
                        } else {
                            quantityInput.style.borderColor = '';
                        }

                        // Check if rate is filled
                        if (!rateInput.value) {
                            isValid = false;
                            invalidField = rateInput;
                            rateInput.style.borderColor = '#dc2626';
                        } else {
                            rateInput.style.borderColor = '';
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        if (invalidField) {
                            invalidField.focus();
                        }
                    }
                });
            });
        </script>
    @endpush
    <style>
        .item-row{
            height: auto;
        }
    </style>
@endsection
