@extends('../layouts/' . $layout)

@section('subhead')
    <title>Payments - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Payments</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">

        <!-- BEGIN: Filters -->
        <div class="intro-y col-span-12">
            <div class="box p-5">
                <div class="accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Filters
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <form action="">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 sm:col-span-3">
                                            <x-base.form-label>Bullion Name</x-base.form-label>
                                            <x-base.form-select name="bullion">
                                                <option value="0">ALL</option>
                                                @if (!empty($bullions))
                                                    @foreach ($bullions as $bullion)
                                                        <option value="{{ $bullion->id }}" @if ($bullion->id == request()->input('bullion')) selected @endif>
                                                            {{ $bullion->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </x-base.form-select>
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <x-base.form-label>From Date</x-base.form-label>
                                            <x-base.form-input
                                                type="date"
                                                name="from_date"
                                                max="{{ now()->format('Y-m-d') }}"
                                                value="{{ request()->input('from_date', now()->format('Y-m-d')) }}"
                                                required
                                            />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <x-base.form-label>To Date</x-base.form-label>
                                            <x-base.form-input
                                                type="date"
                                                name="to_date"
                                                max="{{ now()->format('Y-m-d') }}"
                                                value="{{ request()->input('to_date', now()->format('Y-m-d')) }}"
                                                required
                                            />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3 flex items-end gap-2">
                                            <x-base.button type="submit" variant="primary">Search</x-base.button>
                                            <a href="{{ route('payments.index') }}">
                                                <x-base.button type="button" variant="outline-secondary">Clear</x-base.button>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Filters -->

        <!-- BEGIN: Header Actions -->
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <a href="{{ route('payments.create') }}">
                <x-base.button class="mr-2 shadow-md" variant="primary">
                    Add Payment
                </x-base.button>
            </a>
        </div>
        <!-- END: Header Actions -->

        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">

                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Name</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Date</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Amount</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Payment Mode</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Transferred By</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Created By</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Updated By</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Remark</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Created At</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Actions</x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody>
                    @forelse ($payments as $index => $payment)
                        <x-base.table.tr class="intro-x">

                            <!-- Row ID -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payments->firstItem() + $index }}
                            </x-base.table.td>

                            <!-- Name -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payment->bullion->name }}
                            </x-base.table.td>

                            <!-- Date -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ \Carbon\Carbon::parse($payment->pay_date)->format('d/m/Y') }}
                            </x-base.table.td>

                            <!-- Amount -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payment->amount }}
                            </x-base.table.td>

                            <!-- Payment Mode -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payment->paymentMode->name }}
                            </x-base.table.td>

                            <!-- Transferred By -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payment->transferredBy->name }}
                            </x-base.table.td>

                            <!-- Created By -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payment->createdBy->name }}
                            </x-base.table.td>

                            <!-- Updated By -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                @if ($payment->updatedBy) {{ $payment->updatedBy->name }} @endif
                            </x-base.table.td>

                            <!-- Remark -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payment->remark }}
                            </x-base.table.td>

                            <!-- Created At -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $payment->created_at->diffForHumans() }}
                            </x-base.table.td>

                            <!-- Actions -->
                            <x-base.table.td
                                class="relative border-b-0 bg-white py-0 text-center shadow-[20px_3px_20px_#0000000b]">

                                <div class="flex items-center justify-center">

                                    <!-- Edit -->
                                    <a href="{{ route('payments.edit', $payment->id) }}"
                                        class="flex items-center mr-3 text-success">
                                        <x-base.lucide class="mr-1 h-4 w-4" icon="Pencil" /> Edit
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this payment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center text-danger">
                                            <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                        </button>
                                    </form>

                                </div>

                            </x-base.table.td>

                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="11" class="py-4 text-center text-slate-500">
                                No payments found.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>

            </x-base.table>
        </div>
        <!-- END: Data Table -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 mt-3 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
            <x-base.pagination class="w-full sm:mr-auto sm:w-auto">
                {{ $payments->links() }}
            </x-base.pagination>
        </div>
        <!-- END: Pagination -->

    </div>


@endsection
@section('third_party_scripts')
    <script type="text/javascript">
        $(function() {
            $('div.alert').not('.alert-danger').delay(3000).slideUp(300);
            $("input[name='from_date']").change(function() {
                $("input[name='to_date']").attr('min',$(this).val());
            });
        });
    </script>
@endsection


