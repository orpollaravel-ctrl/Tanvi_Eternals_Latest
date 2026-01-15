@extends('../layouts/' . $layout)

@section('subhead')
    <title>Expenses - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <style>
        /* FORCE RESPONSIVE GRID */
        .expense-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 640px) {
            .expense-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .expense-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1280px) {
            .expense-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* CARD WIDTH SAFETY */
        .expense-card {
            width: 100%;
            max-width: 100%;
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .expense-card:hover {
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
        }
    </style>
    <h2 class="intro-y mt-10 text-lg font-medium">Expenses</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-between gap-3 sm:flex-nowrap">
            <div class="flex items-center gap-2">
                @if (auth()->check() && auth()->user()->hasPermission('create-expenses'))
                    <a href="{{ route('expenses.create') }}">
                        <x-base.button class="shadow-md" variant="primary">
                            Add New Expense
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
                            <a href="javascript:void(0);" onclick="printExpenses()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="Printer" /> Print
                            </a>
                        </x-base.menu.item>
                        <x-base.menu.item>
                            <a href="javascript:void(0);" onclick="exportExpensesToExcel()" class="flex">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="FileText" /> Export to Excel
                            </a>
                        </x-base.menu.item>
                    </x-base.menu.items>
                </x-base.menu>
            </div>
            <form method="GET" class="relative">
                <x-base.form-select class="w-44" name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </x-base.form-select>
            </form>
        </div>
        <div class="intro-y col-span-12">
            <div class="expense-grid">
                @forelse($expenses as $salesmanId => $salesmanExpenses)
                    @php
                        $salesman = $salesmanExpenses->first()->salesman;
                        $totalAmount = $salesmanExpenses->sum('amount');
                        $latestExpense = $salesmanExpenses->first();
                    @endphp
                    <a href="{{ route('expenses.show', [$salesmanId, 'status' => request('status')]) }}" class="expense-card"
                        style="justify-content:space-between;">
                        <div class="flex justify-between items-center mb-3" style="justify-content:space-between;">
                            <span class="text-sm font-semibold text-primary">
                                {{ ucwords($salesman->name ?? '-') }}
                            </span>
                        </div>
                        <div class="text-2xl font-bold mb-2">
                            ₹{{ number_format($totalAmount, 2) }}
                        </div>
                        <div class="flex justify-between items-center text-sm" style="justify-content:space-between;">
                            <span class="text-slate-500">
                                {{ $salesmanExpenses->count() }} expense(s)
                            </span>
                            <span class="text-primary font-medium">View Details →</span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-slate-500 py-10">
                        No expenses found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function printExpenses() {
                window.open('{{ route('expenses.print', request()->query()) }}', '_blank');
            }

            function exportExpensesToExcel() {
                window.location.href = '{{ route('expenses.export.excel', request()->query()) }}';
            }
        </script>
    @endpush
@endsection
