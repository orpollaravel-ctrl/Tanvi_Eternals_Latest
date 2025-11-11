@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Purchase - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Purchase</h2>
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

                <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Purchase Party -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Purchase Party *</x-base.form-label>
                            <x-base.form-select name="purchase_party_id" required>
                                <option value="">Select Purchase Party</option>
                                @foreach ($purchaseParties as $party)
                                    <option value="{{ $party->id }}" {{ old('purchase_party_id', $purchase->purchase_party_id) == $party->id ? 'selected' : '' }}>
                                        {{ $party->party_name }}
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
                                value="{{ old('bill_number', $purchase->bill_number) }}"
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
                                value="{{ old('bill_date', $purchase->bill_date?->format('Y-m-d')) }}"
                                required
                            />
                        </div>

                        <!-- Delivery Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Delivery Date *</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="delivery_date"
                                value="{{ old('delivery_date', $purchase->delivery_date?->format('Y-m-d')) }}"
                                required
                            />
                        </div>

                        <!-- Total Invoice Amount -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Total Invoice Amount *</x-base.form-label>
                            <x-base.form-input
                                type="number"
                                step="0.01"
                                name="total_invoice_amount"
                                value="{{ old('total_invoice_amount', $purchase->total_invoice_amount) }}"
                                placeholder="0.00"
                                required
                            />
                        </div>

                        <!-- Bill Photo -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Bill Photo</x-base.form-label>
                            <x-base.form-file-input
                                name="bill_photo"
                                type="file"
                                accept="image/*"
                            />
                            <p class="text-xs text-slate-500 mt-2">Max file size: 2MB. Supported: JPEG, PNG, JPG, GIF</p>
                            @if($purchase->bill_photo)
                                <p class="text-xs text-slate-600 mt-2">Current: {{ $purchase->bill_photo }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Purchase Items Section -->
                    <div class="mt-8 mb-4">
                        <h3 class="text-md font-medium">Purchase Items</h3>
                        <p class="text-sm text-slate-500 mb-4">Select products from dropdown and fill in the details. Press Enter to navigate between fields.</p>
                    </div>

                    <!-- Horizontal Table Format -->
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full">
                            <!-- Table Header -->
                            <thead class="bg-slate-200 border-b">
                                <tr class="text-left text-sm font-medium">
                                    <th class="px-4 py-3 w-1/4">Product Name *</th>
                                    <th class="px-4 py-3 w-1/6">Expiry Date</th>
                                    <th class="px-4 py-3 w-1/6">Quantity *</th>
                                    <th class="px-4 py-3 w-1/6">Rate *</th>
                                    <th class="px-4 py-3 w-1/6">Amount *</th>
                                    <th class="px-4 py-3 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <!-- Table Body -->
                            <tbody id="items-container" class="divide-y">
                                @foreach($purchase->items as $index => $item)
                                    <tr class="item-row hover:bg-slate-50">
                                        <td class="px-4 py-3">
                                            <select
                                                class="product-select tom-select w-full"
                                                name="items[{{ $index }}][product_id]"
                                                data-item-index="{{ $index }}"
                                                data-placeholder="Search & select product..."
                                                required
                                            >
                                                <option value="">Select product...</option>
                                            </select>
                                        </td>

                                        <td class="px-4 py-3">
                                            <input
                                                type="date"
                                                class="expiry-date-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                name="items[{{ $index }}][expiry_date]"
                                                value="{{ old('items.' . $index . '.expiry_date', $item->expiry_date?->format('Y-m-d')) }}"
                                                data-item-index="{{ $index }}"
                                            />
                                        </td>

                                        <td class="px-4 py-3">
                                            <input
                                                type="number"
                                                step="0.01"
                                                class="quantity-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                name="items[{{ $index }}][quantity]"
                                                value="{{ old('items.' . $index . '.quantity', $item->quantity) }}"
                                                placeholder="0.00"
                                                data-item-index="{{ $index }}"
                                                required
                                            />
                                        </td>

                                        <td class="px-4 py-3">
                                            <input
                                                type="number"
                                                step="0.01"
                                                class="rate-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                name="items[{{ $index }}][rate]"
                                                value="{{ old('items.' . $index . '.rate', $item->rate) }}"
                                                placeholder="0.00"
                                                data-item-index="{{ $index }}"
                                                required
                                            />
                                        </td>

                                        <td class="px-4 py-3">
                                            <input
                                                type="number"
                                                step="0.01"
                                                class="amount-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                name="items[{{ $index }}][amount]"
                                                value="{{ old('items.' . $index . '.amount', $item->amount) }}"
                                                placeholder="0.00"
                                                data-item-index="{{ $index }}"
                                                required
                                            />
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <button type="button" class="remove-item-btn text-danger hover:text-danger/70 p-1">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
                        <x-base.button type="submit" variant="primary">Update Purchase</x-base.button>
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
                let itemCount = {{ count($purchase->items) }};
                const addItemBtn = document.getElementById('add-item-btn');
                const itemsContainer = document.getElementById('items-container');

                // Initialize event handlers for existing items
                for (let i = 0; i < {{ count($purchase->items) }}; i++) {
                    initializeItemHandlers(i);
                }

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
                        </td>

                        <td class="px-4 py-3">
                            <input type="date" name="items[${itemCount}][expiry_date]" class="expiry-date-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}" />
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][quantity]" placeholder="0.00" class="quantity-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}" required />
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][rate]" placeholder="0.00" class="rate-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}" required />
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" step="0.01" name="items[${itemCount}][amount]" placeholder="0.00" class="amount-input w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" data-item-index="${itemCount}" required />
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
                                    load: function(query, callback) {
                                        console.log('Loading products for query:', query);
                                        if (!query.length) {
                                            return callback();
                                        }

                                        const url = '/api/products/search?q=' + encodeURIComponent(query);
                                        console.log('Fetching from URL:', url);

                                        fetch(url)
                                            .then(response => {
                                                console.log('Response status:', response.status);
                                                return response.json();
                                            })
                                            .then(data => {
                                                console.log('Received data:', data);
                                                if (data.success && data.data && Array.isArray(data.data)) {
                                                    const options = data.data.map(product => ({
                                                        value: product.id,
                                                        text: `${product.product_name}${product.barcode_number ? ' - ' + product.barcode_number : ''}${product.tool_code ? ' (' + product.tool_code + ')' : ''}`,
                                                        rate: product.minimum_rate || 0
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

                                // Handle product selection - set rate and focus next field
                                productSelect.addEventListener('change', function () {
                                    console.log('Product selected:', this.value);
                                    if (this.value && tomSelectInstance.options) {
                                        const selectedData = tomSelectInstance.options[this.value];
                                        console.log('Selected data:', selectedData);
                                        if (selectedData && selectedData.rate) {
                                            const row = this.closest('tr');
                                            const rateInput = row.querySelector('.rate-input');
                                            rateInput.value = selectedData.rate;
                                        }
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

                    // Remove item handler
                    const allRemoveBtns = document.querySelectorAll('.item-row .remove-item-btn');
                    allRemoveBtns.forEach(btn => {
                        if (!btn.hasListener) {
                            btn.addEventListener('click', function (e) {
                                e.preventDefault();
                                const itemRows = document.querySelectorAll('.item-row');
                                if (itemRows.length > 1) {
                                    this.closest('.item-row').remove();
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
                        const amountInput = row.querySelector('.amount-input');

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
                            quantityInput.style.borderColor = '#dc2626';
                        } else {
                            quantityInput.style.borderColor = '';
                        }

                        // Check if rate is filled
                        if (!rateInput.value) {
                            isValid = false;
                            rateInput.style.borderColor = '#dc2626';
                        } else {
                            rateInput.style.borderColor = '';
                        }

                        // Check if amount is filled
                        if (!amountInput.value) {
                            isValid = false;
                            amountInput.style.borderColor = '#dc2626';
                        } else {
                            amountInput.style.borderColor = '';
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
@endsection
