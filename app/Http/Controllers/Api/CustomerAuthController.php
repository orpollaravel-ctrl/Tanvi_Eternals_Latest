<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients,email'],
            'password' => ['required', 'string', 'min:8'],
            'mobile_number' => ['nullable', 'string', 'max:50'],
        ]);

        $client = Client::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'mobile_number' => $validated['mobile_number'] ?? null,
        ]);

        $token = $client->createToken('customer-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'customer' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'mobile_number' => $client->mobile_number,
                ],
                'token' => $token
            ]
        ], 201);
    }

    public function login(Request $request)
    { 
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)
                       ->orWhere('mobile_number', $request->email)
                       ->first();
    
        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $client->createToken('customer-token')->plainTextToken; 
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'customer' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'mobile_number' => $client->mobile_number,
                ],
                'token' => $token
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $client = $request->user();
        if ($client && $client->currentAccessToken()) {
            $client->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function quotations(Request $request)
    {
        $client = $request->user();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $quotations = Quotation::where('customer_id', $client->id)
            ->where(function ($q) {
                $q->whereNull('barcode')
                  ->orWhere('barcode', '');
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $quotations
        ], 200);
    }

    public function quotationDetails(Request $request, $id)
    {
        $client = $request->user();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $quotation = Quotation::where('id', $id)
            ->where('customer_id', $client->id)
            ->first();

        if (!$quotation) {
            return response()->json([
                'success' => false,
                'message' => 'Quotation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $quotation
        ], 200);
    }
}
