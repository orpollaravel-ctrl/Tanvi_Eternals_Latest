@extends('../layouts/' . $layout)

@section('subhead')
    <title>Bullions DashBoard- Jewelry ERP</title>
@endsection
<style>
    #dealerPieChart, #bullionPieChart {
        max-width: 400px;
        max-height: 280px;
    }
</style>
@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Bullions Dashboard</h2>

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
                            $drf_qty = round($drf->sum('pending'),3);
                            $drf_amt = round($drf->sum('pending_amt'));
                            $drf_avg = $drf_qty != 0 && $drf_amt != 0 ? round(($drf_amt / $drf_qty) * 10, 3) : 0;
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
                            $brf_qty = round($brf->sum('pending'),3);
                            $brf_amt = round($brf->sum('pending_amt'));
                            $brf_avg = $brf_qty != 0 && $brf_amt != 0 ? round(($brf_amt / $brf_qty) * 10, 3) : 0;
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
		 <!-- Dealer Amt Compare Chart -->
        <div class="col-span-12 sm:col-span-6 lg:col-span-6">
            <div class="intro-y flex h-10 items-center">
                <h2 class="mr-5 truncate text-lg font-medium">Dealer Amount Compare Chart</h2>
            </div>
            <div class="intro-y box mt-5 p-5">
                <div class="mt-3">
                    <x-report-pie-chart
                        id="dealer-amt-compare-chart"
                        height="h-[213px]"
                        :labels="['Dealer Amt','Dealer Avrg']"
                        :data="[intval($dealerAmt), intval($dealerAvrg)]"
                        :colors="['primary','success']"
                    />
                </div>
               <div style="margin: 2rem auto 0; width: 208px;">
					<div style="display: flex; align-items: center; margin-bottom: 1rem;">
						<div style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.5rem; background-color: #3b82f6;"></div>
						<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Dealer Amount</span>
						<span style="margin-left: auto; font-weight: 500;">{{ $dealerAmt }}</span>
					</div>

					<div style="display: flex; align-items: center; margin-bottom: 1rem;">
						<div style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.75rem; background-color: #22c55e;"></div>
						<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Dealer Average</span>
						<span style="margin-left: auto; font-weight: 500;">{{ $dealerAvrg }}</span>
					</div>

					<div style="display: flex; align-items: center;">
						<div style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.75rem; background-color: #ef4444;"></div>
						<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Dealer Qty</span>
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
                    <x-report-pie-chart
                        id="bullion-amt-compare-chart"
                        height="h-[213px]"
                        :labels="['Bullion Amt','Bullion Avrg']"
                        :data="[intval($bullionAmt), intval($bullionAvrg)]"
                        :colors="['pending','primary']"
                    />
                </div>
                <div style="margin: 2rem auto 0; width: 208px;">
					<div style="display: flex; align-items: center; margin-bottom: 1rem;">
						<div style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.5rem; background-color: #f59e0b;"></div>
						<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Bullion Amount</span>
						<span style="margin-left: auto; font-weight: 500;">{{$bullionAmt}}</span>
					</div>

					<div style="display: flex; align-items: center; margin-bottom: 1rem;">
						<div style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.5rem; background-color: #ef4444;"></div>
						<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Bullion Average</span>
						<span style="margin-left: auto; font-weight: 500;">{{ $bullionAvrg }}</span>
					</div>

					<div style="display: flex; align-items: center;">
						<div style="width: 12px; height: 12px; border-radius: 50%; margin-right: 0.75rem; background-color: #22c55e;"></div>
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
                                    <x-base.table.th class="whitespace-nowrap border-b-0">Balance Quantity</x-base.table.th>
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
                                            $avg_amt = ($qty != 0 && $amt != 0) ? round($amt / $qty, 3) : 0;
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

