@extends('../layouts/' . $layout)

@section('subhead')
    <title>Product Report - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Product Report</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Filters -->
        <div class="intro-y col-span-12">
            <form method="GET" action="{{ route('tool-assigns.product-report') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full sm:w-64">
                    <x-base.lucide class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 text-slate-500" icon="Search" />
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}" class="filter-input box pl-10 w-full" placeholder="Search products, categories...">
                </div>

                <!-- Employee Filter -->
                <div class="w-full sm:w-48">
                    <select id="employee-filter" name="employee_id" class="employee-select tom-select w-full" data-placeholder="Search & select employee...">
                        <option value="">All Employees</option>
                    </select>
                </div>

                <!-- Product Filter -->
                <div class="w-full sm:w-48">
                    <select id="product-filter" name="product_id" class="product-select tom-select w-full" data-placeholder="Search & select product...">
                        <option value="">All Products</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="w-full sm:w-48">
                    <select id="category-filter" name="category_id" class="category-select tom-select w-full" data-placeholder="Search & select category...">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div class="flex gap-2 w-full sm:w-auto">
                    <input type="date" id="start-date" name="start_date" value="{{ request('start_date') }}" class="filter-input box w-full sm:w-32">
                    <input type="date" id="end-date" name="end_date" value="{{ request('end_date') }}" class="filter-input box w-full sm:w-32">
                </div>

                <!-- Filter and Cancel Buttons -->
                <div class="flex gap-2">
                    <x-base.button type="submit" variant="primary">Search</x-base.button>
                    <x-base.button type="button" variant="outline-secondary" id="clear-filters">Cancel</x-base.button>
                </div>
            </form>
        </div>
        <!-- END: Filters -->

        <!-- Report Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Product Name</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Barcode</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Tool Code</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Category</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Total Purchased</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Total Assigned</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Remaining</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Avg Rate</x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @forelse ($products as $product)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md">
                                {{ $loop->iteration }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $product->product_name }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $product->barcode_number ?? '-' }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $product->tool_code ?? '-' }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $product->category->name ?? '-' }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format($product->total_purchased ?? 0, 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format($product->total_assigned ?? 0, 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format(($product->total_purchased ?? 0) - ($product->total_assigned ?? 0), 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] last:rounded-r-md">
                                {{ number_format($product->avg_rate ?? 0, 2) }}
                            </x-base.table.td>
                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="9" class="text-center text-slate-500 py-4">
                                No products found.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>
    </div>

    @push('vendors')
        @vite('resources/js/vendor/tom-select/index.js')
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Tom Select for employee filter
                const employeeFilter = document.getElementById('employee-filter');
                let employeeTomSelect;
                if (employeeFilter) {
                    employeeTomSelect = new TomSelect(employeeFilter, {
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
                            const searchQuery = query || '';
                            const url = '/api/employees/search?q=' + encodeURIComponent(searchQuery);

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.data && Array.isArray(data.data)) {
                                        const options = data.data.map(employee => ({
                                            value: employee.id,
                                            text: `${employee.name}${employee.code ? ' (' + employee.code + ')' : ''}${employee.barcode ? ' - ' + employee.barcode : ''}`,
                                        }));
                                        callback(options);
                                    } else {
                                        callback();
                                    }
                                })
                                .catch(error => {
                                    console.error('Fetch error:', error);
                                    callback();
                                });
                        }
                    });
                }

                // Initialize Tom Select for product filter
                const productFilter = document.getElementById('product-filter');
                let productTomSelect;
                if (productFilter) {
                    productTomSelect = new TomSelect(productFilter, {
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
                            const searchQuery = query || '';
                            const url = '/api/products/search?q=' + encodeURIComponent(searchQuery);

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.data && Array.isArray(data.data)) {
                                        const options = data.data.map(product => ({
                                            value: product.id,
                                            text: `${product.product_name}${product.barcode_number ? ' - ' + product.barcode_number : ''}${product.tool_code ? ' (' + product.tool_code + ')' : ''} (Remaining: ${product.remaining_quantity})`,
                                        }));
                                        callback(options);
                                    } else {
                                        callback();
                                    }
                                })
                                .catch(error => {
                                    console.error('Fetch error:', error);
                                    callback();
                                });
                        }
                    });
                }

                // Initialize Tom Select for category filter
                const categoryFilter = document.getElementById('category-filter');
                let categoryTomSelect;
                if (categoryFilter) {
                    categoryTomSelect = new TomSelect(categoryFilter, {
                        plugins: ['dropdown_input'],
                        create: false,
                        placeholder: 'Search & select category...',
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        maxOptions: null,
                        loadThrottle: 300,
                        preload: 'focus',
                        load: function(query, callback) {
                            const searchQuery = query || '';
                            const url = '/api/categories/search?q=' + encodeURIComponent(searchQuery);

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.data && Array.isArray(data.data)) {
                                        const options = data.data.map(category => ({
                                            value: category.id,
                                            text: category.name,
                                        }));
                                        callback(options);
                                    } else {
                                        callback();
                                    }
                                })
                                .catch(error => {
                                    console.error('Fetch error:', error);
                                    callback();
                                });
                        }
                    });
                }

                // Filter change events
                const searchInput = document.getElementById('search-input');
                const startDateInput = document.getElementById('start-date');
                const endDateInput = document.getElementById('end-date');
                const clearFiltersBtn = document.getElementById('clear-filters');

                // Debounced search
                let searchTimeout;
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            // For now, just log; backend needs to handle search
                            console.log('Search:', this.value);
                        }, 500);
                    });
                }

                if (startDateInput) {
                    startDateInput.addEventListener('change', function() {
                        // Auto-submit or handle via AJAX if needed
                    });
                }

                if (endDateInput) {
                    endDateInput.addEventListener('change', function() {
                        // Auto-submit or handle via AJAX if needed
                    });
                }

                // Clear filters
                if (clearFiltersBtn) {
                    clearFiltersBtn.addEventListener('click', function() {
                        if (searchInput) searchInput.value = '';
                        if (employeeFilter) employeeFilter.value = '';
                        if (productFilter) productFilter.value = '';
                        if (categoryFilter) categoryFilter.value = '';
                        if (startDateInput) startDateInput.value = '';
                        if (endDateInput) endDateInput.value = '';
                        // Reset TomSelect
                        if (employeeTomSelect) employeeTomSelect.clear();
                        if (productTomSelect) productTomSelect.clear();
                        if (categoryTomSelect) categoryTomSelect.clear();
                        // Redirect to clear URL
                        window.location.href = '{{ route("tool-assigns.product-report") }}';
                    });
                }
            });
        </script>
    @endpush
@endsection
