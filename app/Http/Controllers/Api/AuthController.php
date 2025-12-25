<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Collection;
use App\Models\Dsr;
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

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
        $clients = Client::latest()->get();
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
        $quotations = Quotation::with('client:id,name')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $quotations->map(function ($quotation) {
                return [
                    'id' => $quotation->id,
                    'customer_id' => $quotation->customer_id,
                    'customer_name' => $quotation->client->name ?? null,
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
                ];
            })
        ], 200);
    }

    public function createQuotation(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'integer','exists:clients,id'],
            'contact' => ['required', 'string', 'max:255'],
            'customer_code' => ['required', 'string', 'max:255'],
            'metal' => ['required', 'in:yellow gold,rose gold,white gold'],
            'purity' => ['required', 'in:22k,18k,14k,9k'],
            'diamond' => ['required', 'in:SI-IJ,SI-GH,VS-GH,VVS-EF,VS-SIGH,VS-ISHI,SI-HI'],
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
        $quotation = Quotation::with('client:id,name')->find($id);

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
                'customer_id' => $quotation->customer_id,
                'customer_name' => $quotation->client->name ?? null,
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
        $expenses = Expense::latest()->get();

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
        ]);

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
        $expense = Expense::find($id);

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
    public function createDsr(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'client_type' => ['required'],
            'no_of_shops' => ['nullable', 'integer'],
            'visiting_card_photo' => ['nullable', 'image', 'max:2048'],
            'shop_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('visiting_card_photo')) {
            $file = $request->file('visiting_card_photo');
            $fileName = time() . '_vc_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/visiting_cards'), $fileName);
            $validated['visiting_card_photo'] = $fileName;
        }

        if ($request->hasFile('shop_photo')) {
            $file = $request->file('shop_photo');
            $fileName = time() . '_shop_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/shop_photos'), $fileName);
            $validated['shop_photo'] = $fileName;
        }

        $dsr = Dsr::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'DSR created successfully',
            'data' => $dsr
        ], 201);
    }

    public function dsrList(Request $request)
    {
        $dsrs = Dsr::with('client')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $dsrs
        ], 200);
    }

    public function dsrDetail($id)
    {
        $dsr = Dsr::with('client')->find($id);

        if (!$dsr) {
            return response()->json([
                'success' => false,
                'message' => 'DSR not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dsr
        ], 200);
    }

    public function collectionsByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $data = Collection::with('client')
            ->whereDate('collection_date', $request->date)
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createCollection(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'collection_date' => 'required|date',
            'amount' => 'required|numeric'
        ]); 

      $collection = Collection::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Collection added successfully',
            'data' => $collection
        ]);
    }

    public function ordersByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $data = Order::with('client')
            ->whereDate('order_date', $request->date)
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'order_date' => 'required|date',
            'order_qty' => 'required|integer'
        ]);

      $order = Order::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order added successfully',
            'data' => $order
        ]);
    }

    public function targetsByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $data = Target::with('client')
            ->whereDate('target_date', $request->date)
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function createTarget(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'target_date' => 'required|date',
            'target_qty' => 'required|integer'
        ]);

      $target =  Target::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Target added successfully',
            'data' => $target
        ]);
    }

    public function quotationFilter(Request $request)
    {
        $query = Quotation::with('client');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
               $a =  $q->where('customer_code', 'like', "%{$search}%")
                ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });  
            });
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $quotations = $query->latest()->get(); 
        return response()->json([
            'status' => true,
            'data' => $quotations
        ]);     
    }

    public function customerFilter(Request $request)
    {
         $query = Client::query();

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
         $query = Expense::query();
 
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
}
