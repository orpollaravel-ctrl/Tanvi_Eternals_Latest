@extends('../layouts/' . $layout)

@section('subhead')
    <title>{{ $user->name ?? 'User' }} Visits - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-10 flex items-center justify-between">
        <h2 class="text-lg font-medium">{{ $user->name ?? 'User' }} Visits</h2>
        <a href="{{ route('visits.index') }}">
            <x-base.button variant="secondary">← Back to Visits</x-base.button>
        </a>
    </div>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <x-base.table class="-mt-2 border-separate border-spacing-y-[10px]">
                <x-base.table.thead>
                    <x-base.table.tr>
                        <x-base.table.th class="border-b-0">Customer</x-base.table.th>
                        <x-base.table.th class="border-b-0">Phone</x-base.table.th>
                        <x-base.table.th class="border-b-0">Visit Date</x-base.table.th>
                        <x-base.table.th class="border-b-0">Time</x-base.table.th>
                        <x-base.table.th class="border-b-0">Reason</x-base.table.th>
                        <x-base.table.th class="border-b-0 text-center">Actions</x-base.table.th>
                    </x-base.table.tr>
                </x-base.table.thead>
                <x-base.table.tbody>
                    @foreach($visits as $visit)
                    <x-base.table.tr class="intro-x">
                        <x-base.table.td class="bg-white shadow">
                            {{ $visit->customer_name ?? 'N/A' }}
                        </x-base.table.td>
                        <x-base.table.td class="bg-white shadow">
                            {{ $visit->phone ?? 'N/A' }}
                        </x-base.table.td>
                        <x-base.table.td class="bg-white shadow">
                            {{ $visit->target_date ?? 'N/A' }}
                        </x-base.table.td>
                        <x-base.table.td class="bg-white shadow">
                            {{ $visit->time ?? 'N/A' }}
                        </x-base.table.td>
                        <x-base.table.td class="bg-white shadow">
                            {{ Str::limit($visit->reason ?? 'N/A', 30) }}
                        </x-base.table.td>
                        <x-base.table.td class="bg-white shadow text-center last:rounded-r-md">
                            <div class="flex items-center justify-center text-primary">
                                <a class="flex items-center text-primary" href="{{ route('visits.show', $visit->id) }}">
                                    <x-base.lucide class="w-4 h-4 mr-1" icon="Eye" />View
                                </a>
                            </div>
                        </x-base.table.td>
                    </x-base.table.tr>
                    @endforeach
                </x-base.table.tbody>
            </x-base.table>
        </div>
    </div>
@endsection