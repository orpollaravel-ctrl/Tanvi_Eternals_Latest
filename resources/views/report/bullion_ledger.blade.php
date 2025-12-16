@extends('../layouts/' . $layout)

@section('subhead')
    <title>Bullion Ledger Account - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Bullion Ledger Account</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Filters -->
        <div class="intro-y col-span-12">
            <div class="box p-5">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Filters
                            </button>
                        </h2>
                        <div class="card-body">
                            <form action="">
                                <div class="grid grid-cols-12 gap-4">

                                    <div class="col-span-12 sm:col-span-4">
                                        <x-base.form-label>Bullion Name</x-base.form-label>
                                        <x-base.form-select name="bullion">
                                            <option value="0">ALL</option>
                                            @if (!empty($bullions))
                                                @foreach ($bullions as $bullion)
                                                    <option value="{{ $bullion->id }}"
                                                        @if ($bullion->id == request()->input('bullion')) selected @endif>
                                                        {{ $bullion->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </x-base.form-select>
                                    </div>

                                    <div class="col-span-12 sm:col-span-3">
                                        <x-base.form-label>From Date</x-base.form-label>
                                        <x-base.form-input type="date" name="from_date"
                                            max="{{ now()->format('Y-m-d') }}"
                                            value="{{ request()->input('from_date', now()->format('Y-m-d')) }}" required />
                                    </div>

                                    <div class="col-span-12 sm:col-span-3">
                                        <x-base.form-label>To Date</x-base.form-label>
                                        <x-base.form-input type="date" name="to_date" max="{{ now()->format('Y-m-d') }}"
                                            value="{{ request()->input('to_date', now()->format('Y-m-d')) }}" required />
                                    </div>

                                    <div class="col-span-12 sm:col-span-2 flex items-end gap-2">
                                        <x-base.button type="submit" variant="primary">
                                            Search
                                        </x-base.button>

                                        <a href="{{ route('bullion_ledger') }}">
                                            <x-base.button type="button" variant="outline-danger">
                                                Clear
                                            </x-base.button>
                                        </a>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="box p-5">
                @if (count($data) > 0)
                    <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                        <x-base.table.thead>
                            <x-base.table.tr>
                                <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Date</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Bullion Name</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Quantity</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Rate</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Amount</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Payment</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Receipt Qty.</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Transaction Type</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Remarks</x-base.table.th>
                            </x-base.table.tr>
                        </x-base.table.thead>
                        <x-base.table.tbody>
                            @foreach ($data as $key => $deal)
                                <x-base.table.tr class="intro-x">
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ 1 + $key }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ Carbon\Carbon::parse($deal->date)->format('d/m/Y') }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->name }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->quantity }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->rate }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                         {{  ($deal->amount) }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                         {{ $deal->payment }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->receipt_qty }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->transaction }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->remark }}
                                    </x-base.table.td>
                                </x-base.table.tr>
                            @endforeach
                        </x-base.table.tbody>
                        <tfoot>

                            {{-- TOTAL --}}
                            <tr class="bg-slate-100 text-sm">
                                <td colspan="2" class="font-semibold text-center py-3">TOTAL</td>

                                <td class="font-semibold text-center">Quantity</td>
                                <td class="font-semibold text-gray-700">{{ $data->sum('quantity') }}</td>

                                <td class="font-semibold">Amount</td>
                                <td class="font-semibold"> {{ $data->sum('amount') }}</td>

                                <td class="font-semibold"> {{ $data->sum('payment') }}</td>
                                <td class="font-semibold text-gray-700">{{ $data->sum('receipt_qty') }}</td>

                                <td colspan="2"></td>
                            </tr>

                            {{-- SETTLEMENT --}}
                            <tr class="bg-slate-200 text-sm">
                                <td colspan="2" class="font-semibold text-center py-3">SETTLEMENT</td>

                                <td class="font-semibold text-center">Quantity</td>
                                <td class="font-semibold text-blue-700">
                                    {{ round($data->sum('quantity') - $data->sum('receipt_qty'), 2) }}
                                </td>

                                <td class="font-semibold">Amount</td>
                                <td class="font-semibold text-blue-700">
								{{ $data->sum('amount') - $data->sum('payment') }}
                                </td>

                                <td colspan="4"></td>
                            </tr>

                            {{-- OPENING --}}
                            <tr class="bg-slate-100 text-sm">
                                <td colspan="2" class="font-semibold text-center py-3">OPENING</td>

                                <td class="font-semibold text-center">Quantity</td>
                                <td class="font-semibold text-gray-700">
                                    {{ $opening->quantity ?? 0 }}
                                </td>

                                <td class="font-semibold">Amount</td>
                                <td class="font-semibold text-gray-700">
								{{ $opening->amount ?? 0 }}
                                </td>

                                <td colspan="4"></td>
                            </tr>

                            {{-- FINAL SETTLEMENT --}}
                            <tr class="bg-slate-200 text-sm">
                                <td colspan="2" class="font-semibold text-center py-3">FINAL SETTLEMENT</td>

                                <td class="font-semibold text-center">Quantity</td>
                                <td class="font-semibold text-green-700">
                                    {{ round(($opening->quantity ?? 0) + $data->sum('quantity') - $data->sum('receipt_qty'), 2) }}
                                </td>

                                <td class="font-semibold">Amount</td>
                                <td class="font-semibold text-green-700">
									{{($opening->amount ?? 0) + $data->sum('amount') - $data->sum('payment') }}
                                </td>

                                <td colspan="4"></td>
                            </tr>

                        </tfoot>

                    </x-base.table>
                @else
                    <p class="text-center text-slate-500">Ledger data not found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('third_party_scripts')
    <script src="{{ URL::asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 100
                }],
                dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': $('html').attr('dir') == 'rtl' ? '←' : '→',
                        'previous': $('html').attr('dir') == 'rtl' ? '→' : '←'
                    }
                },
                buttons: [
                    'copy', 'excel', 'pdf', 'print', 'colvis'
                ]
            });
            var table = $('#datatable').DataTable();
            table.buttons().container().appendTo('#datatable-header');
            $("input[name='from_date']").change(function() {
                $("input[name='to_date']").attr('min', $(this).val());
            });
        });
    </script>
@endsection
