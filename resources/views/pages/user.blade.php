@extends('../layouts/' . $layout)

@section('subhead')
    <title>Users - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Users</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
            @if(auth()->check() && auth()->user()->hasPermission('create-users'))
                <a href="{{ route('users.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add New User
                    </x-base.button>
                </a>
            @endif 
            <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto md:ml-0">
                <form method="GET" action="{{ route('users') }}" class="relative w-56">
                    <x-base.form-input 
                        class="!box w-56 pr-10" 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, email, contact..." 
                    />
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center justify-center w-10 h-full text-slate-500 hover:text-primary">
                        <x-base.lucide class="h-4 w-4" icon="Search" />
                    </button>
                </form>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">
                            Photo
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">
                            User Name
                        </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Email </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0"> Contact No. </x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                            STATUS
                        </x-base.table.th>
                        @if(auth()->check() && (auth()->user()->hasPermission('edit-users') || auth()->user()->hasPermission('delete-users')))
                            <x-base.table.th class="whitespace-nowrap border-b-0 text-center">
                                ACTIONS
                            </x-base.table.th>
                        @endif
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody id="users-tbody">
                    @isset($users)
                        @foreach ($users->take(20) as $user)
                            <x-base.table.tr class="intro-x">
                                <x-base.table.td class="w-20 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="flex justify-center">
                                        @if($user->photo)
                                            <img src="{{ url('uploads/user/' . $user->photo) }}" alt="User Photo" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <img src="{{ url('uploads/logo.png') }}" alt="Default Photo" class="h-10 w-10 rounded-full object-cover">
                                        @endif
                                    </div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="w-40 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="flex">
                                        <div class="whitespace-nowrap font-medium">{{ $user->name }}</div>
                                    </div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $user->email }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">{{ $user->contact_number ?? '-' }}</div>
                                </x-base.table.td>
                                <x-base.table.td
                                    class="w-40 border-b-0 bg-white shadow-[20px_3px_20px_#0000000b] first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600">
                                    <div class="flex items-center justify-center {{ $user->active ? 'text-success' : 'text-danger' }}">
                                        <x-base.lucide class="mr-2 h-4 w-4" icon="CheckSquare" /> {{ $user->active ? 'Active' : 'Inactive' }}
                                    </div>
                                </x-base.table.td>
                                @if(auth()->check() && (auth()->user()->hasPermission('edit-users') || auth()->user()->hasPermission('delete-users')))
                                    <x-base.table.td class="relative w-56 border-b-0 bg-white py-0 shadow-[20px_3px_20px_#0000000b] before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 first:rounded-l-md last:rounded-r-md dark:bg-darkmode-600 before:dark:bg-darkmode-400">
                                        <div class="flex items-center justify-center">
                                            @if(auth()->check() && auth()->user()->hasPermission('edit-users'))
                                                <a class="mr-3 flex items-center" href="{{ route('users.edit', $user->id) }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="CheckSquare" />
                                                    Edit
                                                </a>
                                            @endif
                                            @if(auth()->check() && auth()->user()->hasPermission('delete-users'))
                                                <a class="flex items-center text-danger" data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal" href="#"
                                                    data-delete-route="{{ route('users.delete', $user->id) }}"
                                                    data-delete-name="{{ $user->name }}">
                                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Trash" /> Delete
                                                </a>
                                            @endif
                                        </div>
                                    </x-base.table.td>
                                @endif
                            </x-base.table.tr>
                        @endforeach
                    @endisset
                </x-base.table.tbody>
            </x-base.table>
        </div>
        <!-- END: Data List --> 
        <!-- END: Pagination -->
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteLinks = document.querySelectorAll('[data-delete-route]');
            const deleteForm = document.getElementById('delete-user-form');
            const deleteUserName = document.getElementById('delete-user-name');
            
            deleteLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const route = this.getAttribute('data-delete-route');
                    const name = this.getAttribute('data-delete-name');
                    
                    deleteForm.setAttribute('action', route);
                    deleteUserName.textContent = name;
                });
            });
        });
    </script>
@endpush