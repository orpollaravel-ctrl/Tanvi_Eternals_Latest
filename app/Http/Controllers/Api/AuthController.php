<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Collection;
use App\Models\Dsr;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Quotation;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)
                   ->orWhere('contact_number', $request->email)
                   ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->active != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact administrator.'
            ], 403);
        }

        if($user->sales_id == null){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized as Salesman. Please contact administrator.'
            ], 403);
        }

        $token = $user->createToken('salesman-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact_number' => $user->contact_number,
                    'gender' => $user->gender,
                ],
                'token' => $token
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function me(Request $request)
    { 
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contact_number' => $user->contact_number,
                'gender' => $user->gender,
                'photo' => $user->photo ? url('uploads/user/' . $user->photo) : null,
            ]
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'contact_number' => ['required', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->contact_number = $validated['contact_number'];

        if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path('uploads/user/' . $user->photo))) {
                unlink(public_path('uploads/user/' . $user->photo));
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/user';
            $file->move(public_path($filePath), $fileName);
            $user->photo = $fileName;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contact_number' => $user->contact_number,
                'gender' => $user->gender,
                'photo' => $user->photo ? url('uploads/user/' . $user->photo) : null,
            ]
        ], 200);
    }

    public function customers(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $clients = Client::where('salesman_id', $employee->id)->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $clients
        ], 200);
    }

    public function customerDetails(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $client
        ], 200);
    }

    public function quotations()
    {
        $quotations = Quotation::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $quotations->map(function ($quotation) {
                return [
                    'id' => $quotation->id,
                    'customer_name' => $quotation->customer_name,
                    'salesman_name' => $quotation->salesman_name,
                    'pincode' => $quotation->pincode,
                    'state' => $quotation->state,
                    'city' => $quotation->city,
                    'contact' => $quotation->contact, 
                    'metal' => $quotation->metal,
                    'purity' => $quotation->purity,
                    'diamond' => $quotation->diamond,
                    'women_ring_size_from' => $quotation->women_ring_size_from,
                    'women_ring_size_to' => $quotation->women_ring_size_to,
                    'men_ring_size_from' => $quotation->men_ring_size_from,
                    'men_ring_size_to' => $quotation->men_ring_size_to,
                    'remarks' => $quotation->remarks,
                    'barcode' => $quotation->barcode,
                    'created_at' => $quotation->created_at,
                    'updated_at' => $quotation->updated_at,
                ];
            })
        ], 200);
    }

    public function createQuotation(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'salesman_name' => ['required', 'string', 'max:255'],
            'pincode' => ['required', 'string', 'max:10'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'], 
            'metal' => ['required', 'in:yellow gold,rose gold,white gold'],
            'purity' => ['required', 'in:22k,18k,14k,9k'],
            'diamond' => ['required', 'in:SI-IJ,SI-GH,VS-GH,VVS-EF,VS-SIGH,VS-ISHI,SI-HI,CVD'],
            'women_ring_size_from' => ['nullable', 'string', 'max:255'],
            'women_ring_size_to' => ['nullable', 'string', 'max:255'],
            'men_ring_size_from' => ['nullable', 'string', 'max:255'],
            'men_ring_size_to' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'barcode' => ['nullable', 'array'], 
        ]);
         if (!empty($validated['barcode'])) {
            $validated['barcode'] = implode(',', $validated['barcode']);
        }
        $quotation = Quotation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Quotation created successfully',
            'data' => $quotation
        ], 201);
    }

   public function quotationDetails($id)
    {
        $quotation = Quotation::find($id);

        if (!$quotation) {
            return response()->json([
                'success' => false,
                'message' => 'Quotation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $quotation->id,
                'customer_name' => $quotation->customer_name,
                'salesman_name' => $quotation->salesman_name,
                'pincode' => $quotation->pincode,
                'state' => $quotation->state,
                'city' => $quotation->city,
                'contact' => $quotation->contact,
                'customer_code' => $quotation->customer_code,
                'metal' => $quotation->metal,
                'purity' => $quotation->purity,
                'diamond' => $quotation->diamond,
                'women_ring_size_from' => $quotation->women_ring_size_from,
                'women_ring_size_to' => $quotation->women_ring_size_to,
                'men_ring_size_from' => $quotation->men_ring_size_from,
                'men_ring_size_to' => $quotation->men_ring_size_to,
                'remarks' => $quotation->remarks,
                'barcode' => $quotation->barcode,
                'created_at' => $quotation->created_at,
                'updated_at' => $quotation->updated_at,
            ]
        ], 200);
    }

    public function expenses()
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $expenses = Expense::where('salesman_id', $employee->id)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $expenses
        ], 200);
    }

    public function createExpense(Request $request)
    { 
        $validated = $request->validate([
            'type' => ['required', 'in:travel expense,food expense,hotel expense,other expense'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'remark' => ['nullable', 'string'],
            'bill_upload' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'status' => ['nullable', 'in:pending,approved,rejected'],
        ]);

        $user = $request->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $validated['salesman_id'] = $employee->id;
        $validated['salesman_name'] = $employee->name;

        if ($request->hasFile('bill_upload')) {
            $file = $request->file('bill_upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $fileName);
            $validated['bill_upload'] = $fileName;
        }

        $expense = Expense::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Expense created successfully',
            'data' => $expense
        ], 201);
    }

    public function expenseDetails($id)
    {
        $expense = Expense::with('salesman')->find($id);

        if (!$expense) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $expense
        ], 200);
    } 

    public function quotationFilter(Request $request)
    {
        $query = Quotation::query();

        if ($request->filled('search')) {
            $query->where('customer_name', 'LIKE', '%' . $request->search . '%');
        } 

        $quotations = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $quotations
        ]);
    }

    public function customerFilter(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $query = Client::where('salesman_id', $employee->id);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $clients
        ]);
    }

    public function expenseFilter(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $query = Expense::where('salesman_id', $employee->id);
 
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
 
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $expenses = $query->orderByDesc('date')->get();

        return response()->json([
            'status' => true,
            'data' => $expenses
        ]);
    }

    public function createVisit(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'location' => ['required'],
            'target_date' => ['required'],
            'time' => ['required'],
            'phone' => ['required', 'string', 'max:255'], 
            'visit_card' => ['nullable', 'image', 'max:2048'],
            'shop_photo' => ['nullable', 'image', 'max:2048'],
            'reason' => ['required', 'string', 'max:255'],
        ]);
        
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $validated['user_id'] = $employee->id;
        
        if ($request->hasFile('visit_card')) {
            $file = $request->file('visit_card');
            $fileName = time() . '_vc_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/visiting_cards'), $fileName);
            $validated['visit_card'] = $fileName;
        }

        if ($request->hasFile('shop_photo')) {
            $file = $request->file('shop_photo');
            $fileName = time() . '_shop_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/shop_photos'), $fileName);
            $validated['shop_photo'] = $fileName;
        }

        $dsr = Target::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Visit created successfully',
            'data' => $dsr
        ], 201);
    }

    public function visitList(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $data = Target::where('user_id', $employee->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function visitsByDate(Request $request)
    {
         $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $targets = Target::where('user_id', $employee->id)
            ->whereDate('target_date', '>=', $request->from_date)
            ->whereDate('target_date', '<=', $request->to_date)
            ->orderByDesc('target_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $targets
        ]);
    }

    public function visitDetail($id)
    {
        $visit = Target::find($id);

        if (!$visit) {
            return response()->json([
                'success' => false,
                'message' => 'Visit not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $visit
        ]);
    } 

    public function collectionsByDate(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $collections = Collection::where('user_id', $employee->id)
            ->whereDate('collection_date', '>=', $request->from_date)
            ->whereDate('collection_date', '<=', $request->to_date)
            ->orderByDesc('collection_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $collections
        ]);
    }

    public function createCollection(Request $request)
    {
        $validated = $request->validate([ 
            'time' => 'required',
            'client_id' => 'required|exists:clients,id',
            'collection_date' => 'required|date',
            'amount' => 'required|numeric',
            'remark' => 'required|string'
        ]);

        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $validated['user_id'] = $employee->id;

        $collection = Collection::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Collection added successfully',
            'data' => $collection
        ], 201);
    }

    public function collectionList(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $data = Collection::where('user_id', $employee->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function collectionDetail($id)
    {
        $collection = Collection::find($id);

        if (!$collection) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $collection
        ]);
    }

    public function createOrder(Request $request)
    {
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'order_date' => 'required|date',
            'order_qty' => 'required',
            'remark' => 'required|string', 
            'time' => 'required'
        ]);

        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $validated['user_id'] = $employee->id;

        $order = Order::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order added successfully',
            'data' => $order
        ], 201);
    }

    public function orderList(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $data = Order::where('user_id', $employee->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function orderDetail($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function ordersByDate(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $orders = Order::where('user_id', $employee->id)
            ->whereDate('order_date', '>=', $request->from_date)
            ->whereDate('order_date', '<=', $request->to_date)
            ->orderByDesc('order_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function salesmanList()
    {
        $salesman = Employee::whereHas('department', function ($q) {
            $q->whereRaw('LOWER(name) = ?', ['sales']);
        })->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $salesman
        ]);
    }

    public function calendarData(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = $request->date;
        $user = auth()->user();
        $employee = \App\Models\Employee::find($user->sales_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $userId = $employee->id;

        $collections = Collection::where('user_id', $userId)
            ->whereDate('collection_date', $date) 
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'collection', 
                    'amount' => $item->amount,
                    'time' => $item->time,
                    'date' => $item->collection_date,
                    'remark' => $item->remark
                ];
            });

        $orders = Order::where('user_id', $userId)
            ->whereDate('order_date', $date) 
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'order', 
                    'order_qty' => $item->order_qty,
                    'time' => $item->time,
                    'date' => $item->order_date,
                    'remark' => $item->remark
                ];
            }); 
        $allData = $collections->concat($orders);

        return response()->json([
            'success' => true,
            'data' => [
                'collections' => $collections,
                'orders' => $orders, 
                'all' => $allData
            ]
        ]);
    }
}
