@extends('../layouts/' . $layout)

@section('subhead')
    <title>Department List - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Department List</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Header Actions -->
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            @if (auth()->check() && auth()->user()->hasPermission('create-departments'))
                <a href="{{ route('departments.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New Department
                    </x-base.button>
                </a>
            @endif
            {{-- 
            <x-base.menu>
                <x-base.menu.button class="!box px-2" as="x-base.button">
                    <span class="flex h-5 w-5 items-center justify-center">
                        <x-base.lucide class="h-4 w-4" icon="Plus" />
                    </span>
                </x-base.menu.button>
                <x-base.menu.items class="w-40">
                    <x-base.menu.item>
                        <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                    </x-base.menu.item>
                    <x-base.menu.item>
                        <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to Excel
                    </x-base.menu.item>
                    <x-base.menu.item>
                        <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to PDF
                    </x-base.menu.item>
                </x-base.menu.items>
            </x-base.menu> --}}
            {{-- 
            <div class="mx-auto hidden text-slate-500 md:block">
                Showing {{ $departments->firstItem() ?? 0 }} to {{ $departments->lastItem() ?? 0 }} of {{ $departments->total() ?? 0 }} entries
            </div> --}}

            {{-- <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto md:ml-0">
                <div class="relative w-56 text-slate-500">
                    <form method="GET" action="{{ route('departments.index') }}">
                        <x-base.form-input
                            class="!box w-56 pr-10"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search..."
                        />
                        <x-base.lucide class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4" icon="Search" />
                    </form>
                </div>
            </div> --}}
        </div>
        <!-- END: Header Actions -->

        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">#</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Name</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Code</x-base.table.th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-departments') || auth()->user()->hasPermission('delete-departments')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody id="departments-tbody">
                    @forelse ($departments->take(20) as $department)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td
                                class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md">
                                {{ $loop->iteration }}
                            </x-base.table.td>

                            <x-base.table.td
                                class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] font-medium">
                                {{ $department->name }}
                            </x-base.table.td>

                            <x-base.table.td
                                class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $department->code ?? '-' }}
                            </x-base.table.td>
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-departments') || auth()->user()->hasPermission('delete-departments')))
                                <x-base.table.td
                                    class="relative border-b-0 bg-white py-0 dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] text-center first:rounded-l-md last:rounded-r-md before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400">
                                    <div class="flex items-center justify-center">
                                        <!-- View -->
                                       
                                            <a href="{{ route('departments.show', $department->id) }}"
                                                class="flex items-center mr-3 text-primary">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Eye" /> View
                                            </a>
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-departments'))
                                            <!-- Edit -->
                                            <a href="{{ route('departments.edit', $department->id) }}"
                                                class="flex items-center mr-3 text-success">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" /> Edit
                                            </a>
                                        @endif
                                        <!-- Delete -->
                                         @if (auth()->check() && auth()->user()->hasPermission('delete-departments'))
                                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this department?')">
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
                            <x-base.table.td colspan="4" class="text-center text-slate-500 py-4">
                                No departments found.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>
        <!-- END: Data Table -->


    </div>

    <!-- BEGIN: Delete Confirmation Modal -->
    <x-base.dialog id="delete-confirmation-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
                <div class="mt-5 text-3xl">Are you sure?</div>
                <div class="mt-2 text-slate-500">
                    Do you really want to delete this department?<br />
                    This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">
                    Cancel
                </x-base.button>
                <x-base.button class="w-24" type="button" variant="danger">
                    Delete
                </x-base.button>
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: Delete Confirmation Modal -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tbody = document.getElementById('departments-tbody');
                let allDepartments = @json($departments);
                let displayedCount = 20;

                function loadMoreDepartments() {
                    if (displayedCount >= allDepartments.length) return;
                    
                    const nextBatch = allDepartments.slice(displayedCount, displayedCount + 20);
                    nextBatch.forEach((department, index) => {
                        const row = `<tr class="intro-x">
                            <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md">
                                ${displayedCount + index + 1}
                            </td>
                            <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] font-medium">
                                ${department.name}
                            </td>
                            <td class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                ${department.code || '-'}
                            </td>
                            <td class="relative border-b-0 bg-white py-0 dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] text-center first:rounded-l-md last:rounded-r-md">
                                <div class="flex items-center justify-center">
                                    <a href="/departments/${department.id}" class="flex items-center mr-3 text-primary">
                                        <i class="mr-1 h-4 w-4"></i> View
                                    </a>
                                    <a href="/departments/${department.id}/edit" class="flex items-center mr-3 text-success">
                                        <i class="mr-1 h-4 w-4"></i> Edit
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                    displayedCount += 20;
                }

                window.addEventListener('scroll', function() {
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
                        loadMoreDepartments();
                    }
                });
            });
        </script>
    @endpush
@endsection
