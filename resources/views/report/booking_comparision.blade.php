@extends('../layouts/' . $layout)

@section('subhead')
    <title>Booking Comparision - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Booking Comparision</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Filters -->
        <div class="intro-y col-span-12">
    <div class="box p-5">

        <!-- Direct Visible Filters -->
        <form action="">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-3">
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
                    <x-base.form-input
                        type="date"
                        name="from_date"
                        value="{{ request()->input('from_date', now()->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        required
                    />
                </div>

                <div class="col-span-12 sm:col-span-3">
                    <x-base.form-label>To Date</x-base.form-label>
                    <x-base.form-input
                        type="date"
                        name="to_date"
                        value="{{ request()->input('to_date', now()->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        required
                    />
                </div>

                <div class="col-span-12 sm:col-span-3 flex items-end gap-2">
                    <x-base.button type="submit" variant="primary">
                        Search
                    </x-base.button>

                    <a href="{{ route('booking_comparision') }}">
                        <x-base.button type="button" variant="outline-danger">
                            Clear
                        </x-base.button>
                    </a>
                </div>
            </div>
        </form>

    </div>
</div>


        <!-- Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="box p-5">
                @if (count($deals) > 0)
                    <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                        <x-base.table.thead>
                            <x-base.table.tr>
                                <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Date</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Client Name</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Req. Quantity</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Req. Rate</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Booked Date</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Bullion Name</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Booked Quantity</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Booked Rate</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Profit</x-base.table.th>
                            </x-base.table.tr>
                        </x-base.table.thead>
                        <x-base.table.tbody>
                            @foreach ($deals as $key => $deal)
                                <x-base.table.tr class="intro-x">
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ 1 + $key }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ \Carbon\Carbon::parse($deal->drf->drf_date)->format('d/m/Y') }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->drf->client->name }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->drf->quantity }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->drf->rate }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ \Carbon\Carbon::parse($deal->brf->brf_date)->format('d/m/Y') }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->brf->bullion->name }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->quantity }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->brf->rate }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ round($deal->quantity * 0.10 * ($deal->drf->rate - $deal->brf->rate), 3) }}
                                    </x-base.table.td>
                                </x-base.table.tr>
                            @endforeach
                        </x-base.table.tbody>
                        <tfoot>
                            <tr class="bg-slate-100 text-sm">
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->count() }}</td>
                                <td colspan="2" class="border-b-0 bg-slate-100 font-semibold text-center py-3">Total</td>
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->sum(function($deal) { return $deal->drf->quantity; }) }}</td>
                                <td class="border-b-0 bg-slate-100"></td>
                                <td colspan="2" class="border-b-0 bg-slate-100"></td>
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->sum('quantity') }}</td>
                                <td class="border-b-0 bg-slate-100"></td>
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->sum(function($deal) { return round($deal->quantity * 0.10 * ($deal->drf->rate - $deal->brf->rate), 3); }) }}</td>
                            </tr>
                        </tfoot>
                    </x-base.table>
                @else
                    <p class="text-center text-slate-500">Booking comparision data not found.</p>
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
                $("input[name='to_date']").attr('min',$(this).val());
            });
        });
    </script>
@endsection


