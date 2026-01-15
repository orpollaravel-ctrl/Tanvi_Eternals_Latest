@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Expense - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Expense</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">
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

                <form method="POST" action="{{ route('expenses.update', $expense->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Type of Expense *</x-base.form-label>
                            <x-base.tom-select name="type" required>
                                <option value="">Select Type</option>
                                <option value="travel expense" @selected(old('type', $expense->type) == 'travel expense')>Travel Expense</option>
                                <option value="food expense" @selected(old('type', $expense->type) == 'food expense')>Food Expense</option>
                                <option value="hotel expense" @selected(old('type', $expense->type) == 'hotel expense')>Hotel Expense</option>
                                <option value="other expense" @selected(old('type', $expense->type) == 'other expense')>Other Expense</option>
                            </x-base.tom-select>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Date *</x-base.form-label>
                            <x-base.form-input type="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" required />
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Amount *</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" name="amount" value="{{ old('amount', $expense->amount) }}" placeholder="0.00" required />
                        </div>
                        <div  class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Salesman*</x-base.form-label>
                            <x-base.tom-select name="salesman_id" class="tom-select w-full" required>
                                <option value="">Select Salesman</option>
                                @foreach ($salesman as $emp)
                                    <option value="{{ $emp->id }}" @selected(old('salesman_id',$expense->salesman_id) == $emp->id)>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </x-base.tom-select>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Upload Bill</x-base.form-label>
                            <x-base.form-input type="file" name="bill_upload" accept=".pdf,.jpg,.jpeg,.png" />
                            @if($expense->bill_upload)
                                <div class="mt-2">
                                    <a href="{{ asset('uploads/expenses/' . $expense->bill_upload) }}" target="_blank" class="text-primary text-sm">View Current Bill</a>
                                </div>
                            @endif
                            <div class="mt-1 text-xs text-slate-500">Accepted formats: PDF, JPG, JPEG, PNG (Max: 2MB)</div>
                        </div>
                        <div class="col-span-12">
                            <x-base.form-label>Remark</x-base.form-label>
                            <x-base.form-textarea name="remark" placeholder="Enter remarks">{{ old('remark', $expense->remark) }}</x-base.form-textarea>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('expenses.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Update Expense</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection