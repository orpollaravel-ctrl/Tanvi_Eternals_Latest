<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BullionPurchaseController;
use App\Http\Controllers\BullionRateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientRateCutPendingController;
use App\Http\Controllers\ClietRateFixController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\ColorSchemeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePartyController;
use App\Http\Controllers\VendorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('dark-mode-switcher', [DarkModeController::class, 'switch'])->name('dark-mode-switcher');
Route::get('color-scheme-switcher/{color_scheme}', [ColorSchemeController::class, 'switch'])->name('color-scheme-switcher');

Route::controller(AuthController::class)->middleware('loggedin')->group(function () {
    Route::get('login', 'loginView')->name('login.index');
    Route::post('login', 'login')->name('login.check');
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::controller(PageController::class)->group(function () {
        Route::get('/', 'dashboardOverview1')->name('dashboard-overview-1');
        Route::get('dashboard-overview-2-page', 'dashboardOverview2')->name('dashboard-overview-2');
        Route::get('dashboard-overview-3-page', 'dashboardOverview3')->name('dashboard-overview-3');
        Route::get('dashboard-overview-4-page', 'dashboardOverview4')->name('dashboard-overview-4');
        Route::get('categories-page', 'categories')->name('categories');
        Route::get('add-product-page', 'addProduct')->name('add-product');
        Route::get('product-list-page', 'productList')->name('product-list');
        Route::get('product-grid-page', 'productGrid')->name('product-grid');
        Route::get('transaction-list-page', 'transactionList')->name('transaction-list');
        Route::get('transaction-detail-page', 'transactionDetail')->name('transaction-detail');
        Route::get('seller-list-page', 'sellerList')->name('seller-list');
        Route::get('seller-detail-page', 'sellerDetail')->name('seller-detail');
        Route::get('reviews-page', 'reviews')->name('reviews');
        Route::get('inbox-page', 'inbox')->name('inbox');
        Route::get('file-manager-page', 'fileManager')->name('file-manager');
        Route::get('point-of-sale-page', 'pointOfSale')->name('point-of-sale');
        Route::get('chat-page', 'chat')->name('chat');
        Route::get('post-page', 'post')->name('post');
        Route::get('calendar-page', 'calendar')->name('calendar');
        Route::get('crud-data-list-page', 'crudDataList')->name('crud-data-list');
        Route::get('crud-form-page', 'crudForm')->name('crud-form');
        // Route::get('users-page', 'users')->name('users');
        Route::get('users-layout-1-page', 'usersLayout1')->name('users-layout-1');
        Route::get('users-layout-2-page', 'usersLayout2')->name('users-layout-2');
        Route::get('users-layout-3-page', 'usersLayout3')->name('users-layout-3');
        Route::get('profile-overview-1-page', 'profileOverview1')->name('profile-overview-1');
        Route::get('profile-overview-2-page', 'profileOverview2')->name('profile-overview-2');
        Route::get('profile-overview-3-page', 'profileOverview3')->name('profile-overview-3');
        Route::get('wizard-layout-1-page', 'wizardLayout1')->name('wizard-layout-1');
        Route::get('wizard-layout-2-page', 'wizardLayout2')->name('wizard-layout-2');
        Route::get('wizard-layout-3-page', 'wizardLayout3')->name('wizard-layout-3');
        Route::get('blog-layout-1-page', 'blogLayout1')->name('blog-layout-1');
        Route::get('blog-layout-2-page', 'blogLayout2')->name('blog-layout-2');
        Route::get('blog-layout-3-page', 'blogLayout3')->name('blog-layout-3');
        Route::get('pricing-layout-1-page', 'pricingLayout1')->name('pricing-layout-1');
        Route::get('pricing-layout-2-page', 'pricingLayout2')->name('pricing-layout-2');
        Route::get('invoice-layout-1-page', 'invoiceLayout1')->name('invoice-layout-1');
        Route::get('invoice-layout-2-page', 'invoiceLayout2')->name('invoice-layout-2');
        Route::get('faq-layout-1-page', 'faqLayout1')->name('faq-layout-1');
        Route::get('faq-layout-2-page', 'faqLayout2')->name('faq-layout-2');
        Route::get('faq-layout-3-page', 'faqLayout3')->name('faq-layout-3');
        Route::get('login-page', 'login')->name('login');
        Route::get('register-page', 'register')->name('register');
        Route::get('error-page-page', 'errorPage')->name('error-page');
        Route::get('update-profile-page', 'updateProfile')->name('update-profile');
        Route::get('change-password-page', 'changePassword')->name('change-password');
        Route::get('regular-table-page', 'regularTable')->name('regular-table');
        Route::get('tabulator-page', 'tabulator')->name('tabulator');
        Route::get('modal-page', 'modal')->name('modal');
        Route::get('slide-over-page', 'slideOver')->name('slide-over');
        Route::get('notification-page', 'notification')->name('notification');
        Route::get('tab-page', 'tab')->name('tab');
        Route::get('accordion-page', 'accordion')->name('accordion');
        Route::get('button-page', 'button')->name('button');
        Route::get('alert-page', 'alert')->name('alert');
        Route::get('progress-bar-page', 'progressBar')->name('progress-bar');
        Route::get('tooltip-page', 'tooltip')->name('tooltip');
        Route::get('dropdown-page', 'dropdown')->name('dropdown');
        Route::get('typography-page', 'typography')->name('typography');
        Route::get('icon-page', 'icon')->name('icon');
        Route::get('loading-icon-page', 'loadingIcon')->name('loading-icon');
        Route::get('regular-form-page', 'regularForm')->name('regular-form');
        Route::get('datepicker-page', 'datepicker')->name('datepicker');
        Route::get('tom-select-page', 'tomSelect')->name('tom-select');
        Route::get('file-upload-page', 'fileUpload')->name('file-upload');
        Route::get('wysiwyg-editor-classic-page', 'wysiwygEditorClassic')->name('wysiwyg-editor-classic');
        Route::get('wysiwyg-editor-inline-page', 'wysiwygEditorInline')->name('wysiwyg-editor-inline');
        Route::get('wysiwyg-editor-balloon-page', 'wysiwygEditorBalloon')->name('wysiwyg-editor-balloon');
        Route::get('wysiwyg-editor-balloon-block-page', 'wysiwygEditorBalloonBlock')->name('wysiwyg-editor-balloon-block');
        Route::get('wysiwyg-editor-document-page', 'wysiwygEditorDocument')->name('wysiwyg-editor-document');
        Route::get('validation-page', 'validation')->name('validation');
        Route::get('chart-page', 'chart')->name('chart');
        Route::get('slider-page', 'slider')->name('slider');
        Route::get('image-zoom-page', 'imageZoom')->name('image-zoom');
        
    });

    // Users CRUD
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users');
        Route::get('users/create', 'create')->name('users.create');
        Route::post('users', 'store')->name('users.store');
        Route::get('users/{id}/edit', 'edit')->name('users.edit');
        Route::put('users/{id}', 'update')->name('users.update');
        Route::delete('users/{id}', 'destroy')->name('users.delete');
    });

    Route::controller(ClietRateFixController::class)->group(function () {
        Route::get('client-rate-fix', 'index')->name('client-rate-fix');
        Route::get('client-rate-fix/create', 'create')->name('client-rate-fix.create');
        Route::post('client-rate-fix', 'store')->name('client-rate-fix.store');
        Route::get('client-rate-fix/{id}/edit', 'edit')->name('client-rate-fix.edit');
        Route::put('client-rate-fix/{id}', 'update')->name('client-rate-fix.update');
        Route::delete('client-rate-fix/{id}', 'destroy')->name('client-rate-fix.delete');
    });

    Route::controller(BullionRateController::class)->group(function () {
        Route::get('bullion-rate', 'index')->name('bullion-rate');
        Route::get('bullion-rate/create', 'create')->name('bullion-rate.create');
        Route::post('bullion-rate', 'store')->name('bullion-rate.store');
        Route::get('bullion-rate/{id}/edit', 'edit')->name('bullion-rate.edit');
        Route::put('bullion-rate/{id}', 'update')->name('bullion-rate.update');
        Route::delete('bullion-rate/{id}', 'destroy')->name('bullion-rate.delete');
    });

    Route::controller(BullionPurchaseController::class)->group(function () {
        Route::get('bullion-purchase', 'index')->name('bullion-purchase');
        Route::get('bullion-purchase/create', 'create')->name('bullion-purchase.create');
        Route::post('bullion-purchase', 'store')->name('bullion-purchase.store');
        Route::get('bullion-purchase/{id}/edit', 'edit')->name('bullion-purchase.edit');
        Route::put('bullion-purchase/{id}', 'update')->name('bullion-purchase.update');
        Route::delete('bullion-purchase/{id}', 'destroy')->name('bullion-purchase.delete');
    });

    Route::controller(ClientRateCutPendingController::class)->group(function () {
        Route::get('client-rate-cut-pending', 'index')->name('client-rate-cut-pending');
        Route::get('client-rate-cut-pending/create', 'create')->name('client-rate-cut-pending.create');
        Route::post('client-rate-cut-pending', 'store')->name('client-rate-cut-pending.store');
        Route::get('client-rate-cut-pending/{id}/edit', 'edit')->name('client-rate-cut-pending.edit');
        Route::put('client-rate-cut-pending/{id}', 'update')->name('client-rate-cut-pending.update');
        Route::delete('client-rate-cut-pending/{id}', 'destroy')->name('client-rate-cut-pending.delete');
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get('payment', 'index')->name('payment');
        Route::get('payment/create', 'create')->name('payment.create');
        Route::post('payment', 'store')->name('payment.store');
        Route::get('payment/{id}/edit', 'edit')->name('payment.edit');
        Route::put('payment/{id}', 'update')->name('payment.update');
        Route::delete('payment/{id}', 'destroy')->name('payment.delete');
    });

    Route::resource('client',ClientController::class);
    Route::resource('vendor',VendorController::class);

    // Products CRUD
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index')->name('products.index');
        Route::get('products/create', 'create')->name('products.create');
        Route::post('products', 'store')->name('products.store');
        Route::get('products/{id}', 'show')->name('products.show');
        Route::get('products/{id}/edit', 'edit')->name('products.edit');
        Route::put('products/{id}', 'update')->name('products.update');
        Route::delete('products/{id}', 'destroy')->name('products.delete');
    });

    // Purchase Parties CRUD
    Route::controller(PurchasePartyController::class)->group(function () {
        Route::get('purchase-parties', 'index')->name('purchase-parties.index');
        Route::get('purchase-parties/create', 'create')->name('purchase-parties.create');
        Route::post('purchase-parties', 'store')->name('purchase-parties.store');
        Route::get('purchase-parties/{id}/edit', 'edit')->name('purchase-parties.edit');
        Route::put('purchase-parties/{id}', 'update')->name('purchase-parties.update');
        Route::delete('purchase-parties/{id}', 'destroy')->name('purchase-parties.delete');
    });

    // Purchases CRUD
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('purchases', 'index')->name('purchases.index');
        Route::get('purchases/create', 'create')->name('purchases.create');
        Route::post('purchases', 'store')->name('purchases.store');
        Route::get('purchases/{id}/edit', 'edit')->name('purchases.edit');
        Route::put('purchases/{id}', 'update')->name('purchases.update');
        Route::delete('purchases/{id}', 'destroy')->name('purchases.delete');
    });
});
