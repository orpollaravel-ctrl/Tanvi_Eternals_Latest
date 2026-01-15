@extends('../layouts/' . $layout)

@section('subhead')
    <title>Tool Assign Department Wise Report - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Tool Assign Department Wise Report</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Filters -->
        <div class="intro-y col-span-6">
			 <form method="GET" action="{{ route('tool-assigns.department-wise-report') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Employee Filter -->
                <div class="w-full sm:w-48">
                    <select id="department-filter" name="department_id" class="department-select tom-select w-full" data-placeholder="Search & select employee...">
                        <option value="">All Departments</option>
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
                    <x-base.button type="button" variant="outline-secondary" id="clear-filters">Clear </x-base.button>
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
            <x-base.table.th class="whitespace-nowrap border-b-0">Department</x-base.table.th>
            <x-base.table.th class="whitespace-nowrap border-b-0">Amount</x-base.table.th>
            <x-base.table.th class="whitespace-nowrap border-b-0">Actions</x-base.table.th>
        </x-base.table.tr>
    </x-base.table.thead>

    <x-base.table.tbody>
        @php
            $grouped = $toolAssigns->groupBy('d_id');
            $index = 1;
        @endphp

        @forelse ($departments as $department)
            @php
                $deptId = $department->id;
                $assignments = $grouped->get($deptId, collect());
                $totalAmount = 0;

                foreach ($assignments as $assign) {
                    foreach ($assign->items as $item) {

                        // SAFE product & purchaseItems access
                        $avgRate = $item->product?->purchaseItems?->avg('rate') ?? 0;
                        $amount = $item->quantity * $avgRate;

                        $totalAmount += $amount;
                    }
                }

                $hasAssignments = $assignments->isNotEmpty();
            @endphp

            <x-base.table.tr class="intro-x department-row" data-dept-id="{{ $deptId }}">
                <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md">
                    {{ $index++ }}
                </x-base.table.td>

                <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                    {{ $department->name ?? '-' }}
                </x-base.table.td>

                <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                    {{ number_format($totalAmount, 2) }}
                </x-base.table.td>

                <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] last:rounded-r-md">
                    @if($hasAssignments)
                        <button class="btn btn-sm btn-primary toggle-details" data-dept-id="{{ $deptId }}">
                            <x-base.lucide icon="ChevronDown" class="w-4 h-4" />
                        </button>
                    @endif
                </x-base.table.td>
            </x-base.table.tr>

            @if($hasAssignments)
                <!-- Details -->
                <x-base.table.tr class="details-row hidden" data-dept-id="{{ $deptId }}">
                    <x-base.table.td colspan="4" class="border-b-0 bg-slate-50 dark:bg-darkmode-700 p-4">
                        <div class="overflow-auto">
                            <table class="w-full border-separate border-spacing-y-[5px]">
                                <thead>
                                    <tr>
                                        <th class="text-left">Product</th>
                                        <th class="text-left">Employee</th>
                                        <th class="text-left">Quantity</th>
                                        <th class="text-left">Date</th>
                                        <th class="text-left">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($assignments as $assign)
                                        @foreach ($assign->items as $item)
                                            @php
                                                $avgRate = $item->product?->purchaseItems?->avg('rate') ?? 0;
                                                $amount = $item->quantity * $avgRate;
                                            @endphp
                                            <tr>
                                                <td>{{ $item->product?->product_name ?? '-' }}</td>
                                                <td>{{ $item->employee?->name ?? '-' }}</td>
                                                <td>{{ number_format($item->quantity, 2) }}</td>
                                                <td>{{ $assign->date ? \Carbon\Carbon::parse($assign->date)->format('d M Y') : '-' }}</td>
                                                <td>{{ number_format($amount, 2) }}</td>
                                            </tr>
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
                    No departments found.
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
               

                const tableBody = document.querySelector('.opening-stock-table tbody') || document.querySelector('tbody');
                const loadingIndicator = document.createElement('div');
                loadingIndicator.className = 'col-span-12 text-center py-4 hidden';
                loadingIndicator.innerHTML = '<div class="inline-flex items-center"><div class="animate-spin h-5 w-5 mr-2 border-2 border-blue-500 border-t-transparent rounded-full"></div>Loading more data...</div>';
                document.querySelector('.grid.grid-cols-12').appendChild(loadingIndicator);

                // Initialize Tom Select for department filter
                const departmentFilter = document.getElementById('department-filter');
                let departmentTomSelect;
                if (departmentFilter) {
                    // Fetch all departments on page load
                    fetch('/api/departments/search')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data && Array.isArray(data.data)) {
                                const options = data.data.map(department => ({
                                    value: department.id,
                                    text: department.name,
                                }));

                                departmentTomSelect = new TomSelect(departmentFilter, {
                                    plugins: ['dropdown_input'],
                                    create: false,
                                    placeholder: 'Select department...',
                                    valueField: 'value',
                                    labelField: 'text',
                                    searchField: 'text',
                                    maxOptions: null,
                                    options: options,
                                    items: []
                                });

                                
                            } else {
                                // Fallback if no data
                                departmentTomSelect = new TomSelect(departmentFilter, {
                                    plugins: ['dropdown_input'],
                                    create: false,
                                    placeholder: 'Select department...',
                                    valueField: 'value',
                                    labelField: 'text',
                                    searchField: 'text',
                                    maxOptions: null,
                                    options: [],
                                    items: []
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            // Fallback
                            departmentTomSelect = new TomSelect(departmentFilter, {
                                plugins: ['dropdown_input'],
                                create: false,
                                placeholder: 'Select department...',
                                valueField: 'value',
                                labelField: 'text',
                                searchField: 'text',
                                maxOptions: null,
                                options: [],
                                items: []
                            });
                        });
                }

                const clearFiltersBtn = document.getElementById('clear-filters');

                // Clear filters
                if (clearFiltersBtn) {
                    clearFiltersBtn.addEventListener('click', function() {
                       
                        // Reload data
                        window.location.href = "{{ route('tool-assigns.department-wise-report') }}";
                    });
                }

                // Toggle department details - using event delegation for dynamic content
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.toggle-details')) {
                        e.preventDefault();
                        const button = e.target.closest('.toggle-details');
                        const deptId = button.getAttribute('data-dept-id');
                        const detailsRow = document.querySelector(`.details-row[data-dept-id="${deptId}"]`);
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
