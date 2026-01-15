@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Expense - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Expense Details</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-between gap-3 sm:flex-nowrap">
            <div class="flex items-center gap-2">
                <a href="{{ route('expenses.index') }}">
                    <x-base.button class="shadow-md" variant="outline-secondary">
                        <x-base.lucide class="mr-2 h-4 w-4" icon="ArrowLeft" />
                        Back to Expenses
                    </x-base.button>
                </a>
                <x-base.menu>
                    <x-base.menu.button class="!box px-2" as="x-base.button">
                        <span class="flex h-5 w-5 items-center justify-center">
                            <x-base.lucide class="h-4 w-4" icon="Plus" />
                        </span>
                    </x-base.menu.button>
                    <x-base.menu.items class="w-40">
                        <x-base.menu.item>
                            <a href="javascript:void(0);" onclick="printExpenses()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                            </a>
                        </x-base.menu.item>
                        <x-base.menu.item>
                            <a href="javascript:void(0);" onclick="exportExpensesToExcel()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to Excel
                            </a>
                        </x-base.menu.item>
                    </x-base.menu.items>
                </x-base.menu>
            </div>
            <form method="GET" class="relative">
                <x-base.form-select class="w-44" name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </x-base.form-select>
            </form>
        </div>
        {{-- <div class="intro-y col-span-12 lg:col-span-6">
                <div class="relative bg-white dark:bg-darkmode-600 rounded-xl shadow-lg overflow-hidden">

                    <!-- Left Accent -->
                    <div class="absolute left-0 top-0 h-full w-1 bg-primary"></div>

                    <div style="padding: 15px;">

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-slate-800 dark:text-white">
                                    Expense Details
                                </h2>
                                <p class="text-sm text-slate-500">
                                    Recorded expense summary
                                </p>
                            </div>

                            <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-primary/10 text-primary">
                                {{ ucwords($expense->type) }}
                            </span>
                        </div>

                        <!-- Amount Highlight -->
                        <div class="mb-6">
                            <p class="text-sm text-slate-500">Total Amount</p>
                            <p class="text-md font-bold text-primary">
                                ₹{{ number_format($expense->amount, 2) }}
                            </p>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

                            <div class="flex items-center gap-3">
                                <x-base.lucide icon="Calendar" class="w-5 h-5 text-slate-400" />
                                <div>
                                    <p class="text-xs text-slate-500">Date</p>
                                    <p class="font-medium">
                                        {{ $expense->date->format('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <x-base.lucide icon="FileText" class="w-5 h-5 text-slate-400" />
                                <div>
                                    <p class="text-xs text-slate-500">Bill</p>
                                    @if ($expense->bill_upload)
                                        <button
                                            onclick="viewBill('{{ asset('uploads/expenses/' . $expense->bill_upload) }}','{{ $expense->bill_upload }}')"
                                            class="text-primary font-medium text-sm hover:underline">
                                            View Bill
                                        </button>
                                    @else
                                        <p class="text-slate-400 text-sm">Not Available</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <x-base.lucide icon="MessageSquare" class="w-5 h-5 text-slate-400" />
                                <div>
                                    <p class="text-xs text-slate-500">Remark</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 truncate max-w-xs">
                                        {{ $expense->remark ?: 'No remarks added' }}
                                    </p>
                                </div>
                            </div>

                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-200 dark:border-darkmode-400">
                            <a href="{{ route('expenses.index') }}">
                                <x-base.button variant="outline-secondary">
                                    ← Back
                                </x-base.button>
                            </a>

                            @if (auth()->check() && auth()->user()->hasPermission('edit-expenses'))
                                <a href="{{ route('expenses.edit', $expense->id) }}">
                                    <x-base.button variant="primary">
                                        Edit Expense
                                    </x-base.button>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>  --}}

        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Type</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Date</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Amount</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Remark</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Bill</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Status</x-base.table.th>
                        @if (auth()->check() &&
                                (auth()->user()->hasPermission('view-expenses') ||
                                    auth()->user()->hasPermission('edit-expenses') ||
                                    auth()->user()->hasPermission('delete-expenses')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @forelse($expenses as $expense)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <span class="whitespace-nowrap font-medium">{{ ucwords($expense->type) }}</span>
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                {{ $expense->date->format('d M Y') }}
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                ₹{{ number_format($expense->amount, 2) }}
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                {{ $expense->remark ? Str::limit($expense->remark, 50) : '-' }}
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                @if ($expense->bill_upload)
                                    <a href="{{ asset('uploads/expenses/' . $expense->bill_upload) }}" target="_blank"
                                        class="text-primary">View Bill</a>
                                @else
                                    -
                                @endif
                            </x-base.table.td>
                            <x-base.table.td
                                class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                <div id="status-{{ $expense->id }}">
                                    @if($expense->status === 'pending')
                                        <div class="flex gap-2">
                                            <button onclick="updateStatus({{ $expense->id }}, 'approved')"
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200">
                                                <x-base.lucide icon="Check" class="w-3 h-3 mr-1" />
                                                Approve
                                            </button>
                                            <button onclick="updateStatus({{ $expense->id }}, 'rejected')"
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200">
                                                <x-base.lucide icon="X" class="w-3 h-3 mr-1" />
                                                Reject
                                            </button>
                                        </div>
                                    @elseif($expense->status === 'approved')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                            <x-base.lucide icon="Check" class="w-3 h-3 mr-1" />
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
                                            <x-base.lucide icon="X" class="w-3 h-3 mr-1" />
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                            </x-base.table.td>
                            @if (auth()->check() &&
                                    (auth()->user()->hasPermission('view-expenses') ||
                                        auth()->user()->hasPermission('edit-expenses') ||
                                        auth()->user()->hasPermission('delete-expenses')))
                                <x-base.table.td class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
                                    <div class="flex items-center justify-center">
                                        @if (auth()->check() && auth()->user()->hasPermission('view-expenses'))
                                            <a class="mr-3 flex items-center"
                                                href="{{ route('expenses.view', $expense->id) }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Eye" />
                                                View
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-expenses'))
                                            <a class="mr-3 flex items-center"
                                                href="{{ route('expenses.edit', $expense->id) }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-expenses'))
                                            <a class="flex items-center text-danger" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal" href="#"
                                                data-delete-route="{{ route('expenses.destroy', $expense->id) }}"
                                                data-delete-name="Expense">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                            </a>
                                        @endif
                                    </div>
                                </x-base.table.td>
                            @endif
                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="6" class="text-center text-slate-500 py-4">
                                No expenses found.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>

        <!-- Bill Photo Modal -->
        <x-base.dialog id="bill-photo-modal" size="xl">
            <x-base.dialog.panel>
                <x-base.dialog.title>
                    <h2 class="mr-auto text-base font-medium">Bill Photo</h2>
                </x-base.dialog.title>
                <x-base.dialog.description class="grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 text-center">
                        <img id="bill-image" src="" alt="Bill Photo"
                            class="max-w-full max-h-96 mx-auto rounded-md shadow-lg" style="display: none;">
                        <iframe id="bill-pdf" src="" class="w-full h-96 rounded-md"
                            style="display: none;"></iframe>
                    </div>
                </x-base.dialog.description>
                <x-base.dialog.footer>
                    <x-base.button type="button" variant="outline-secondary" data-tw-dismiss="modal">
                        Close
                    </x-base.button>
                </x-base.dialog.footer>
            </x-base.dialog.panel>
        </x-base.dialog>
         <!-- BEGIN: Delete Confirmation Modal -->
    <x-base.dialog id="delete-confirmation-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
                <div class="mt-5 text-3xl">Are you sure?</div>
                <div class="mt-2 text-slate-500">
                    Do you really want to delete this expense?
                    <br />This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <form id="delete-expense-form" method="POST" action="" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button"
                        variant="outline-secondary">
                        Cancel
                    </x-base.button>
                    <x-base.button class="w-24" type="submit" variant="danger">
                        Delete
                    </x-base.button>
                </form>
            </div>
        </x-base.dialog.panel>
    </x-base.dialog> 
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('[data-delete-route]');
                const deleteForm = document.getElementById('delete-expense-form');

                deleteButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        const route = this.getAttribute('data-delete-route');
                        if (deleteForm && route) {
                            deleteForm.setAttribute('action', route);
                        }
                    });
                });
            });
        </script>
        <script>
            function viewBill(url, filename) {
                const modal = document.getElementById('bill-photo-modal');
                const image = document.getElementById('bill-image');
                const pdf = document.getElementById('bill-pdf');

                // Hide both elements first
                image.style.display = 'none';
                pdf.style.display = 'none';

                // Check file extension
                const extension = filename.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png'].includes(extension)) {
                    image.src = url;
                    image.style.display = 'block';
                } else if (extension === 'pdf') {
                    pdf.src = url;
                    pdf.style.display = 'block';
                }

                // Show modal
                const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
                modalInstance.show();
            }

            function updateStatus(expenseId, status) {
                const statusDiv = document.getElementById(`status-${expenseId}`);
                
                fetch(`/expenses/${expenseId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (status === 'approved') {
                            statusDiv.innerHTML = `
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20,6 9,17 4,12"/></svg>
                                    Approved
                                </span>
                            `;
                        } else {
                            statusDiv.innerHTML = `
                                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Rejected
                                </span>
                            `;
                        }
                    } else {
                        alert('Error updating status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating status');
                });
            }

            function printExpenses() {
                const params = new URLSearchParams(window.location.search);
                params.set('salesman_id', '{{ $salesman->id }}');
                window.open(`{{ route('expenses.print') }}?${params.toString()}`, '_blank');
            }

            function exportExpensesToExcel() {
                const params = new URLSearchParams(window.location.search);
                params.set('salesman_id', '{{ $salesman->id }}');
                window.location.href = `{{ route('expenses.export.excel') }}?${params.toString()}`;
            }
        </script>
    @endpush
@endsection
