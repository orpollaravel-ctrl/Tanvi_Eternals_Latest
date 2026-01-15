@extends('../layouts/' . $layout)

@section('subhead')
    <title>My Quotations - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">My Quotations</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Metal</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Purity</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Diamond</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Women Ring Size</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Men Ring Size</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0">Remarks</x-base.table.th>
                        <x-base.table.th class="whitespace-nowrap border-b-0 text-center">Action</x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>

                <x-base.table.tbody>
                    @forelse ($quotations as $quotation)
                        <x-base.table.tr class="intro-x">
                            <x-base.table.td class="border-b-0 bg-white first:rounded-l-md last:rounded-r-md">
                                <div class="whitespace-nowrap text-xs text-slate-500">
                                    {{ ucfirst($quotation->metal) }}
                                </div>
                            </x-base.table.td>

                            <x-base.table.td class="border-b-0 bg-white">
                                <div class="whitespace-nowrap text-xs text-slate-500">
                                    {{ strtoupper($quotation->purity) }}
                                </div>
                            </x-base.table.td>

                            <x-base.table.td class="border-b-0 bg-white">
                                <div class="whitespace-nowrap text-xs text-slate-500">
                                    {{ $quotation->diamond }}
                                </div>
                            </x-base.table.td>

                            <x-base.table.td class="border-b-0 bg-white">
                                <div class="whitespace-nowrap text-xs text-slate-500">
                                    {{ $quotation->women_ring_size_from ?? '-' }}
                                    @if($quotation->women_ring_size_to)
                                        - {{ $quotation->women_ring_size_to }}
                                    @endif
                                </div>
                            </x-base.table.td>

                            <x-base.table.td class="border-b-0 bg-white">
                                <div class="whitespace-nowrap text-xs text-slate-500">
                                    {{ $quotation->men_ring_size_from ?? '-' }}
                                    @if($quotation->men_ring_size_to)
                                        - {{ $quotation->men_ring_size_to }}
                                    @endif
                                </div>
                            </x-base.table.td>

                            <x-base.table.td class="border-b-0 bg-white max-w-xs">
                                <div class="truncate text-xs text-slate-500">
                                    {{ $quotation->remarks ?? '-' }}
                                </div>
                            </x-base.table.td>

                            <x-base.table.td class="border-b-0 bg-white text-center">
                                <a href="{{ route('customer.quotations.show', $quotation->id) }}"
                                   class="text-primary flex items-center justify-center">
                                    <x-base.lucide class="mr-1 h-4 w-4" icon="Eye" />
                                    View
                                </a>
                            </x-base.table.td>
                        </x-base.table.tr>
                    @empty
                        <x-base.table.tr>
                            <x-base.table.td colspan="7" class="text-center py-6 text-slate-500">
                                No quotations found.
                            </x-base.table.td>
                        </x-base.table.tr>
                    @endforelse
                </x-base.table.tbody>
            </x-base.table>
        </div>
        <!-- END: Data List -->
    </div>
@endsection
