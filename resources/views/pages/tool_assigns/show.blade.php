@extends('../layouts/' . $layout)

@section('subhead')
    <title>Tool Assign Details - Jewelry ERP</title>
@endsection

@section('subcontent')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Tool Assign Details
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('tool-assigns.edit', $toolAssign->id) }}">
            <x-base.button class="mr-2 shadow-md" variant="primary">
                Edit Tool Assign
            </x-base.button>
        </a>
        <a href="{{ route('tool-assigns.index') }}">
            <x-base.button class="mr-2 shadow-md" variant="primary">
                Back to List
            </x-base.button>
        </a>
    </div>
</div>

<div class="intro-y box p-5 mt-5">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">ID</label>
            <div class="form-control-plaintext">{{ $toolAssign->id }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Department</label>
            <div class="form-control-plaintext">{{ $toolAssign->department->name ?? 'N/A' }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Date</label>
            <div class="form-control-plaintext">
                {{ $toolAssign->date ? \Carbon\Carbon::parse($toolAssign->date)->format('d M Y H:i:s') : 'N/A' }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Created At</label>
            <div class="form-control-plaintext">
                {{ $toolAssign->created_at ? \Carbon\Carbon::parse($toolAssign->created_at)->format('d M Y H:i:s') : 'N/A' }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Updated At</label>
            <div class="form-control-plaintext">
                {{ $toolAssign->updated_at ? \Carbon\Carbon::parse($toolAssign->updated_at)->format('d M Y H:i:s') : 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Assigned Items Table -->
    <div class="mt-5">
        <h3 class="text-lg font-medium mb-3">Assigned Items</h3>
        <div class="overflow-x-auto">
            <x-base.table class="border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Product</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Serial Number</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Quantity</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Employee</x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @forelse ($toolAssign->items as $item)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md">
                                {{ $item->product->product_name ?? 'N/A' }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $item->serial_number ?? '-' }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $item->quantity ?? '0' }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md">
                                {{ $item->employee->name ?? 'N/A' }}
                            </x-base.table.td>
                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="4" class="text-center text-slate-500 py-4">
                                No items assigned.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>
    </div>

    <!-- Inventory Calculation
    <div class="mt-5">
        <h3 class="text-lg font-medium mb-3">Inventory Calculation (FIFO)</h3>
        <div class="overflow-x-auto">
            <x-base.table class="border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Product</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Total Purchased Qty</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Total Purchased Value</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Total Assigned Qty</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Remaining Qty</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Remaining Value</x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @forelse ($inventory as $inv)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md">
                                {{ $inv['product_name'] }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format($inv['total_purchased_qty'], 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format($inv['total_purchased_value'], 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format($inv['total_assigned_qty'], 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ number_format($inv['remaining_qty'], 2) }}
                            </x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] last:rounded-r-md">
                                {{ number_format($inv['remaining_value'], 2) }}
                            </x-base.table.td>
                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="6" class="text-center text-slate-500 py-4">
                                No inventory data.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>
    </div> -->
</div>
@endsection
