@extends('../layouts/' . $layout)

@section('subhead')
    <title>Payment Modes - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Payment Modes</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">

        <!-- BEGIN: Header Actions -->
        @if (auth()->check() && auth()->user()->hasPermission('create-payment-modes'))
            <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
                <a href="{{ route('paymentmodes.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add Payment Mode
                    </x-base.button>
                </a>
            </div>
        @endif
        <!-- END: Header Actions -->

        <!-- BEGIN: Success & Error Messages -->
        <div class="intro-y col-span-12">
            @if (session('success_message'))
                <div class="alert alert-success mb-4">{{ session('success_message') }}</div>
            @endif

            @if (session('error_message'))
                <div class="alert alert-danger mb-4">{{ session('error_message') }}</div>
            @endif
        </div>
        <!-- END: Success & Error Messages -->


        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th>#</x-base.table.th>
                        <x-base.table.th>Name</x-base.table.th>
                        <x-base.table.th>Created At</x-base.table.th>
                        @if (auth()->check() && (auth()->user()->hasPermission('edit-payment-modes') || auth()->user()->hasPermission('delete-payment-modes')))
                            <x-base.table.th class="text-center">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody>
                    @forelse ($paymentmodes as $key => $pm)
                        <x-base.table.tr class="intro-x">

                            <!-- SR NO -->
                            <x-base.table.td
                                class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] first:rounded-l-md">
                                {{ $paymentmodes->firstItem() + $key }}
                            </x-base.table.td>

                            <!-- NAME -->
                            <x-base.table.td
                                class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] font-medium">
                                {{ $pm->name }}
                            </x-base.table.td>

                            <!-- CREATED AT -->
                            <x-base.table.td
                                class="border-b-0 bg-white dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b]">
                                {{ $pm->created_at->diffForHumans() }}
                            </x-base.table.td>

                            <!-- ACTIONS -->
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-payment-modes') || auth()->user()->hasPermission('delete-payment-modes')))
                                <x-base.table.td
                                    class="relative border-b-0 bg-white py-0 text-center dark:bg-darkmode-600 shadow-[20px_3px_20px_#0000000b] last:rounded-r-md">

                                    <div class="flex items-center justify-center">
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-payment-modes'))
                                            <!-- Edit -->
                                            <a href="{{ route('paymentmodes.edit', $pm->id) }}"
                                                class="flex items-center mr-3 text-success">
                                                <x-base.lucide icon="CheckSquare" class="mr-1 h-4 w-4" />
                                                Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-payment-modes'))
                                            <!-- Delete -->
                                            <button class="flex items-center text-danger delete-btn"
                                                data-id="{{ $pm->id }}" data-name="{{ $pm->name }}"
                                                data-has-records="{{ $pm->payments_count > 0 ? '1' : '0' }}">
                                                <x-base.lucide icon="Trash" class="mr-1 h-4 w-4" />
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </x-base.table.td>
                            @endif

                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="4" class="text-center text-slate-500 py-4">
                                No payment modes found.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>
        <!-- END: Data Table -->

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
            <x-base.pagination class="w-full sm:mr-auto sm:w-auto">
                {{ $paymentmodes->links() }}
            </x-base.pagination>
        </div>
        <!-- END: Pagination -->

    </div>

    <!-- BEGIN: Delete Confirmation Modal -->
    <x-base.dialog id="delete-confirm-modal">
        <x-base.dialog.panel>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')

                <div class="p-5 text-center">
                    <x-base.lucide icon="XCircle" class="mx-auto mt-3 h-16 w-16 text-danger" />
                    <div class="mt-5 text-3xl">Are you sure?</div>
                    <div class="mt-2 text-slate-500">
                        Do you really want to delete <span class="font-bold" id="deleteName"></span>?
                        <br>This action cannot be undone.
                    </div>
                </div>

                <div class="px-5 pb-8 text-center">
                    <x-base.button variant="outline-secondary" class="mr-1 w-24"
                        data-tw-dismiss="modal">Cancel</x-base.button>
                    <x-base.button variant="danger" class="w-24" type="submit">Delete</x-base.button>
                </div>
            </form>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: Delete Confirmation Modal -->

    <!-- BEGIN: Info Modal (Record Exists) -->
    <x-base.dialog id="info-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide icon="AlertTriangle" class="mx-auto mt-3 h-16 w-16 text-warning" />
                <div class="mt-5 text-2xl font-semibold">Cannot Delete</div>
                <div class="mt-2 text-slate-500">
                    <span id="infoName"></span> cannot be deleted because it has related records.
                </div>

                <div class="px-5 pb-8 text-center mt-5">
                    <x-base.button data-tw-dismiss="modal" variant="primary">OK</x-base.button>
                </div>
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: Info Modal -->

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete click
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let hasRecords = this.dataset.hasRecords;
                    let id = this.dataset.id;
                    let name = this.dataset.name;

                    if (hasRecords == '1') {
                        // Show info modal
                        document.getElementById('infoName').innerHTML = name;
                        tailwind.Modal.getOrCreateInstance(document.getElementById('info-modal')).show();
                        return;
                    }

                    // Setup delete form
                    document.getElementById('deleteName').innerHTML = name;
                    let form = document.getElementById('deleteForm');
                    form.action = "{{ url('master/paymentmodes') }}/" + id;

                    tailwind.Modal.getOrCreateInstance(document.getElementById('delete-confirm-modal')).show();
                });
            });
        });
    </script>
@endpush
