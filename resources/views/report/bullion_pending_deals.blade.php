@extends('../layouts/' . $layout)

@section('subhead')
    <title>Bullion Pending Deals - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Bullion Pending Deals</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="box p-5">
                @if (count($deals) > 0)
                    <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                        <x-base.table.thead>
                            <x-base.table.tr>
                                <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Date</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Bullion Name</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Fixed By</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Req. Quantity</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Req. Rate</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Amount</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Balance Qty.</x-base.table.th>
                                <x-base.table.th class="whitespace-nowrap border-b-0">Remarks</x-base.table.th>
                            </x-base.table.tr>
                        </x-base.table.thead>
                        <x-base.table.tbody>
                            @foreach ($deals as $key => $deal)
                                <x-base.table.tr class="intro-x">
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->id }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ \Carbon\Carbon::parse($deal->brf_date)->format('d/m/Y') }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->bullion->name }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->fixedBy->name }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->quantity }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->rate }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->amount }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ number_format($deal->pending, 3) }}
                                    </x-base.table.td>
                                    <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                        {{ $deal->remark }}
                                    </x-base.table.td>
                                </x-base.table.tr>
                            @endforeach
                        </x-base.table.tbody>
                        <tfoot>
                            <tr class="bg-slate-100 text-sm">
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->count() }}</td>
                                <td colspan="3" class="border-b-0 bg-slate-100 font-semibold text-center py-3">Total</td>
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->sum('quantity') }}</td>
                                @php $avg = round(10 * ($deals->sum('amount') / $deals->sum('quantity')), 3); @endphp
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $avg }}</td>
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->sum('amount') }}</td>
                                <td class="border-b-0 bg-slate-100 font-semibold">{{ $deals->sum('pending') }}</td>
                                <td class="border-b-0 bg-slate-100"></td>
                            </tr>
                        </tfoot>
                    </x-base.table>
                @else
                    <p class="text-center text-slate-500">Bullion pending deals not found.</p>
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
        });
    </script>
@endsection


