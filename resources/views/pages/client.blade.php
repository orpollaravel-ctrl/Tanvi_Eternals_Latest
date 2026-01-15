@extends('../layouts/' . $layout)

@section('subhead')
    <title>Client - Midone - Tailwind HTML Admin Template</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Client</h2>
    
    {{-- Success Message --}}
    @if (session('success'))
        <div class="mt-5 rounded-md border border-success/20 bg-success/10 p-4 text-success dark:border-success/30">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-5 grid grid-cols-8 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            @if(auth()->check() && auth()->user()->hasPermission('create-clients'))
                <a href="{{ route('client.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New Client
                    </x-base.button>
                </a>
                <x-base.button
                        class="mr-2"
                        variant="outline-primary"
                        data-tw-toggle="modal"
                        data-tw-target="#import-client-modal">
                        Import Excel
                    </x-base.button>
            @endif
            {{-- <x-base.menu>
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
            </x-base.menu>  --}} 
            <form method="GET" action="{{ route('client.index') }}">
                <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto">
                    <div class="relative w-56 text-slate-500">
                        <x-base.form-input
                            name="search"
                            value="{{ request('search') }}"
                            class="!box w-56 pr-10"
                            type="text"
                            placeholder="Search by name or code..."
                        />
                        <x-base.lucide
                            class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"
                            icon="Search"
                        />
                    </div>
                </div>
            </form>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Client Code </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Client Name </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Client Type </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Salesman </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Mobile </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> City </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> State </x-base.table.th>

                        @if(auth()->check() && (auth()->user()->hasPermission('edit-clients') || auth()->user()->hasPermission('delete-clients')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                                ACTIONS
                            </x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody id="clients-tbody">
                    @isset($clients)
                        @forelse ($clients as $client)
                            <x-base.table.tr class="intro-x">
                                <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="text-xs text-slate-500">{{ $client->code }}</div>
                                </x-base.table.td>

                                <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="text-xs text-slate-500">{{ $client->name }}</div>
                                </x-base.table.td>
                                <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="text-xs text-slate-500">{{ $client->client_type }}</div>
                                </x-base.table.td>
                                <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="text-xs text-slate-500">
                                        {{ $client->salesman->name ?? '-' }}
                                    </div>
                                </x-base.table.td>      
                                <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="text-xs text-slate-500">
                                        {{ $client->mobile_number ?? '-' }}
                                    </div>
                                </x-base.table.td>

                                <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="text-xs text-slate-500">
                                        {{ $client->city ?? '-' }}
                                    </div>
                                </x-base.table.td>

                                <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="text-xs text-slate-500">
                                        {{ $client->state ?? '-' }}
                                    </div>
                                </x-base.table.td>

                                @if(auth()->check() && (auth()->user()->hasPermission('edit-clients') || auth()->user()->hasPermission('delete-clients')))
                                    <x-base.table.td class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                        <div class="flex items-center justify-center">
                                            @if(auth()->user()->hasPermission('edit-clients'))
                                                <a class="mr-3 flex items-center" href="{{ route('client.edit', $client->id) }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                    Edit
                                                </a>
                                            @endif

                                            @if(auth()->user()->hasPermission('delete-clients'))
                                                <a class="flex items-center text-danger"
                                                data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal"
                                                href="#"
                                                data-delete-route="{{ route('client.destroy', $client->id) }}"
                                                data-delete-name="{{ $client->name }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" />
                                                    Delete
                                                </a>
                                            @endif
                                        </div>
                                    </x-base.table.td>
                                @endif

                            </x-base.table.tr>

                        @empty
                            <x-base.table.tr>
                                <x-base.table.td colspan="8" class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                    <div class="py-8 text-slate-500">No Client records found.</div>
                                </x-base.table.td>
                            </x-base.table.tr>
                        @endforelse
                    @else
                        <x-base.table.tr>
                            <x-base.table.td colspan="8" class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b] dark:bg-darkmode-600">
                                <div class="py-8 text-slate-500">No Client records found.</div>
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
    <x-base.dialog id="import-client-modal">
        <x-base.dialog.panel>
            <form action="{{ route('client.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-5">
                    <div class="text-lg font-medium">Import Clients</div>

                    <div class="mt-4">
                        <x-base.form-label>Excel File (.xlsx / .csv)</x-base.form-label>
                        <x-base.form-input type="file" name="file" required />
                    </div>

                    <div class="mt-3 text-slate-500 text-xs">
                        Download sample format:
                        <a href="{{ asset('uploads/excl/client_import_demo.xlsx') }}" class="text-primary underline">
                            Sample Excel
                        </a>
                    </div>
                </div>
                
                <div class="px-5 pb-5 text-right">
                    <x-base.button type="button" variant="outline-secondary" data-tw-dismiss="modal">
                        Cancel
                    </x-base.button>
                    <x-base.button type="submit" variant="primary">
                        Import
                    </x-base.button>
                </div>
            </form>
        </x-base.dialog.panel>
    </x-base.dialog> 

    <!-- END: Delete Confirmation Modal -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('[data-delete-route]');
                const deleteForm = document.getElementById('delete-user-form');
                const deleteUserName = document.getElementById('delete-user-name');
                const tbody = document.getElementById('clients-tbody');
                let allClients = @json($clients);
                let displayedCount = 20;

                function loadMoreClients() {
                    if (displayedCount >= allClients.length) return;
                    
                    const nextBatch = allClients.slice(displayedCount, displayedCount + 20);
                    nextBatch.forEach(client => {
                        const row = `<tr class="intro-x">
                            <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${client.code}</div>
                            </td>
                            <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">${client.name}</div>
                            </td>
                        </tr>`;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                    displayedCount += 20;
                }

                window.addEventListener('scroll', function() {
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
                        loadMoreClients();
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
                            deleteUserName.textContent = name || 'this user';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection

