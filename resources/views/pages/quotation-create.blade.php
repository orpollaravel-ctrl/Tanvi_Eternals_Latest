@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Quotation - Tanvi Eternals</title>
@endsection

@section('subcontent')
    @php
        $optionClass =
            'cursor-pointer px-3 py-2 border border-gray-300 rounded-md text-sm font-medium transition-all duration-200 hover:bg-slate-50 hover:border-slate-400';
        $activeClass = 'radio-active text-white';
    @endphp

    <style>
        .radio-active {
            background-color: #164E63 !important;
            border-color: #164E63 !important;
        }

        .radio-active:hover {
            background-color: #0f3a4a !important;
        }

        .metal-option img {
            transition: all 0.2s ease;
        }

        .metal-option.selected img {
            border-color: #164E63 !important;
            border-width: 3px;
            box-shadow: 0 0 0 2px rgba(22, 78, 99, 0.2);
        }

        .metal-option:hover img {
            border-color: #164E63;
            transform: scale(1.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.name === 'metal') {
                        document.querySelectorAll(`input[name="${this.name}"]`).forEach(function(
                        r) {
                            r.closest('label').querySelector('.metal-option').classList
                                .remove('selected');
                        });
                        if (this.checked) {
                            this.closest('label').querySelector('.metal-option').classList.add(
                                'selected');
                        }
                    } else {
                        document.querySelectorAll(`input[name="${this.name}"]`).forEach(function(
                        r) {
                            r.closest('label').classList.remove('radio-active',
                                'text-white');
                        });
                        if (this.checked) {
                            this.closest('label').classList.add('radio-active', 'text-white');
                        }
                    }
                });
            });
        });
    </script>
    <h2 class="intro-y mt-10 text-lg font-medium">Create Quotation</h2>
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

                <form method="POST" action="{{ route('quotations.store') }}">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                           {{-- Customer Name --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Customer Name *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="customer_name"
                                value="{{ old('customer_name') }}"
                                placeholder="Enter Customer Name"
                                required
                            />
                        </div>

                        {{-- Salesman --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Salesman *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="salesman_name"
                                value="{{ old('salesman_name') }}"
                                placeholder="Enter Salesman Name"
                                required
                            />
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Contact *</x-base.form-label>
                            <x-base.form-input type="text" id="customer-contact" name="contact"
                                value="{{ old('contact') }}" placeholder="Contact Number" required  />
                        </div> 
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Barcode</x-base.form-label>
                            <select id="product" name="barcode[]" multiple></select>
                            <p id="product-error" class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Metal *</x-base.form-label>
                            <div class="flex gap-6 mt-2">
                                @php
                                    $metals = [
                                        'yellow gold' => '/uploads/yellow.png',
                                        'rose gold' => '/uploads/rose.png',
                                        'white gold' => '/uploads/white.png',
                                    ];
                                @endphp
                                @foreach ($metals as $metal => $image)
                                    <label class="cursor-pointer text-center">
                                        <input type="radio" name="metal" value="{{ $metal }}" class="hidden"
                                            @checked(old('metal') == $metal)>
                                        <div class="metal-option {{ old('metal') == $metal ? 'selected' : '' }}"
                                            style="margin-left: 10px;">
                                            <img src="{{ $image }}" alt="{{ $metal }}"
                                                class="w-12 h-12 rounded-full border-2 border-gray-300 object-cover">
                                        </div>
                                        <span class="block mt-2 text-sm font-medium">{{ ucwords($metal) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Purity *</x-base.form-label>
                            <div class="flex gap-3 mt-2">
                                @foreach (['22K', '18K', '14K', '9K'] as $p)
                                    <label
                                        class="{{ $optionClass }} {{ old('purity') == strtolower($p) ? $activeClass : '' }}">
                                        <input type="radio" name="purity" value="{{ strtolower($p) }}" class="hidden"
                                            @checked(old('purity') == strtolower($p))>
                                        {{ $p }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-span-12">
                            <x-base.form-label>Diamond *</x-base.form-label>
                            <div class="flex flex-wrap gap-3 mt-2">
                                @foreach (['SI-IJ', 'SI-GH', 'VS-GH', 'VVS-EF', 'VS-SIGH', 'VS-ISHI', 'SI-HI','CVD'] as $d)
                                    <label class="{{ $optionClass }} {{ old('diamond') == $d ? $activeClass : '' }}">
                                        <input type="radio" name="diamond" value="{{ $d }}" class="hidden"
                                            @checked(old('diamond') == $d)>
                                        {{ $d }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mt-4 mb-2">Ring Size - Women</h3>
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-6">
                                    <x-base.form-label>From</x-base.form-label>
                                    <x-base.tom-select name="women_ring_size_from">
                                        <option value="">Select size</option>
                                        @foreach (range(9, 17) as $size)
                                            <option value="{{ $size }}" @selected(old('women_ring_size_from') == $size)>
                                                {{ $size }}
                                            </option>
                                        @endforeach
                                    </x-base.tom-select>
                                </div>
                                <div class="col-span-6">
                                    <x-base.form-label>To</x-base.form-label>
                                    <x-base.tom-select name="women_ring_size_to">
                                        <option value="">Select size</option>
                                        @foreach (range(9, 17) as $size)
                                            <option value="{{ $size }}" @selected(old('women_ring_size_to') == $size)>
                                                {{ $size }}
                                            </option>
                                        @endforeach
                                    </x-base.tom-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <h3 class="text-lg font-medium mt-4 mb-2">Ring Size - Men</h3>
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-6">
                                    <x-base.form-label>From</x-base.form-label>
                                    <x-base.tom-select name="men_ring_size_from">
                                        <option value="">Select size</option>
                                        @foreach (range(18, 26) as $size)
                                            <option value="{{ $size }}" @selected(old('men_ring_size_from') == $size)>
                                                {{ $size }}
                                            </option>
                                        @endforeach
                                    </x-base.tom-select>
                                </div>
                                <div class="col-span-6">
                                    <x-base.form-label>To</x-base.form-label>
                                    <x-base.tom-select name="men_ring_size_to">
                                        <option value="">Select size</option>
                                        @foreach (range(18, 26) as $size)
                                            <option value="{{ $size }}" @selected(old('men_ring_size_to') == $size)>
                                                {{ $size }}
                                            </option>
                                        @endforeach
                                    </x-base.tom-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <x-base.form-label>Remarks</x-base.form-label>
                            <x-base.form-textarea name="remarks" placeholder="Enter remarks"
                                value="{{ old('remarks') }}" />
                        </div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('quotations.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Create Quotation</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('vendors')
        @vite('resources/js/vendor/tom-select/index.js')
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const errorEl = document.getElementById('product-error');

                function showError(message) {
                    errorEl.innerText = message;
                    errorEl.classList.remove('hidden');

                    setTimeout(() => {
                        errorEl.classList.add('hidden');
                        errorEl.innerText = '';
                    }, 3000);
                }

                const productSelect = new TomSelect('#product', {
                    persist: false,
                    createOnBlur: true,
                    plugins: ['remove_button'],

                    onType: function(str) {
                        duplicateProduct = !!this.items.includes(str.trim());
                        this.refreshOptions(false);
                    },

                    create: function(input) {
                        input = input.trim();

                        if (this.items.includes(input)) {
                            duplicateProduct = true;
                            this.refreshOptions(false);
                            return false;
                        }

                        duplicateProduct = false;
                        return {
                            value: input,
                            text: input
                        };
                    },

                    render: {
                        no_results: function() {
                            if (duplicateProduct) {
                                return `<div class="no-results text-red-600 px-3 py-2">
                                Product already scanned
                            </div>`;
                            }

                            return `<div class="no-results px-3 py-2">
                            No results found
                        </div>`;
                        }
                    }
                });

                productSelect.control_input.focus();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const customerSelect = document.getElementById('customer-select');
                const customerCodeInput = document.getElementById('customer-code');
                const customerContactInput = document.getElementById('customer-contact');
                const salesmanInput = document.getElementById('salesman');

                const ts = customerSelect.tomselect;

                if (!ts) {
                    console.error('TomSelect instance not found');
                    return;
                }

                if (ts.getValue() && ts.options[ts.getValue()]) {
                    customerCodeInput.value = ts.options[ts.getValue()].code || '';
                    customerContactInput.value = ts.options[ts.getValue()].contact || '';
                    salesmanInput.value = ts.options[ts.getValue()].salesman || '';
                }

                ts.on('change', function(value) {
                    if (value && ts.options[value]) {
                        customerCodeInput.value = ts.options[value].code || '';
                        customerContactInput.value = ts.options[value].contact || '';
                        salesmanInput.value = ts.options[value].salesman || '';
                    } else {
                        customerCodeInput.value = '';
                        customerContactInput.value = '';
                        salesmanInput.value = '';
                    }
                });

                const radioLabels = document.querySelectorAll(
                'label:has(input[type="radio"]):not(:has(.metal-option))');

                radioLabels.forEach(label => {
                    const radio = label.querySelector('input[type="radio"]');

                    label.addEventListener('click', function() {
                        const sameName = document.querySelectorAll(`input[name="${radio.name}"]`);
                        sameName.forEach(r => {
                            const parentLabel = r.closest('label');
                            parentLabel.classList.remove('radio-active', 'text-white');
                            parentLabel.classList.add('hover:bg-slate-50',
                                'hover:border-slate-400');
                        });

                        setTimeout(() => {
                            if (radio.checked) {
                                label.classList.add('radio-active', 'text-white');
                                label.classList.remove('hover:bg-slate-50',
                                    'hover:border-slate-400');
                            }
                        }, 10);
                    });
                });

                const metalLabels = document.querySelectorAll('label:has(.metal-option)');
                metalLabels.forEach(label => {
                    const radio = label.querySelector('input[type="radio"]');
                    const metalOption = label.querySelector('.metal-option');

                    label.addEventListener('click', function() {
                        document.querySelectorAll('.metal-option').forEach(option => {
                            option.classList.remove('selected');
                        });

                        setTimeout(() => {
                            if (radio.checked) {
                                metalOption.classList.add('selected');
                            }
                        }, 10);
                    });
                });
            });
        </script>
    @endpush
@endsection
