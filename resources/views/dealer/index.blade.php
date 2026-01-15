@extends('../layouts/' . $layout)

@section('subhead')
    <title>Dealers - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Dealers</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">

        {{-- Success Message --}}
        @if (session('success_message'))
            <div class="intro-y col-span-12">
                <div
                    class="alert alert-success show mb-4 shadow-md rounded border border-success/60 bg-success/10 text-success py-4">
                    {{ session('success_message') }}
                </div>
            </div>
        @endif

        {{-- Validation Errors from import --}}
        @if (session('validation_errors') && count(session('validation_errors')) > 0)
            <div class="intro-y col-span-12">
                <div
                    class="alert alert-danger show mb-4 shadow-md rounded border border-danger/60 bg-danger/10 text-danger py-4">
                    <ul class="list-disc list-inside">
                        @foreach (session('validation_errors') as $error)
                            @php
                                $rowNo = $error['row'];
                                $email = $error['email'] ?? 'N/A';
                                $messages = $error['messages'];
                            @endphp
                            <li>
                                <strong>Row no:- {{ $rowNo }}, {{ $email }}:</strong>
                                <ul class="list-disc list-inside ml-5">
                                    @foreach ($messages as $msg)
                                        <li>{{ ucfirst($msg) }}</li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="intro-y col-span-12">
                <div
                    class="alert alert-danger show mb-4 shadow-md rounded border border-danger/60 bg-danger/10 text-danger py-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        <!-- BEGIN: Header Actions -->
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            @if (auth()->check() && auth()->user()->hasPermission('create-bullions'))
                <a href="{{ route('dealers.create') }}">
                    <x-base.button class="mr-2 shadow-md" variant="primary">
                        Add Dealer
                    </x-base.button>
                </a>
            @endif

            <!-- <form action="{{ route('dealers.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center">
                    @csrf
                    <input type="file" name="file" accept=".xls,.xlsx" required
                        class="mr-2 block w-60 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer" />
                    <x-base.button type="submit" class="shadow-md" variant="success">
                        Import Excel
                    </x-base.button>
                </form>
       -->
            <div class="mt-3 w-full sm:mt-0 sm:ml-auto sm:w-auto md:ml-0">
                <div class="relative w-56 text-slate-500">
                    <input type="text" id="dealer-search" class="form-control !box w-56 pr-10"
                        placeholder="Search dealers...">
                    <x-base.lucide class="absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4" icon="Search" />
                </div>
            </div>
        </div>
        <!-- END: Header Actions -->

        <!-- BEGIN: Data Table -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="-mt-2 border-separate border-spacing-y-[10px] w-full text-center">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap border-b-0">#</th>
                        <th class="whitespace-nowrap border-b-0">Name</th>
                        <th class="whitespace-nowrap border-b-0">Code</th>
                        <th class="whitespace-nowrap border-b-0">Email</th>
                        <th class="whitespace-nowrap border-b-0">Phone</th>
                        <th class="whitespace-nowrap border-b-0">Location</th>
                        <th class="whitespace-nowrap border-b-0">Pincode</th>
                        <th class="whitespace-nowrap border-b-0 text-center">Status</th>
                        @if (auth()->check() &&
                                (auth()->user()->hasPermission('edit-bullions') || auth()->user()->hasPermission('delete-bullions')))
                            <th class="whitespace-nowrap border-b-0 text-center">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="dealer-table-body">
                    {{-- Initially empty for ajax rendering --}}
                </tbody>
            </table>
        </div>
        <!-- END: Data Table -->

        <!-- Loading indicator -->
        <div id="loading-indicator" class="col-span-12 text-center py-4 hidden">
            <div class="inline-flex items-center">
                <x-base.loading-icon class="animate-spin h-5 w-5 mr-2" />
                Loading more dealers...
            </div>
        </div>

        {{-- Server side pagination removed for ajax pagination --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            let isLoading = false;
            let hasMorePages = true;
            let searchTimeout;

            const tableBody = document.getElementById('dealer-table-body');
            const loadingIndicator = document.getElementById('loading-indicator');
            const searchInput = document.getElementById('dealer-search');

            // Debounced search input handler
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentPage = 1;
                    hasMorePages = true;
                    loadDealers(true); // Reset and load fresh data
                }, 500);
            });

            // Initial load
            loadDealers();

            // Infinite Scroll
            window.addEventListener('scroll', function() {
                if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
                    if (!isLoading && hasMorePages) {
                        loadDealers();
                    }
                }
            });

            function loadDealers(reset = false) {
                if (isLoading) return;

                if (!tableBody) {
                    console.error('Dealer table body not found');
                    return;
                }

                isLoading = true;
                loadingIndicator.classList.remove('hidden');

                const search = searchInput.value;

                fetch(`{{ route('dealers.index') }}?page=${currentPage}&search=${encodeURIComponent(search)}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (reset) {
                            tableBody.innerHTML = '';
                        }

                        if (data.data.length > 0) {
                            data.data.forEach((dealer, index) => {
                                const rowNumber = (currentPage - 1) * 25 + index + 1;
                                const rowHtml = generateDealerRow(dealer, rowNumber);
                                tableBody.insertAdjacentHTML('beforeend', rowHtml);
                            });

                            currentPage++;
                            hasMorePages = data.has_more;
                        } else if (reset) {
                            tableBody.innerHTML =
                                '<tr><td colspan="9" class="text-center text-slate-500 py-4">No dealers found.</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading dealers:', error);
                    })
                    .finally(() => {
                        isLoading = false;
                        loadingIndicator.classList.add('hidden');
                    });
            }

            function generateDealerRow(dealer, rowNumber) {
                const statusBadge = dealer.status ?
                    `<span class="px-2 py-1 rounded bg-success/20 text-success text-xs">Active</span>` :
                    `<span class="px-2 py-1 rounded bg-danger/20 text-danger text-xs">Inactive</span>`;

                return `
                    <tr class="intro-x" style="box-shadow: 0px 3px 20px #0000000b; border-radius: 8px; margin-bottom: 10px; height: 45px;">
                        <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">${rowNumber}</td>
                        <td class="border-b-0 bg-white font-medium shadow-[20px_3px_20px_#0000000b]">${dealer.name}</td>
                        <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">${dealer.code}</td>
                        <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">${dealer.email}</td>
                        <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">${dealer.phone}</td>
                        <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">${dealer.location || '-'}</td>
                        <td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">${dealer.pincode}</td>
                        <td class="border-b-0 bg-white text-center shadow-[20px_3px_20px_#0000000b]">${statusBadge}</td>
                        ${@json(auth()->check() && (auth()->user()->hasPermission('edit-bullions') || auth()->user()->hasPermission('delete-bullions'))) ? `
                        <td class="relative border-b-0 bg-white py-0 text-center shadow-[20px_3px_20px_#0000000b]">
                            <div class="flex items-center justify-center">
                                ${@json(auth()->check() && auth()->user()->hasPermission('edit-bullions')) ? `
                                <a href="/master/dealers/${dealer.id}/edit" class="flex items-center mr-3 text-success">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6m-3-3v6m7 3v-2a1 1 0 00-1-1h-1m-10 0H5a1 1 0 00-1 1v2m10 0H7a2 2 0 01-2-2v-5a2 2 0 012-2h8a2 2 0 012 2v5a2 2 0 01-2 2z" />
                                    </svg>
                                    Edit
                                </a>` : ''}
                                ${@json(auth()->check() && auth()->user()->hasPermission('delete-bullions')) ? `
                                <form action="/master/dealers/${dealer.id}" method="POST" onsubmit="return confirm('Are you sure you want to delete this dealer?')" class="m-0 p-0">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="flex items-center text-danger bg-transparent border-0 cursor-pointer">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>` : ''}
                            </div>
                        </td>` : ''}
                    </tr>
                `;
            }
        });

        // Hide error messages after 2 seconds
        const errorAlert = document.querySelector('.intro-y.col-span-12 .alert-danger.show');
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.transition = "opacity 0.5s ease";
                errorAlert.style.opacity = 0;
                setTimeout(() => {
                    if (errorAlert.parentNode) {
                        errorAlert.parentNode.removeChild(errorAlert);
                    }
                }, 500); // wait for fade-out transition
            }, 2000);
        }
    </script>
@endsection
