@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Expense - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Expense Details</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
         
        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="relative bg-white dark:bg-darkmode-600 rounded-xl shadow-lg overflow-hidden">

                <!-- Left Accent -->
                <div class="absolute left-0 top-0 h-full w-1 bg-primary"></div>

                <div style="padding: 15px;">

                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6" style="justify-content:space-between;">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-800 dark:text-white">
                                {{ $expense->salesman_name }}
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
                    {{-- <div class="flex items-center gap-3 pt-4 border-t border-slate-200 dark:border-darkmode-400">
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
                        </div> --}}

                </div>
            </div>
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

        @push('scripts')
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
            </script>
        @endpush
    @endsection
