@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Quotation - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-10 flex items-center justify-between">
        <h2 class="text-lg font-medium">Quotation Details</h2>

        <a href="{{ route('quotations.index') }}">
            <x-base.button variant="secondary">
                Back
            </x-base.button>
        </a>
    </div>

    <div class="intro-y box mt-5 p-5">
        <div class="grid grid-cols-12 gap-6">
            {{-- Customer Name --}}
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Customer Name</label>
                <div class="mt-1 font-semibold">
                    {{ $quotation->client->name ?? '-' }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Salesman</label>
                <div class="mt-1 font-semibold">
                    {{ empty($quotation->salesman) ? '-' : $quotation->salesman }}
                </div>  
            </div> 
            {{-- Contact --}}
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Contact</label>
                <div class="mt-1 font-semibold">
                    {{ $quotation->contact ?? '-' }}
                </div>
            </div>
            {{-- Customer Code --}}
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Customer Code</label>
                <div class="mt-1 font-semibold">
                    {{ $quotation->customer_code ?? '-' }}
                </div>
            </div>
            {{-- Metal --}}
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Metal</label>
                <div class="mt-1 font-semibold">
                    {{ ucfirst($quotation->metal) }}
                </div>
            </div>
            {{-- Purity --}}
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Purity</label>
                <div class="mt-1 font-semibold">
                    {{ $quotation->purity }}
                </div>
            </div>
            {{-- Diamond --}}
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Diamond</label>
                <div class="mt-1 font-semibold">
                    {{ $quotation->diamond }}
                </div>
            </div>
            {{-- Barcodes --}} 
            @if ($quotation->barcode != '')
                <div class="col-span-12">
                    <label class="block mb-3 text-sm font-medium text-slate-500">
                        Barcodes
                    </label>
                    <div class="flex flex-wrap gap-6">
                        @foreach (explode(',', $quotation->barcode) as $index => $code)
                            @php $code = trim($code); @endphp
                            @if ($code)
                                <div class="bg-white p-2 rounded shadow text-center">
                                    <div class="text-xs font-semibold text-slate-600">
                                        {{ $code }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="intro-y mt-5 flex flex-wrap gap-2">
        @if (auth()->check() && auth()->user()->hasPermission('edit-quotations'))
            <a href="{{ route('quotations.edit', $quotation->id) }}">
                <x-base.button variant="primary">
                    Edit
                </x-base.button>
            </a>
        @endif 
    </div>  
@endsection 
