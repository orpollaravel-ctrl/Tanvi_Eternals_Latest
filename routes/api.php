<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OpeningStockController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']); 

// Customer API Routes
Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/customer/login', [CustomerAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    }); 
    Route::get('/quotation/filter', [AuthController::class, 'quotationFilter']);
    Route::get('/customer/filter', [AuthController::class, 'customerFilter']);
    Route::get('/expense/filter', [AuthController::class, 'expenseFilter']);
    Route::get('/customers', [AuthController::class, 'customers']);
    Route::get('/customers/{id}', [AuthController::class, 'customerDetails']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::get('/quotations', [AuthController::class, 'quotations']);
    Route::post('/quotations', [AuthController::class, 'createQuotation']);
    Route::get('/quotations/{id}', [AuthController::class, 'quotationDetails']);
    Route::get('/expenses', [AuthController::class, 'expenses']);
    Route::post('/expenses', [AuthController::class, 'createExpense']);
    Route::get('/expenses/{id}', [AuthController::class, 'expenseDetails']); 
    Route::post('/visits', [AuthController::class, 'createVisit']); 
    Route::get('/visits', [AuthController::class, 'visitList']); 
    Route::get('/visits/filter', [AuthController::class, 'visitsByDate']); 
    Route::get('/visits/{id}', [AuthController::class, 'visitDetail']); 
    Route::post('/collections', [AuthController::class, 'createCollection']); 
    Route::get('/collections', [AuthController::class, 'collectionList']); 
    Route::get('/collections/filter', [AuthController::class, 'collectionsByDate']); 
    Route::get('/collections/{id}', [AuthController::class, 'collectionDetail']); 
    Route::post('/orders', [AuthController::class, 'createOrder']); 
    Route::get('/orders', [AuthController::class, 'orderList']); 
    Route::get('/orders/filter', [AuthController::class, 'ordersByDate']); 
    Route::get('/orders/{id}', [AuthController::class, 'orderDetail']);
    Route::get('/salesman', [AuthController::class, 'salesmanList']);
    
    // Customer authenticated routes
    Route::post('/customer/logout', [CustomerAuthController::class, 'logout']);
    Route::get('/customer/quotations', [CustomerAuthController::class, 'quotations']);
    Route::get('/customer/quotations/{id}', [CustomerAuthController::class, 'quotationDetails']);
});

Route::get('/calendar', [AuthController::class, 'calendarData']);

Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/employees/search', [EmployeeController::class, 'search']);
Route::get('/departments/search', [DepartmentController::class, 'search']);
Route::put('/opening-stock/{productId}', [OpeningStockController::class, 'updateAjax']);
