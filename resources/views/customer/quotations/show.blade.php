@extends('../layouts/' . $layout)

@section('subhead')
    <title>Quotation Details - Tanvi Eternals</title>
@endsection

@section('subcontent')
<h2 class="intro-y mt-10 text-lg font-medium">Quotation Details</h2>

<div class="intro-y box mt-5 p-5">
    <div class="grid grid-cols-12 gap-4">

        <div class="col-span-12 sm:col-span-6">
            <label class="text-slate-500 text-sm">Metal</label>
            <div class="font-medium">{{ ucfirst($quotation->metal) }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="text-slate-500 text-sm">Purity</label>
            <div class="font-medium">{{ strtoupper($quotation->purity) }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="text-slate-500 text-sm">Diamond</label>
            <div class="font-medium">{{ $quotation->diamond }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="text-slate-500 text-sm">Contact</label>
            <div class="font-medium">{{ $quotation->contact ?? '-' }}</div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="text-slate-500 text-sm">Women Ring Size</label>
            <div class="font-medium">
                {{ $quotation->women_ring_size_from ?? '-' }}
                -
                {{ $quotation->women_ring_size_to ?? '-' }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label class="text-slate-500 text-sm">Men Ring Size</label>
            <div class="font-medium">
                {{ $quotation->men_ring_size_from ?? '-' }}
                -
                {{ $quotation->men_ring_size_to ?? '-' }}
            </div>
        </div>

        <div class="col-span-12">
            <label class="text-slate-500 text-sm">Remarks</label>
            <div class="font-medium">
                {{ $quotation->remarks ?? '—' }}
            </div>
        </div>
        <div class="col-span-12 mt-6">
            <label class="text-slate-500 text-sm">Attached PDFs</label>

            @if ($quotation->pdfs->count())
                <ul class="mt-2 space-y-2">
                    @foreach ($quotation->pdfs as $pdf)
                        <li class="flex items-center p-2">
                            <span class="mr-3">{{ $pdf->original_name }}</span>
                            <a href="{{ asset($pdf->file_path) }}"
                                target="_blank"
                                class="mr-3 flex items-center text-primary">
                                    <x-base.lucide class="mr-1 h-4 w-4" icon="file" />
                                View
                                </a>
                        </li>
                    @endforeach
                </ul>
            @else
            <div class="text-slate-400 mt-2">No PDFs uploaded</div>
            @endif
        </div>


    </div>

    <div class="mt-6">
        <a href="{{ route('customer.quotations.index') }}">
            <x-base.button variant="outline-secondary">
                Back to Quotations
            </x-base.button>
        </a>
    </div>
</div>
@endsection
