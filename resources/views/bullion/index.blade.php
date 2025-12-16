@extends('../layouts/' . $layout)

@section('subhead')
    <title>Bullions - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Bullions</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">

        <!-- BEGIN: Header Actions -->
        @if (auth()->check() && auth()->user()->hasPermission('create-bullions'))
            <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
                <a href="{{ route('bullions.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add Bullion
                    </x-base.button>
                </a>
            </div>
        @endif
        <!-- END: Header Actions -->

        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">

                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Name</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Phone</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Created At</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Status</x-base.table.th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-bullions') || auth()->user()->hasPermission('delete-bullions')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody>
                    @forelse ($bullions as $index => $bullion)
                        <x-base.table.tr class="intro-x">

                            <!-- Row ID -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $bullions->firstItem() + $index }}
                            </x-base.table.td>

                            <!-- Name -->
                            <x-base.table.td class="border-b-0 bg-white font-semibold shadow-[20px_3px_20px_#0000000b]">
                                {{ $bullion->name }}
                            </x-base.table.td>

                            <!-- Phone -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $bullion->phone }}
                            </x-base.table.td>

                            <!-- Created At -->
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                {{ $bullion->created_at->diffForHumans() }}
                            </x-base.table.td>

                            <!-- Status -->
                            <x-base.table.td class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b]">
                                @if ($bullion->status)
                                    <span class="px-2 py-1 rounded bg-success/20 text-success text-xs">Active</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-danger/20 text-danger text-xs">Inactive</span>
                                @endif
                            </x-base.table.td>

                            <!-- Actions -->
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-bullions') || auth()->user()->hasPermission('delete-bullions')))
                                <x-base.table.td
                                    class="relative border-b-0 bg-white py-0 text-center shadow-[20px_3px_20px_#0000000b]">

                                    <div class="flex items-center justify-center">
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-bullions'))
                                            <!-- Edit -->
                                            <a href="{{ route('bullions.edit', $bullion->id) }}"
                                                class="flex items-center mr-3 text-success">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Pencil" /> Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-bullions'))
                                            <!-- Delete -->
                                            <form action="{{ route('bullions.destroy', $bullion->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this bullion?')">
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
                            <x-base.table.td colspan="6" class="py-4 text-center text-slate-500">
                                No bullions found.
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
                {{ $bullions->links() }}
            </x-base.pagination>

            <x-base.form-select class="!box mt-3 w-20 sm:mt-0">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </x-base.form-select>

        </div>
        <!-- END: Pagination -->

    </div>
@endsection
