@extends('../layouts/' . $layout)

@section('subhead')
    <title>Employee Wise Tool Assign Report - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Employee Wise Tool Assign Report</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Filters -->
        <div class="intro-y col-span-6 justify-betwen flex items-center">
            <div class="me-3">
                <x-base.menu>
                    <x-base.menu.button class="!box px-2" as="x-base.button">
                        <span class="flex h-5 w-5 items-center justify-center">
                            <x-base.lucide class="h-4 w-4" icon="Plus" />
                        </span>
                    </x-base.menu.button>

                    <x-base.menu.items class="w-40">
                        <x-base.menu.item
                            as="a"
                            href="{{ route('tool-assigns.employee-wise-report.export', request()->query()) }}"
                        >
                            <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" />
                            Export to Excel
                        </x-base.menu.item>
                    </x-base.menu.items>
                </x-base.menu>
            </div>
            <form method="GET" action="{{ route('tool-assigns.employee-wise-report') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Employee Filter -->
                <div class="w-full sm:w-48">
                    <select id="employee-filter" name="employee_id" class="employee-select tom-select w-full" data-placeholder="Search & select employee...">
                        <option value="">All Employees</option>
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
                    <x-base.button type="button" variant="outline-secondary" id="clear-filters">Clear</x-base.button>
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
                        <x-base.table.th class="whitespace-nowrap border-b-0">Employee</x-base.table.th>
                        {{-- <x-base.table.th class="whitespace-nowrap border-b-0">Department</x-base.table.th> --}}
                        <x-base.table.th class="whitespace-nowrap border-b-0">Total Amount</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Details</x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @php
                        $grouped = $toolAssigns->groupBy(function($assign) {
                            return $assign->items->first()->emp_id ?? 'unknown';
                        });
                        $index = 1;
						$hasFilterEmployee = request()->filled('employee_id');

                    @endphp
                    @forelse ($employees as $employee)
                        @php
                            $empId = $employee->id;
                            $assignments = $grouped->get($empId, collect());
                            $totalAmount = 0;
                            $departmentName = '';
                            foreach ($assignments as $assign) {
                                foreach ($assign->items as $item) {
                                    if ($item->emp_id == $empId) {
                                        $amount = $item->quantity * ($item->product->purchaseItems->avg('rate') ?? 0);
                                        $totalAmount += $amount;
                                        if (!$departmentName) {
                                            $departmentName = $assign->department->name ?? '-';
                                        }
                                    }
                                }
                            }
                            $hasAssignments = $assignments->isNotEmpty();
							if (!$hasFilterEmployee && $totalAmount == 0) {
								continue;
							}
                        @endphp
                        <x-base.table.tr class="intro-x employee-row" data-emp-id="{{ $empId }}">
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md">
                                {{ $index++ }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $employee->name ?? '-' }}
                            </x-base.table.td>
                            {{-- <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $departmentName }}
                            </x-base.table.td> --}}
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format($totalAmount, 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] last:rounded-r-md">
                                @if($hasAssignments)
                                    <button class="btn btn-sm btn-primary toggle-details" data-emp-id="{{ $empId }}">
                                        <x-base.lucide icon="ChevronDown" class="w-4 h-4" />
                                    </button>
                                @endif
                            </x-base.table.td>
                        </x-base.table.tr>
                        @if($hasAssignments)
                            <!-- Collapsible Details -->
                            <x-base.table.tr class="details-row hidden" data-emp-id="{{ $empId }}">
                                <x-base.table.td colspan="4" class="border-b-0 bg-slate-50 dark:bg-darkmode-700 p-4">
                                    <div class="overflow-auto">
                                        <table class="w-full border-separate border-spacing-y-[5px]">
                                            <thead>
                                                <tr>
                                                    <th class="text-left">Department</th>
                                                    <th class="text-left">Product</th>
                                                    <th class="text-left">Quantity</th>
                                                    <th class="text-left">Date</th>
                                                    <th class="text-left">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($assignments as $assign)
                                                    @foreach ($assign->items as $item)
                                                        @if($item->emp_id == $empId)
                                                            <tr>
                                                                <td>{{ $assign->department->name ?? '-' }}</td>
                                                                <td>{{ $item->product->product_name ?? '-' }}</td>
                                                                <td>{{ number_format($item->quantity, 2) }}</td>
                                                                <td>{{ $assign->date ? \Carbon\Carbon::parse($assign->date)->format('d M Y') : '-' }}</td>
                                                                <td>{{ number_format($item->quantity * ($item->product->purchaseItems->avg('rate') ?? 0), 2) }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </x-base.table.td>
                            </x-base.table.tr>
                        @endif
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="5" class="text-center text-slate-500 py-4">
                                No employees found And Still Not Item assign.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>

        <!-- Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap items-center">
            {{-- Pagination removed as we are showing all employees --}}
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

                // Initialize Tom Select for department filter
                const departmentFilter = document.getElementById('department-filter');
                if (departmentFilter) {
                    departmentTomSelect = new TomSelect(departmentFilter, {
                        plugins: ['dropdown_input'],
                        create: false,
                        placeholder: 'Search & select department...',
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        maxOptions: null,
                        loadThrottle: 300,
                        preload: 'focus',
                        load: function(query, callback) {
                            const searchQuery = query || '';
                            const url = '/api/departments/search?q=' + encodeURIComponent(searchQuery);

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.data && Array.isArray(data.data)) {
                                        const options = data.data.map(department => ({
                                            value: department.id,
                                            text: department.name,
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
                const startDateInput = document.getElementById('start-date');
                const endDateInput = document.getElementById('end-date');
                const clearFiltersBtn = document.getElementById('clear-filters');

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
                        if (employeeFilter) employeeFilter.value = '';
                        if (startDateInput) startDateInput.value = '';
                        if (endDateInput) endDateInput.value = '';
                        // Reset TomSelect
                        if (typeof employeeTomSelect !== 'undefined' && employeeTomSelect) employeeTomSelect.clear();
                        // Redirect to clear URL
                        window.location.href = '{{ route("tool-assigns.employee-wise-report") }}';
                    });
                }

                // Toggle employee details - using event delegation for dynamic content
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.toggle-details')) {
                        e.preventDefault();
                        const button = e.target.closest('.toggle-details');
                        const empId = button.getAttribute('data-emp-id');
                        const detailsRow = document.querySelector(`.details-row[data-emp-id="${empId}"]`);
                        const icon = button.querySelector('svg');

                        if (detailsRow) {
                            if (detailsRow.classList.contains('hidden')) {
                                detailsRow.classList.remove('hidden');
                                if (icon) icon.style.transform = 'rotate(180deg)';
                            } else {
                                detailsRow.classList.add('hidden');
                                if (icon) icon.style.transform = 'rotate(0deg)';
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
