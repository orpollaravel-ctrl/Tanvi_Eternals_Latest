@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Vendor - Midone</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Vendor</h2>
    <div class="mt-5 grid grid-cols-8 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">
                {{-- Error Handling --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger dark:border-danger/30">
                        <div class="font-medium">There were some problems with your input.</div>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('vendor.update', $vendor->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-4 gap-4 mt-6">
                        <div class="mt-3">
                            <x-base.form-label>Code*</x-base.form-label>
                            <x-base.form-input type="text" name="code" value="{{ old('code',$vendor->code) }}" placeholder="Code" />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Name*</x-base.form-label>
                            <x-base.form-input type="text" name="name" value="{{ old('name',$vendor->name) }}" placeholder="Name" />
                        </div>   
                        <div class="mt-3">
                            <x-base.form-label>GST No.*</x-base.form-label>
                            <x-base.form-input type="text" name="gst_number" value="{{ old('gst_number',$vendor->gst_number) }}" placeholder="GST Number" />
                        </div>   
                        <div class="mt-3">
                            <x-base.form-label>PAN No.*</x-base.form-label>
                            <x-base.form-input type="text" name="pan_number" value="{{ old('pan_number',$vendor->pan_number) }}" placeholder="PAN Number" />
                        </div>            
                    </div>

                    <!-- Converted Weight + Purchase Rate + Amount -->
                    <div class="grid grid-cols-4 gap-4 mt-3">                        
                        <div class="mt-3">
                            <x-base.form-label>Adhar No.*</x-base.form-label>
                            <x-base.form-input type="text" name="adhard_number" value="{{ old('adhard_number',$vendor->adhard_number) }}" placeholder="Adhar Number" />
                        </div>  
                        <div class="mt-3">
                            <x-base.form-label>Bank Account No.</x-base.form-label>
                            <x-base.form-input type="text"  name="bank_account_number" value="{{ old('bank_account_number',$vendor->bank_account_number) }}" placeholder="Bank Account No." />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>IFSC No.</x-base.form-label>
                            <x-base.form-input type="text"  name="ifsc_code" value="{{ old('ifsc_code',$vendor->ifsc_code) }}" placeholder="IFSC No." />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Party Name (Vendor Co-ordinate Name) </x-base.form-label>
                            <x-base.form-input type="text" name="party_name" value="{{ old('party_name',$vendor->party_name) }}" placeholder="Party Name" />
                        </div>                        
                    </div>

                    <div class="grid grid-cols-4 gap-4 mt-3">                        
                        <div class="mt-3">
                            <x-base.form-label>Contact No.*</x-base.form-label>
                            <x-base.form-input type="text" name="contact_no" value="{{ old('contact_no',$vendor->contact_no) }}" placeholder="Contact No." />
                        </div>  
                        <div class="mt-3">
                            <x-base.form-label>Email*</x-base.form-label>
                            <x-base.form-input type="email" name="email" value="{{ old('email',$vendor->email) }}" placeholder="example@gmail.com" />
                        </div>  
                        <div class="mt-3">
                            <x-base.form-label>SalesMan</x-base.form-label>
                            <x-base.form-select name="salesman">
                                <option value="" {{ (old('salesman', $vendor->salesman) == '' ? 'selected' : '') }}>Select</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ (old('salesman', $vendor->salesman) == $employee->id ? 'selected' : '') }}>{{ $employee->name }}</option>
                                @endforeach
                            </x-base.form-select>
                        </div>  
                        <div class="mt-3">
                            <x-base.form-label>Address</x-base.form-label>
                            <textarea
                                name="address"
                                rows="2"
                                class="form-control w-full rounded-md border border-slate-300 p-2"
                                placeholder="Enter Address">{{ old('address', $vendor->address) }}</textarea>
                        </div>                                               
                    </div>  
                    
                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('vendor.index',$vendor->id) }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Save</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
