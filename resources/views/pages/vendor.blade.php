@extends('../layouts/' . $layout)

@section('subhead')
    <title>Vendor - Midone - Tailwind HTML Admin Template</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Vendor</h2>
    
    {{-- Success Message --}}
    @if (session('success'))
        <div class="mt-5 rounded-md border border-success/20 bg-success/10 p-4 text-success dark:border-success/30">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            @if(auth()->check() && auth()->user()->hasPermission('create-vendors'))
                <a href="{{ route('vendor.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New Vendor
                    </x-base.button>
                </a>
            @endif
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
            </x-base.menu> 
            <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto">
                <div class="relative w-56">
                    <x-base.form-input class="!box w-56 pr-10" type="text" placeholder="Search..." />
                    <x-base.lucide class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4 text-slate-500" icon="Search" />
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Name</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Code</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Contact No.</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Email</x-base.table.th>
                        @if(auth()->check() && (auth()->user()->hasPermission('edit-vendors') || auth()->user()->hasPermission('delete-vendors')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                                ACTIONS
                            </x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody id="vendors-tbody">
                    @isset($vendors)
                        @forelse ($vendors->take(20) as $vendor)
                            <x-base.table.tr class="intro-x">
                                <x-base.table.td
                                    class="w-40 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="flex">
                                        <div class="whitespace-nowrap font-medium">{{ $vendor->name }}</div>
                                    </div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $vendor->code ?? 'N/A' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $vendor->contact_no ?? 'N/A' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $vendor->email ?? 'N/A' }}</div>
                                </x-base.table.td>
                                @if(auth()->check() && (auth()->user()->hasPermission('edit-vendors') || auth()->user()->hasPermission('delete-vendors')))
                                    <x-base.table.td
                                        class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            @if(auth()->check() && auth()->user()->hasPermission('edit-vendors'))
                                                <a class="mr-3 flex items-center" href="{{ route('vendor.edit', $vendor->id) }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                    Edit
                                                </a>
                                            @endif
                                            @if(auth()->check() && auth()->user()->hasPermission('edit-vendors'))
                                                <a class="flex items-center text-danger" data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal" href="#"
                                                    data-delete-route="{{ route('vendor.destroy', $vendor->id) }}"
                                                    data-delete-name="{{ $vendor->name ?? 'Vendor #' . $vendor->id }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                                </a>
                                            @endif
                                        </div>
                                    </x-base.table.td>
                                @endif
                            </x-base.table.tr>
                        @empty
                            <x-base.table.tr>
                                <x-base.table.td colspan="10" class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="py-8 text-slate-500">No Vendor records found.</div>
                                </x-base.table.td>
                            </x-base.table.tr>
                        @endforelse
                    @else
                        <x-base.table.tr>
                            <x-base.table.td colspan="10" class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                <div class="py-8 text-slate-500">No Vendor records found.</div>
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endisset
                </x-base.table.tbody>
            </x-base.table>
        </div>
        <!-- END: Data List -->

    </div>
    <!-- BEGIN: Delete Confirmation Modal -->
    <x-base.dialog id="delete-confirmation-modal">
        <x-base.dialog.panel>
            <div class="p-5 text-center">
                <x-base.lucide class="mx-auto mt-3 h-16 w-16 text-danger" icon="XCircle" />
                <div class="mt-5 text-3xl">Are you sure?</div>
                <div class="mt-2 text-slate-500">
                    Do you really want to delete <span class="font-medium" id="delete-user-name"></span>?
                    <br />This action cannot be undone.
                </div>
            </div>
            <div class="px-5 pb-8 text-center">
                <form id="delete-user-form" method="POST" action="" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-base.button class="mr-1 w-24" data-tw-dismiss="modal" type="button"
                        variant="outline-secondary">
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
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('[data-delete-route]');
                const deleteForm = document.getElementById('delete-user-form');
                const deleteUserName = document.getElementById('delete-user-name');
                const tbody = document.getElementById('vendors-tbody');
                let allVendors = @json($vendors);
                let displayedCount = 20;

                function loadMoreVendors() {
                    if (displayedCount >= allVendors.length) return;
                    
                    const nextBatch = allVendors.slice(displayedCount, displayedCount + 20);
                    nextBatch.forEach(vendor => {
                        const row = `<tr class="intro-x">
                            <td class="w-40 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="flex"><div class="whitespace-nowrap font-medium">${vendor.name}</div></div>
                            </td>
                            <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${vendor.code || 'N/A'}</div>
                            </td>
                            <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${vendor.contact_no || 'N/A'}</div>
                            </td>
                            <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${vendor.email || 'N/A'}</div>
                            </td>
                        </tr>`;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                    displayedCount += 20;
                }

                window.addEventListener('scroll', function() {
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
                        loadMoreVendors();
                    }
                });

                deleteButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        const route = this.getAttribute('data-delete-route');
                        const name = this.getAttribute('data-delete-name');

                        if (deleteForm && route) {
                            deleteForm.setAttribute('action', route);
                        }

                        if (deleteUserName) {
                            deleteUserName.textContent = name || 'this purchase';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
