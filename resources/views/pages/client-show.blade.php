@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Client - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-10 flex items-center justify-between">
        <h2 class="text-lg font-medium">Client Details</h2>

        <a href="{{ route('client.index') }}">
            <x-base.button variant="secondary">
                Back
            </x-base.button>
        </a>
    </div>

    <div class="intro-y box mt-5 p-5">
        <div class="grid grid-cols-12 gap-6"> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Client Code</label>
                <div class="mt-1 font-semibold">
                    {{ $client->code ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Client Name</label>
                <div class="mt-1 font-semibold">
                    {{ $client->name ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Client Type</label>
                <div class="mt-1 font-semibold">
                    {{ $client->client_type ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Salesman</label>
                <div class="mt-1 font-semibold">
                    {{ $client->salesman->name ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Email</label>
                <div class="mt-1 font-semibold">
                    {{ $client->email ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Mobile Number</label>
                <div class="mt-1 font-semibold">
                    {{ $client->mobile_number ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">City</label>
                <div class="mt-1 font-semibold">
                    {{ $client->city ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">State</label>
                <div class="mt-1 font-semibold">
                    {{ $client->state ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Pincode</label>
                <div class="mt-1 font-semibold">
                    {{ $client->pincode ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">GST Number</label>
                <div class="mt-1 font-semibold">
                    {{ $client->gst_number ?? '-' }}
                </div>
            </div> 
            <div class="col-span-12">
                <label class="text-sm font-medium text-slate-500">Address</label>
                <div class="mt-1 font-semibold">
                    {{ $client->address ?? '-' }}
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y mt-5 flex flex-wrap gap-2">
        @if (auth()->check() && auth()->user()->hasPermission('edit-clients'))
            <a href="{{ route('client.edit', $client->id) }}">
                <x-base.button variant="primary">
                    Edit
                </x-base.button>
            </a>
        @endif
    </div>
@endsection
