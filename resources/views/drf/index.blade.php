@extends('../layouts/' . $layout)

@section('subhead')
    <title>Client Rate Fixes - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Client Rate Fixes</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Filters -->
        <div class="intro-y col-span-12">
            <div class="box p-5">
                <form action="">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Client Name</x-base.form-label>
                            <x-base.form-select name="dealer" class="form-control">
                                <option value="0">ALL</option>
                                @if (!empty($clients))
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            @if ($client->id == request()->input('client')) selected @endif>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <div class="col-span-12 sm:col-span-3">
                            <x-base.form-label>From Date</x-base.form-label>
                            <x-base.form-input type="date" name="from_date" max="{{ now()->format('Y-m-d') }}"
                                value="{{ request()->input('from_date', now()->format('Y-m-d')) }}" required />
                        </div>

                        <div class="col-span-12 sm:col-span-3">
                            <x-base.form-label>To Date</x-base.form-label>
                            <x-base.form-input type="date" name="to_date" max="{{ now()->format('Y-m-d') }}"
                                value="{{ request()->input('to_date', now()->format('Y-m-d')) }}" required />
                        </div>

                        <div class="col-span-12 sm:col-span-2 flex items-end gap-2">
                            <x-base.button type="submit" variant="primary">Search</x-base.button>
                            <a href="{{ route('drfs.index') }}">
                                <x-base.button type="button" variant="outline-secondary">
                                    Clear
                                </x-base.button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- END: Filters -->

        <!-- BEGIN: Header Actions -->
        @if (auth()->check() && auth()->user()->hasPermission('create-dealer-rate-fixes'))
            <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
                <a href="{{ route('drfs.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add Client Rate Fix
                    </x-base.button>
                </a>
            </div>
        @endif
        <!-- END: Header Actions -->

        <!-- BEGIN: Success/Error Messages -->
        @if (session('success_message'))
            <div class="intro-y col-span-12">
                <div class="alert alert-success">{{ session('success_message') }}</div>
            </div>
        @endif
        @if (Session::has('error_message'))
            <div class="intro-y col-span-12">
                <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
            </div>
        @endif
        <!-- END: Success/Error Messages -->

        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Client Name</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Date</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Quantity</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Rate</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Amount</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Fixed By</x-base.table.th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-dealer-rate-fixes') || auth()->user()->hasPermission('delete-dealer-rate-fixes')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody>
                    @forelse ($drfs as $index => $drf)
                        <x-base.table.tr class="intro-x">

                            {{-- Row ID --}}
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $drfs->firstItem() + $index }}
                            </x-base.table.td>

                            {{-- Client Name --}}
                            <x-base.table.td class="border-b-0 bg-white font-medium shadow-[20px_3px_20px_#0000000b]">
                                {{ $drf->client->name ?? '-'}}
                            </x-base.table.td>

                            {{-- Date --}}
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ \Carbon\Carbon::parse($drf->drf_date)->format('d/m/Y') }}
                            </x-base.table.td>

                            {{-- Quantity --}}
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $drf->quantity }}
                            </x-base.table.td>

                            {{-- Rate --}}
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $drf->rate }}
                            </x-base.table.td>

                            {{-- Amount --}}
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $drf->amount }}
                            </x-base.table.td>

                            {{-- Fixed By --}}
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $drf->fixedBy->name }}
                            </x-base.table.td>
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-dealer-rate-fixes') || auth()->user()->hasPermission('delete-dealer-rate-fixes')))
                                {{-- Actions --}}
                                <x-base.table.td
                                    class="relative border-b-0 bg-white py-0 text-center shadow-[20px_3px_20px_#0000000b]">

                                    <div class="flex items-center justify-center">
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-dealer-rate-fixes'))
                                            {{-- Edit --}}
                                            <a href="{{ route('drfs.edit', $drf->id) }}"
                                                class="flex items-center mr-3 text-success">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" /> Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-dealer-rate-fixes'))
                                            {{-- Delete --}}
                                            <form action="{{ route('drfs.destroy', $drf->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this dealer rate fix?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center text-danger">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                                </button>
                                            </form>
                                        @endif

                                    </div>

                                </x-base.table.td>
                            @endif

                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="8" class="text-center text-slate-500 py-4">
                                No dealer rate fixes found.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>

                <!-- Footer with Totals -->
                @if ($drfs->count() > 0)
                    <tfoot>
                        <tr class="bg-gray-50 text-sm text-gray-700 font-semibold">
                            <td colspan="3" class="py-4 text-center border-t border-gray-200 rounded-bl-xl">
                                Total
                            </td>

                            <td class="px-5 py-3 border-t border-gray-200 text-red-900">
                                {{ $totals->total_quantity }}
                            </td>

                            <td class="px-5 py-3 border-t border-gray-200 text-red-900">
                                {{ $totals->total_quantity > 0 ? round($totals->total_amount / $totals->total_quantity, 2) : 0 }}
                            </td>

                            <td class="px-5 py-3 border-t border-gray-200 font-bold text-gray-900">
                                {{ $totals->total_amount }}
                            </td>

                            <td colspan="2" class="py-4 border-t border-gray-200 rounded-br-xl">
                            </td>
                        </tr>
                    </tfoot>
                @endif

            </x-base.table>
        </div>
        <!-- END: Data Table --> 

    </div>
@endsection

@section('third_party_scripts')
    <script>
        $(function() {
            $('div.alert').not('.alert-danger').delay(3000).slideUp(300);
            $("input[name='from_date']").change(function() {
                $("input[name='to_date']").attr('min', $(this).val());
            });
        });
    </script>
@endsection
