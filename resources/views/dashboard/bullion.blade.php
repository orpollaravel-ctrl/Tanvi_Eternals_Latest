@extends('../layouts/' . $layout)

@section('subhead')
    <title>Bullions DashBoard- Jewelry ERP</title>
@endsection
<style>
    #dealerPieChart,
    #bullionPieChart {
        max-width: 400px;
        max-height: 280px;
    }

    .tx-item {
        width: 82px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-align: center;
        cursor: pointer;
    }

    .tx-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background-color: #ffffff;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
    }

    .tx-icon i {
        width: 18px;
        height: 18px;
    }

    .tx-item span {
        font-size: 11px;
        font-weight: 500;
        color: #475569;
        line-height: 1.2;
    }

    .tx-item:hover .tx-icon {
        background-color: #ffffff;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
</style>
@section('subcontent')
    <h2 class="intro-y mb-5 mt-10 text-lg font-medium">Bullions Dashboard</h2>
    <div class="col-span-12">
        <div class="flex grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- TRANSACTIONS --}}
            <div class="mb-5 mr-10">
                <h3 class="text-base font-semibold mb-4">Transactions</h3>
                <div class="flex gap-6">
                    <a href="{{ route('drfs.index', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="settings"></i></div>
                        <span>Client Rate Fix</span>
                    </a>
                    <a href="{{ route('brfs.index', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="bar-chart"></i></div>
                        <span>Bullion Rate Fix</span>
                    </a>
                    <a href="{{ route('receipts.index', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="download"></i></div>
                        <span>Metal Purchase</span>
                    </a>
                    <a href="{{ route('payments.index', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="credit-card"></i></div>
                        <span>Bank Book</span>
                    </a>
                    <a href="{{ route('manual_deal.create', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="file-text"></i></div>
                        <span>Manual Deal</span>
                    </a>
                </div>
            </div>
            {{-- REPORTS --}}
            <div class="mb-5 mr-10" style="margin-left: 40px;">
                <h3 class="text-base font-semibold mb-4">Reports</h3>
                <div class="flex gap-6">
                    <a href="{{ route('bullion_ledger', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="book"></i></div>
                        <span>Bullion Ledger</span>
                    </a>

                    <a href="{{ route('booking_comparision', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="activity"></i></div>
                        <span>Booking Comparison</span>
                    </a>

                    <a href="{{ route('pending_deals', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="clock"></i></div>
                        <span>Client Pending</span>
                    </a>

                    <a href="{{ route('bullion_pending_deals', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="clock"></i></div>
                        <span>Bullion Pending</span>
                    </a>
                </div>
            </div>

            {{-- MASTERS --}}
            <div class="mb-5" style="margin-left: 40px;">
                <h3 class="text-base font-semibold mb-4">Masters</h3>
                <div class="flex gap-6">
                    <a href="{{ route('bullions.index', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="database"></i></div>
                        <span>Bullions</span>
                    </a>

                    <a href="{{ route('paymentmodes.index', ['layout' => 'side-menu']) }}" class="tx-item">
                        <div class="tx-icon"><i data-lucide="credit-card"></i></div>
                        <span>Payment Modes</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5 grid grid-cols-12 gap-6">

        <!-- Dealer Rate Fix Card -->
        <!-- <div class="intro-y col-span-12 md:col-span-6">
                        <div class="box p-5 text-center">
                            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-3 rounded-t">
                                <div class="text-lg font-semibold">Dealer Rate Fix</div>
                            </div>
                            <div class="p-5 grid grid-cols-3 gap-4">
                                <div>
                                    @php
                                        $drf_qty = round($drf->sum('pending'), 3);
                                        $drf_amt = round($drf->sum('pending_amt'));
                                        $drf_avg =
                                            $drf_qty != 0 && $drf_amt != 0 ? round(($drf_amt / $drf_qty) * 10, 3) : 0;
                                    @endphp
                                    <div class="text-2xl font-bold">{{ $drf_qty }}</div>
                                    <div class="text-xs text-slate-500 uppercase">Pending Quantity</div>
                                </div>
                                <div class="border-l border-r border-slate-200">
                                    <div class="text-2xl font-bold">{{ $drf_amt }}</div>
                                    <div class="text-xs text-slate-500 uppercase">Pending Amount</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">{{ $drf_avg }}</div>
                                    <div class="text-xs text-slate-500 uppercase">Pending Average</div>
                                </div>
                            </div>
                        </div>
                    </div>-->

        <!-- Bullion Rate Fix Card -->
        <!--  <div class="intro-y col-span-12 md:col-span-6">
                        <div class="box p-5 text-center">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-500  p-3 rounded-t">
                                <div class="text-lg font-semibold">Bullion Rate Fix</div>
                            </div>
                            <div class="p-5 grid grid-cols-3 gap-4">
                                <div>
                                    @php
                                        $brf_qty = round($brf->sum('pending'), 3);
                                        $brf_amt = round($brf->sum('pending_amt'));
                                        $brf_avg =
                                            $brf_qty != 0 && $brf_amt != 0 ? round(($brf_amt / $brf_qty) * 10, 3) : 0;
                                    @endphp
                                    <div class="text-2xl font-bold">{{ $brf_qty }}</div>
                                    <div class="text-xs text-slate-500 uppercase">Pending Quantity</div>
                                </div>
                                <div class="border-l border-r border-slate-200">
                                    <div class="text-2xl font-bold">{{ $brf_amt }}</div>
                                    <div class="text-xs text-slate-500 uppercase">Pending Amount</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">{{ $brf_avg }}</div>
                                    <div class="text-xs text-slate-500 uppercase">Pending Average</div>
                                </div>
                            </div>
                        </div>
                    </div>  -->
        <!-- Client Amt Compare Chart -->
        <div class="col-span-12 sm:col-span-6 lg:col-span-6">
            <div class="intro-y flex h-10 items-center">
                <h2 class="mr-5 truncate text-lg font-medium">Client Amount Compare Chart</h2>
            </div>
            <div class="intro-y box mt-5 p-5">
                <div class="mt-3">
                    <x-report-pie-chart id="dealer-amt-compare-chart" height="h-[213px]" :labels="['Client Amt', 'Client Avrg']" :data="[intval($dealerAmt), intval($dealerAvrg)]"
                        :colors="['primary', 'success']" />
                </div>
                <div style="margin: 2rem auto 0; width: 208px;">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.5rem; background-color: #2D5F72;">
                        </div>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Client Amount</span>
                        <span style="margin-left: auto; font-weight: 500;">{{ $dealerAmt }}</span>
                    </div>

                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.75rem; background-color: #259E94;">
                        </div>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Client Average</span>
                        <span style="margin-left: auto; font-weight: 500;">{{ $dealerAvrg }}</span>
                    </div>  

                    <div style="display: flex; align-items: center;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.75rem; background-color: #ef4444;">
                        </div>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Client Qty</span>
                        <span style="margin-left: auto; font-weight: 500;">{{ $dealerQty }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bullion Amt Compare Chart -->
        <div class="col-span-12 sm:col-span-6 lg:col-span-6">
            <div class="intro-y flex h-10 items-center">
                <h2 class="mr-5 truncate text-lg font-medium">Bullion Amount Compare Chart</h2>
            </div>
            <div class="intro-y box mt-5 p-5">
                <div class="mt-3">
                    <x-report-pie-chart id="bullion-amt-compare-chart" height="h-[213px]" :labels="['Bullion Amt', 'Bullion Avrg']"
                        :data="[intval($bullionAmt), intval($bullionAvrg)]" :colors="['pending', 'primary']" />
                </div>
                <div style="margin: 2rem auto 0; width: 208px;">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.5rem; background-color: #f59e0b;">
                        </div>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Bullion Amount</span>
                        <span style="margin-left: auto; font-weight: 500;">{{ $bullionAmt }}</span>
                    </div>

                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.5rem; background-color: #2D5F72;">
                        </div>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Bullion
                            Average</span>
                        <span style="margin-left: auto; font-weight: 500;">{{ $bullionAvrg }}</span>
                    </div>

                    <div style="display: flex; align-items: center;">
                        <div
                            style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.75rem; background-color: #22c55e;">
                        </div>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Bullion Qty</span>
                        <span style="margin-left: auto; font-weight: 500;">{{ $bullionQty }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bullion Wise Outstanding Table -->
        <div class="intro-y col-span-12">
            <div class="box">
                <div class="p-5 border-b border-slate-200/60">
                    <h4 class="text-base font-medium">Bullion Wise Outstanding</h4>
                </div>
                <div class="p-5">
                    @if (count($bullions) > 0)
                        <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                            <x-base.table.thead>
                                <x-base.table.tr>
                                    <x-base.table.th class="whitespace-nowrap border-b-0">Bullion Name</x-base.table.th>
                                    <x-base.table.th class="whitespace-nowrap border-b-0">Balance
                                        Quantity</x-base.table.th>
                                    <x-base.table.th class="whitespace-nowrap border-b-0">Balance Amount</x-base.table.th>
                                    <x-base.table.th class="whitespace-nowrap border-b-0">Average Amount</x-base.table.th>
                                    <x-base.table.th class="whitespace-nowrap border-b-0">Pending Amount</x-base.table.th>
                                </x-base.table.tr>
                            </x-base.table.thead>

                            <x-base.table.tbody>
                                @foreach ($bullions as $key => $bullion)
                                    <x-base.table.tr class="intro-x">
                                        <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                            {{ $bullion->name }}
                                        </x-base.table.td>

                                        @php
                                            $qty = round($bullion->brf_quantity - $bullion->mr_quantity, 3);
                                            $amt = round($bullion->brf_amount - $bullion->payment_amount);
                                            $avg_amt = $qty != 0 && $amt != 0 ? round($amt / $qty, 3) : 0;
                                        @endphp

                                        <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                            {{ $qty }}
                                        </x-base.table.td>

                                        <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                            {{ $amt }}
                                        </x-base.table.td>

                                        <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                            {{ $avg_amt }}
                                        </x-base.table.td>

                                        <x-base.table.td class="border-b-0 bg-white shadow-[20px_3px_20px_#0000000b]">
                                            {{ round($bullion->pending_amount, 3) }}
                                        </x-base.table.td>
                                    </x-base.table.tr>
                                @endforeach
                            </x-base.table.tbody>

                        </x-base.table>
                    @else
                        <p class="text-center text-slate-500">Bullions not found.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
