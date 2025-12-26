@extends('../layouts/' . $layout)

@section('subhead')
    <title>Expenses - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Expenses</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
            @if(auth()->check() && auth()->user()->hasPermission('create-expenses'))
                <a href="{{ route('expenses.create') }}">
                    <x-base.button class="shadow-md" variant="primary">
                        Add New Expense
                    </x-base.button>
                </a>
            @endif
        </div>  
        <div class="intro-y col-span-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              @forelse($expenses as $salesmanId => $salesmanExpenses)
                        @php
                            $salesman = $salesmanExpenses->first()->salesman;
                            $totalAmount = $salesmanExpenses->sum('amount');
                            $latestExpense = $salesmanExpenses->first();
                        @endphp
                        <a href="{{ route('expenses.show', $salesmanId) }}" class="block bg-white dark:bg-darkmode-600 rounded-lg shadow-md p-5 hover:shadow-xl transition aspect-square flex flex-col justify-between">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-semibold text-primary">
                                    {{ ucwords($salesman->name ?? '-') }}
                                </span>
                            </div>
                            <div class="text-2xl font-bold mb-2">
                                ₹{{ number_format($totalAmount, 2) }}
                            </div> 
                            <div class="flex justify-between items-center text-sm">
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
@endsection