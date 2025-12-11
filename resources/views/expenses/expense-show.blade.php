@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Expense - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Expense Details</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Type of Expense</x-base.form-label>
                        <div class="mt-2 p-3 bg-slate-100 rounded-md">
                            <span class="px-2 py-1 rounded bg-primary/20 text-primary text-sm">{{ ucwords($expense->type) }}</span>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Date</x-base.form-label>
                        <div class="mt-2 p-3 bg-slate-100 rounded-md">
                            {{ $expense->date->format('d M Y') }}
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Amount</x-base.form-label>
                        <div class="mt-2 p-3 bg-slate-100 rounded-md font-medium">
                            ₹{{ number_format($expense->amount, 2) }}
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Bill Photo</x-base.form-label>
                        <div class="mt-2 p-3 bg-slate-100 rounded-md">
                            @if($expense->bill_upload)
                                <x-base.button type="button" variant="primary" onclick="viewBill('{{ asset('uploads/expenses/' . $expense->bill_upload) }}', '{{ $expense->bill_upload }}')">
                                    <x-base.lucide class="mr-2 h-4 w-4" icon="Eye" />
                                    View Photo
                                </x-base.button>
                            @else
                                <span class="text-slate-500">No bill uploaded</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-span-12">
                        <x-base.form-label>Remark</x-base.form-label>
                        <div class="mt-2 p-3 bg-slate-100 rounded-md min-h-[100px]">
                            {{ $expense->remark ?: 'No remarks' }}
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex items-center">
                    <a href="{{ route('expenses.index') }}" class="mr-3">
                        <x-base.button type="button" variant="outline-secondary">Back to List</x-base.button>
                    </a>
                    @if(auth()->check() && auth()->user()->hasPermission('edit-expenses'))
                        <a href="{{ route('expenses.edit', $expense->id) }}">
                            <x-base.button type="button" variant="primary">Edit Expense</x-base.button>
                        </a>
                    @endif
                </div>
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
                    <img id="bill-image" src="" alt="Bill Photo" class="max-w-full max-h-96 mx-auto rounded-md shadow-lg" style="display: none;">
                    <iframe id="bill-pdf" src="" class="w-full h-96 rounded-md" style="display: none;"></iframe>
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