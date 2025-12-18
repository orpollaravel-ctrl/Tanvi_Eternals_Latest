@extends('../layouts/' . $layout)

@section('subhead')
    <title>Bullion Rate Fixes - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Bullion Rate Fixes</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">

        <!-- BEGIN: Filters -->
        <div class="intro-y col-span-12">
            <div class="box p-5">
                <h3 class="text-lg font-medium mb-4">Filters</h3>
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
                                            {{ $bullion->name }}</option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <x-base.form-label>From Date</x-base.form-label>
                            <x-base.form-input type="date" name="from_date"
                                value="{{ request()->input('from_date', now()->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}" required />
                        </div>
                        <div class="col-span-12 sm:col-span-3">
                            <x-base.form-label>To Date</x-base.form-label>
                            <x-base.form-input type="date" name="to_date"
                                value="{{ request()->input('to_date', now()->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}" required />
                        </div>
                        <div class="col-span-12 sm:col-span-2 flex items-end gap-2">
                            <x-base.button type="submit" variant="primary">Search</x-base.button>
                            <a href="{{ route('brfs.index') }}">
                                <x-base.button type="button" variant="outline-secondary">Clear</x-base.button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END: Filters -->

        <!-- BEGIN: Header Actions -->
        @if (auth()->check() && auth()->user()->hasPermission('create-bullion-rate-fixes'))
            <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
                <a href="{{ route('brfs.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add Bullion Rate Fix
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
                        <x-base.table.th class="whitespace-nowrap border-b-0">Name</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Date</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Quantity</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Rate</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Amount</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Fixed By</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Created By</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Updated By</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Created At</x-base.table.th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-bullion-rate-fixes') || auth()->user()->hasPermission('delete-bullion-rate-fixes')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody>
                    @forelse ($brfs as $index => $brf)
                        <x-base.table.tr class="intro-x">

                            <!-- Row ID -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $brfs->firstItem() + $index }}
                            </x-base.table.td>

                            <!-- Name -->
                            <x-base.table.td class="border-b-0 bg-white font-semibold shadow-[20px_3px_20px_#0000000b]">
                                {{ $brf->bullion->name }}
                            </x-base.table.td>

                            <!-- Date -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ \Carbon\Carbon::parse($brf->brf_date)->format('d/m/Y') }}
                            </x-base.table.td>

                            <!-- Quantity -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $brf->quantity }}
                            </x-base.table.td>

                            <!-- Rate -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $brf->rate }}
                            </x-base.table.td>

                            <!-- Amount -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $brf->amount }}
                            </x-base.table.td>

                            <!-- Fixed By -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $brf->fixedBy->name }}
                            </x-base.table.td>

                            <!-- Created By -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $brf->createdBy->name }}
                            </x-base.table.td>

                            <!-- Updated By -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                @if ($brf->updatedBy)
                                    {{ $brf->updatedBy->name }} @endif
                            </x-base.table.td>

                            <!-- Created At -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $brf->created_at->diffForHumans() }}
                            </x-base.table.td>

                            <!-- Actions -->
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-bullion-rate-fixes') || auth()->user()->hasPermission('delete-bullion-rate-fixes')))
                                <x-base.table.td
                                    class="relative border-b-0 bg-white py-0 text-center shadow-[20px_3px_20px_#0000000b]">
                                    <div class="flex items-center justify-center gap-2">
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-bullion-rate-fixes'))
                                            <a href="{{ route('brfs.edit', $brf->id) }}"
                                                class="flex items-center text-success">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Pencil" /> Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-bullion-rate-fixes'))
                                            <form action="{{ route('brfs.destroy', $brf->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this bullion rate fix?')">
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
                            <x-base.table.td colspan="11" class="py-4 text-center text-slate-500">
                                No bullion rate fixes found.
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
                {{ $brfs->links() }}
            </x-base.pagination>
        </div>
        <!-- END: Pagination -->

    </div>
@endsection
