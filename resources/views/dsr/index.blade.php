@extends('../layouts/' . $layout)

@section('subhead')
    <title>DSR - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">DSR</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex items-center justify-between">
            @if (auth()->user()->hasPermission('create-dsr'))
                <a href="{{ route('dsr.create') }}">
                    <x-base.button variant="primary">Add DSR</x-base.button>
                </a>
            @endif
        </div>

        <div class="intro-y col-span-12 overflow-auto">
            <x-base.table class="mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Customer</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Contact</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Client Type</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">No. of Shops</x-base.table.th>
                        @if(auth()->check() && (auth()->user()->hasPermission('edit-dsr') || auth()->user()->hasPermission('delete-dsr')))
                            <x-base.table.th class="text-center whitespace-nowrap border-b-0">Actions</x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody>
                    @forelse($dsrs as $dsr)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">{{ $dsr->client->name }}</x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">{{ $dsr->client->mobile_number }}</x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">{{ $dsr->client_type }}</x-base.table.td>
                            <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">{{ $dsr->no_of_shops ?? '-' }}</x-base.table.td>
                            @if (auth()->check() && (auth()->user()->hasPermission('edit-dsr') || auth()->user()->hasPermission('delete-dsr')))
                                <x-base.table.td class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
                                    <div class="flex items-center justify-center">
                                        @if (auth()->check() && auth()->user()->hasPermission('edit-dsr'))
                                            <a class="mr-3 flex items-center" href="{{ route('dsr.edit', $dsr->id) }}">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                Edit
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->hasPermission('delete-dsr'))
                                            <a class="flex items-center text-danger" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal" href="#"
                                                data-delete-route="{{ route('dsr.destroy', $dsr->id) }}"
                                                data-delete-name="Expense">
                                                <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                            </a>
                                        @endif 
                                    </div>
                                </x-base.table.td>
                            @endif
                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="5" class="text-center text-slate-500">
                                No DSR found
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>
    </div>
    <!-- BEGIN: Delete Confirmation Modal -->
    <x-base.dialog id="delete-confirmation-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
                <div class="mt-5 text-3xl">Are you sure?</div>
                <div class="mt-2 text-slate-500">
                    Do you really want to delete this expense?
                    <br />This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <form id="delete-expense-form" method="POST" action="" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button" variant="outline-secondary">
                        Cancel
                    </x-base.button>
                    <x-base.button class="w-24" type="submit" variant="danger">
                        Delete
                    </x-base.button>
                </form>
            </div>
        </x-base.dialog.panel>
    </x-base.dialog>
    <!-- END: Delete Confirmation Modal -->

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteButtons = document.querySelectorAll('[data-delete-route]');
                const deleteForm = document.getElementById('delete-expense-form');

                deleteButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const route = this.getAttribute('data-delete-route');
                        if (deleteForm && route) {
                            deleteForm.setAttribute('action', route);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
