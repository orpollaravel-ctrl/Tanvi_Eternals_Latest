@extends('../layouts/' . $layout)

@section('subhead')
    <title>Tool Assign List - Jewelry ERP</title>
@endsection

<style>
    .tool-assign-table {
        width: 100%;
        border-collapse: separate !important;
        border-spacing: 0 12px !important;
    }

    .tool-assign-table tbody tr {
        background: #ffffff;
        box-shadow: 0px 4px 14px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }

    .tool-assign-table tbody tr td:first-child {
        border-radius: 12px 0 0 12px;
    }

    .tool-assign-table tbody tr td:last-child {
        border-radius: 0 12px 12px 0;
    }

    .tool-assign-table thead th {
        font-weight: 600;
        color: #475569;
        padding-bottom: 8px;
        font-size: 13px;
    }

    .tool-assign-table tbody td {
        padding: 14px 18px;
        font-size: 13px;
        vertical-align: middle;
        background-color: #fff;
    }

    .tool-assign-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.12);
    }

    .filter-input {
        border-radius: 8px;
        padding: 6px 8px;
        font-size: 13px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .filter-input:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
    }

    .filter-select {
        border-radius: 8px;
        padding: 6px 8px;
        font-size: 13px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .filter-select:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
    }
</style>

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Tool Assign List</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Header Actions -->
        @if (auth()->check() && auth()->user()->hasPermission('create-tool-issues'))
            <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
                <a href="{{ route('tool-assigns.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New Tool Assign
                    </x-base.button>
                </a>
            </div>
        @endif
        <!-- END: Header Actions -->

        <!-- BEGIN: Filters -->
        <div class="intro-y col-span-12">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full sm:w-64">
                    <x-base.lucide class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 text-slate-500"
                        icon="Search" />
                    <input type="text" id="search-input" class="filter-input box pl-10 w-full"
                        placeholder="Search departments, employees, products...">
                </div>

                <!-- Employee Filter -->
                <div class="w-full sm:w-48">
                    <select id="employee-filter" class="employee-select tom-select w-full"
                        data-placeholder="Search & select employee...">
                        <option value="">All Employees</option>
                    </select>
                </div>

                <!-- Product Filter -->
                <div class="w-full sm:w-48">
                    <select id="product-filter" class="product-select tom-select w-full"
                        data-placeholder="Search & select product...">
                        <option value="">All Products</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="flex gap-2 w-full sm:w-auto">
                    <input type="date" id="start-date" class="filter-input box w-full sm:w-32">
                    <input type="date" id="end-date" class="filter-input box w-full sm:w-32">
                </div>

                <!-- Clear Filters -->
                <button id="clear-filters" class="btn btn-outline-secondary px-4 py-2">Clear</button>
            </div>
        </div>
        <!-- END: Filters -->

        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="tool-assign-table -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap border-b-0">#</th>
                        <th class="whitespace-nowrap border-b-0 cursor-pointer sort-header" data-sort="department">
                            Department <span class="sort-icon">↕</span></th>
                        <th class="whitespace-nowrap border-b-0 cursor-pointer sort-header" data-sort="date">Date <span
                                class="sort-icon">↕</span></th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-tool-issues') || auth()->user()->hasPermission('delete-tool-issues')))
                            <th class="whitespace-nowrap border-b-0 text-center">Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody id="tool-assign-table-body">
                    @forelse ($toolAssigns as $assign)
                        <tr class="intro-x">
                            <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md"
                                style="text-align: center;">
                                {{ $loop->iteration }}
                            </td>

                            <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]"
                                style="text-align: center;">
                                {{ $assign->department->name ?? '-' }}
                            </td>

                            <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]"
                                style="text-align: center;">
                                {{ $assign->date ? \Carbon\Carbon::parse($assign->date)->format('d M Y') : '-' }}
                            </td>
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-tool-issues') || auth()->user()->hasPermission('delete-tool-issues')))
                                <td
                                    class="relative border-b-0 bg-white py-0 text-center dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                    <div class="flex items-center justify-center">
                                        <!-- View -->
                                        <a href="{{ route('tool-assigns.show', $assign->id) }}"
                                            class="flex items-center mr-3 text-primary">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="Eye" /> View
                                        </a>
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-tool-issues'))
                                            <!-- Edit -->
                                            <a href="{{ route('tool-assigns.edit', $assign->id) }}"
                                                class="flex items-center mr-3 text-success">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" /> Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-tool-issues'))
                                            <!-- Delete -->
                                            <form action="{{ route('tool-assigns.destroy', $assign->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center text-danger	mt-3"
                                                    onclick="return confirm('Are you sure you want to delete this tool assign record?')">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate-500 py-4">
                                No tool assign records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- END: Data Table -->

        <!-- Loading indicator -->
        <div id="loading-indicator" class="col-span-12 text-center py-4 hidden">
            <div class="inline-flex items-center">
                <x-base.loading-icon class="animate-spin h-5 w-5 mr-2" />
                Loading tool assigns...
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let offset = 25;
            let isLoading = false;
            let hasMore = true;
            let currentSearch = '';
            let currentEmployee = '';
            let currentProduct = '';
            let currentStartDate = '';
            let currentEndDate = '';
            let currentSortBy = 'created_at';
            let currentSortOrder = 'desc';

            const tableBody = document.getElementById('tool-assign-table-body');
            const loadingIndicator = document.getElementById('loading-indicator');

            // Filter elements
            const searchInput = document.getElementById('search-input');
            const employeeFilter = document.getElementById('employee-filter');
            const productFilter = document.getElementById('product-filter');
            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');
            const clearFiltersBtn = document.getElementById('clear-filters');

            // Sort headers
            const sortHeaders = document.querySelectorAll('.sort-header');

            // Debounced search
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value.trim();
                    offset = 0;
                    loadToolAssigns(true);
                }, 500);
            });

            // Filter change events
            employeeFilter.addEventListener('change', function() {
                currentEmployee = this.value;
                offset = 0;
                loadToolAssigns(true);
            });

            productFilter.addEventListener('change', function() {
                currentProduct = this.value;
                offset = 0;
                loadToolAssigns(true);
            });

            startDateInput.addEventListener('change', function() {
                currentStartDate = this.value;
                offset = 0;
                loadToolAssigns(true);
            });

            endDateInput.addEventListener('change', function() {
                currentEndDate = this.value;
                offset = 0;
                loadToolAssigns(true);
            });

            // Clear filters
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                employeeFilter.value = '';
                productFilter.value = '';
                startDateInput.value = '';
                endDateInput.value = '';
                currentSearch = '';
                currentEmployee = '';
                currentProduct = '';
                currentStartDate = '';
                currentEndDate = '';
                offset = 0;
                loadToolAssigns(true);
            });

            // Sort functionality
            sortHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const sortBy = this.dataset.sort;
                    if (currentSortBy === sortBy) {
                        currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSortBy = sortBy;
                        currentSortOrder = 'asc';
                    }
                    updateSortIcons();
                    offset = 0;
                    loadToolAssigns(true);
                });
            });

            function updateSortIcons() {
                sortHeaders.forEach(header => {
                    const icon = header.querySelector('.sort-icon');
                    if (header.dataset.sort === currentSortBy) {
                        icon.textContent = currentSortOrder === 'asc' ? '↑' : '↓';
                    } else {
                        icon.textContent = '↕';
                    }
                });
            }

            function loadToolAssigns(reset = false) {
                if (reset) {
                    offset = 0;
                    tableBody.innerHTML = '';
                    hasMore = true;
                }

                if (isLoading || !hasMore) return;

                isLoading = true;
                loadingIndicator.classList.remove('hidden');

                const params = new URLSearchParams({
                    offset: offset,
                    search: currentSearch,
                    employee_id: currentEmployee,
                    product_id: currentProduct,
                    start_date: currentStartDate,
                    end_date: currentEndDate,
                    sort_by: currentSortBy,
                    sort_order: currentSortOrder,
                });

                fetch(`{{ route('tool-assigns.index') }}?${params}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.data.length > 0) {
                            const startNumber = offset + 1;
                            data.data.forEach((assign, index) => {
                                const rowNumber = startNumber + index;
                                const rowHtml = `
                                <tr class="intro-x">
                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md" style="text-align: center;">
                                        ${rowNumber}
                                    </td>
                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]" style="text-align: center;">
                                        ${assign.department ? assign.department.name : '-'}
                                    </td>
                                    <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]" style="text-align: center;">
                                        ${assign.date ? new Date(assign.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-'}
                                    </td>
                                    <td class="relative border-b-0 bg-white py-0 text-center dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            <a href="/tool-assigns/${assign.id}" class="flex items-center mr-3 text-primary">
                                                <svg class="mr-1 h-4 w-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> View
                                            </a>
                                            <a href="/tool-assigns/${assign.id}/edit" class="flex items-center mr-3 text-success">
                                                <svg class="mr-1 h-4 w-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit
                                            </a>
                                            <form action="/tool-assigns/${assign.id}" method="POST" class="inline">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button type="submit" class="flex items-center text-danger mt-3" onclick="return confirm('Are you sure you want to delete this tool assign record?')">
                                                    <svg class="mr-1 h-4 w-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3,6 5,6 21,6"/><path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            `;
                                tableBody.insertAdjacentHTML('beforeend', rowHtml);
                            });
                            offset += data.data.length;
                        } else if (reset) {
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center text-slate-500 py-4">
                                    No tool assign records found.
                                </td>
                            </tr>
                        `;
                        }

                        hasMore = data.has_more;
                        isLoading = false;
                        loadingIndicator.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error loading tool assigns:', error);
                        isLoading = false;
                        loadingIndicator.classList.add('hidden');
                    });
            }

            // Infinite scroll with throttling
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (scrollTimeout) return;

                scrollTimeout = setTimeout(() => {
                    scrollTimeout = null;

                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const windowHeight = window.innerHeight;
                    const documentHeight = document.documentElement.scrollHeight;

                    if (documentHeight - (scrollTop + windowHeight) < 300 && !isLoading &&
                        hasMore) {
                        loadToolAssigns(false);
                    }
                }, 200);
            });

            // Initialize sort icons
            updateSortIcons();
        });
    </script>

    @push('vendors')
        @vite('resources/js/vendor/tom-select/index.js')
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Tom Select for employee filter
                const employeeFilter = document.getElementById('employee-filter');
                if (employeeFilter) {
                    const employeeTomSelect = new TomSelect(employeeFilter, {
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
                if (productFilter) {
                    const productTomSelect = new TomSelect(productFilter, {
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
            });
        </script>
    @endpush
@endsection
