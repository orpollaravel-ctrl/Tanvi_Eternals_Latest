<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Department;
use App\Models\ToolAssign;
use App\Models\Bullion;
use App\Models\BullionRateFix;
use App\Models\DealerRateFix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Show specified view.
     *
     */
    public function CustomerDashboard(): View
    { 
        return view('dashboard/customer-dashboard');
    }

    public function dashboardOverview1(): View
    {
        $today = now()->format('Y-m-d');

        $departments = Department::all();
        $summary = [];

        foreach ($departments as $dept) { 
            $todayTotal = ToolAssign::where('d_id', $dept->id)
                ->whereDate('date', $today)
                ->with('items.product.purchaseItems')
                ->get()
                ->sum(function ($assign) {
                    return $assign->items->sum(function ($item) {
                        $product = $item->product;
                        if (!$product) return 0;
                        $avgRate = optional($product->purchaseItems)->avg('rate') ?? 0;
                        return $item->quantity * $avgRate;
                    });
                });
            $monthTotal = ToolAssign::where('d_id', $dept->id)
                ->with('items.product.purchaseItems')
                ->get()
                ->sum(function ($assign) {
                    return $assign->items->sum(function ($item) {
                        $product = $item->product;
                        if (!$product) return 0;

                        $avgRate = optional($product->purchaseItems)->avg('rate') ?? 0;

                        return $item->quantity * $avgRate;
                    });
                });

            $summary[] = [
                'department_name' => $dept->name,
                'today_amount'    => $todayTotal,
                'month_amount'    => $monthTotal,
            ];
        }

        return view('dashboard/dashboard-overview-1', [
            'summary' => $summary,
            'departments' => $departments
        ]);
    }

	
	public function Bulliondashboard(): View
    {
       $drf = DealerRateFix::query()
            ->leftJoin('deals', 'dealer_rate_fixes.id', 'deals.dealer_rate_fix_id')
            ->select(DB::raw('
                dealer_rate_fixes.quantity 
                - sum(IFNULL(deals.quantity,0)) as pending,
                (dealer_rate_fixes.quantity 
                - sum(IFNULL(deals.quantity,0))) * 0.10 * dealer_rate_fixes.rate as pending_amt
            '))
            ->havingRaw('pending > 0')
            ->groupBy('dealer_rate_fixes.id')
            ->get();

        // $brf=DB::table('bullion_rate_fixes')
        // $brf_deals = DB::table('deals')->select('bullion_rate_fix_id', DB::raw('sum(IFNULL(quantity,0)) as qty'))->groupBy('bullion_rate_fix_id');
        // $brf = DB::table('bullion_rate_fixes')->select(DB::raw('SUM(bullion_rate_fixes.quantity-qty) as pending,SUM((bullion_rate_fixes.quantity-qty)*bullion_rate_fixes.rate) as amt'))
        //     ->joinSub($brf_deals, 'deals', function ($join) {
        //         $join->on('bullion_rate_fixes.id', '=', 'deals.bullion_rate_fix_id');                
        //     })->get();

        $brf = BullionRateFix::query()->leftJoin('deals', 'bullion_rate_fixes.id', 'deals.bullion_rate_fix_id')
            ->select(DB::raw('bullion_rate_fixes.quantity - sum(IFNULL(deals.quantity,0)) as pending,(bullion_rate_fixes.quantity - sum(IFNULL(deals.quantity,0)))*0.10*bullion_rate_fixes.rate as pending_amt'))
            ->havingRaw('pending > 0')
            ->groupby('bullion_rate_fixes.id')
            ->get();
        $bullions = Bullion::withSum('bullionRateFixes as brf_quantity', 'quantity')
            ->addSelect(['pending_amount' => function ($query) {
                $query->select(DB::raw('SUM(transactions.quantity*0.10*bullion_rate_fixes.rate)-IFNULL((select sum(IFNULL(amount,0)) from payment_transaction where transaction_id=transactions.id GROUP BY transaction_id),0)'))
                    ->from('transactions')
                    ->leftJoin('bullion_rate_fixes', 'bullion_rate_fixes.id', 'transactions.bullion_rate_fix_id')
                    ->whereColumn('bullion_rate_fixes.bullion_id', 'bullions.id')
                    ->groupBy('transactions.id',)
                    ->limit(1);
            }])
            ->get();
        $bullions->loadSum('bullionRateFixes as brf_amount', 'amount');
        $bullions->loadSum('receipts as mr_quantity', 'quantity');
        $bullions->loadSum('payments as payment_amount', 'amount');
		
		 $dealerQty  = $drf->sum('pending');
        $dealerAmt  = $drf->sum('pending_amt');

        $dealerAvrg = $drf->count() > 0 ? $dealerAmt / $dealerQty : 0;

        $bullionQty = $brf->sum('pending');
        $bullionAmt = $brf->sum('pending_amt');
        $bullionAvrg = $brf->count() > 0 ? $bullionAmt / $bullionQty : 0;

        return view('dashboard/bullion', [
            'bullions' => $bullions,
            'brf' => $brf,
            'drf' => $drf,
			'dealerQty' => $dealerQty,
            'dealerAmt' => $dealerAmt,
            'bullionQty' => $bullionQty,
            'bullionAmt' => $bullionAmt,
            'dealerAvrg' => $dealerAvrg,
            'bullionAvrg' => $bullionAvrg,
        ]);
    }
    /**
     * Show specified view.
     *
     */
    public function dashboardOverview2(): View
    {
        return view('pages/dashboard-overview-2');
    }

    /**
     * Show specified view.
     *
     */
    public function dashboardOverview3(): View
    {
        return view('pages/dashboard-overview-3');
    }

    /**
     * Show specified view.
     *
     */
    public function dashboardOverview4(): View
    {
        return view('pages/dashboard-overview-4');
    }

    /**
     * Show specified view.
     *
     */
    public function inbox(): View
    {
        return view('pages/inbox');
    }

    /**
     * Show specified view.
     *
     */
    public function categories(): View
    {
        return view('pages/categories');
    }

    /**
     * Show specified view.
     *
     */
    public function addProduct(): View
    {
        return view('pages/add-product');
    }

    /**
     * Show specified view.
     *
     */
    public function productList(): View
    {
        return view('pages/product-list');
    }

    /**
     * Show specified view.
     *
     */
    public function productGrid(): View
    {
        return view('pages/product-grid');
    }

    /**
     * Show specified view.
     *
     */
    public function transactionList(): View
    {
        return view('pages/transaction-list');
    }

    /**
     * Show specified view.
     *
     */
    public function transactionDetail(): View
    {
        return view('pages/transaction-detail');
    }

    /**
     * Show specified view.
     *
     */
    public function sellerList(): View
    {
        return view('pages/seller-list');
    }

    /**
     * Show specified view.
     *
     */
    public function sellerDetail(): View
    {
        return view('pages/seller-detail');
    }

    /**
     * Show specified view.
     *
     */
    public function reviews(): View
    {
        return view('pages/reviews');
    }

    /**
     * Show specified view.
     *
     */
    public function fileManager(): View
    {
        return view('pages/file-manager');
    }

    /**
     * Show specified view.
     *
     */
    public function pointOfSale(): View
    {
        return view('pages/point-of-sale');
    }

    /**
     * Show specified view.
     *
     */
    public function chat(): View
    {
        return view('pages/chat');
    }

    /**
     * Show specified view.
     *
     */
    public function post(): View
    {
        return view('pages/post');
    }

    /**
     * Show specified view.
     *
     */
    public function calendar(): View
    {
        return view('pages/calendar');
    }

    /**
     * Show specified view.
     *
     */
    public function crudDataList(): View
    {
        return view('pages/crud-data-list');
    }

    /**
     * Show specified view.
     *
     */
    public function crudForm(): View
    {
        return view('pages/crud-form');
    }

    public function users(): View
    {
        return view('pages/user');
    }

    /**
     * Show specified view.
     *
     */
    public function usersLayout1(): View
    {
        return view('pages/users-layout-1');
    }

    /**
     * Show specified view.
     *
     */
    public function usersLayout2(): View
    {
        return view('pages/users-layout-2');
    }

    /**
     * Show specified view.
     *
     */
    public function usersLayout3(): View
    {
        return view('pages/users-layout-3');
    }

    /**
     * Show specified view.
     *
     */
    public function profileOverview1(): View
    {
        return view('pages/profile-overview-1');
    }

    /**
     * Show specified view.
     *
     */
    public function profileOverview2(): View
    {
        return view('pages/profile-overview-2');
    }

    /**
     * Show specified view.
     *
     */
    public function profileOverview3(): View
    {
        return view('pages/profile-overview-3');
    }

    /**
     * Show specified view.
     *
     */
    public function wizardLayout1(): View
    {
        return view('pages/wizard-layout-1');
    }

    /**
     * Show specified view.
     *
     */
    public function wizardLayout2(): View
    {
        return view('pages/wizard-layout-2');
    }

    /**
     * Show specified view.
     *
     */
    public function wizardLayout3(): View
    {
        return view('pages/wizard-layout-3');
    }

    /**
     * Show specified view.
     *
     */
    public function blogLayout1(): View
    {
        return view('pages/blog-layout-1');
    }

    /**
     * Show specified view.
     *
     */
    public function blogLayout2(): View
    {
        return view('pages/blog-layout-2');
    }

    /**
     * Show specified view.
     *
     */
    public function blogLayout3(): View
    {
        return view('pages/blog-layout-3');
    }

    /**
     * Show specified view.
     *
     */
    public function pricingLayout1(): View
    {
        return view('pages/pricing-layout-1');
    }

    /**
     * Show specified view.
     *
     */
    public function pricingLayout2(): View
    {
        return view('pages/pricing-layout-2');
    }

    /**
     * Show specified view.
     *
     */
    public function invoiceLayout1(): View
    {
        return view('pages/invoice-layout-1');
    }

    /**
     * Show specified view.
     *
     */
    public function invoiceLayout2(): View
    {
        return view('pages/invoice-layout-2');
    }

    /**
     * Show specified view.
     *
     */
    public function faqLayout1(): View
    {
        return view('pages/faq-layout-1');
    }

    /**
     * Show specified view.
     *
     */
    public function faqLayout2(): View
    {
        return view('pages/faq-layout-2');
    }

    /**
     * Show specified view.
     *
     */
    public function faqLayout3(): View
    {
        return view('pages/faq-layout-3');
    }

    /**
     * Show specified view.
     *
     */
    public function login(): View
    {
        return view('login/main', [
            'layout' => 'base'
        ]);
    }

    /**
     * Show specified view.
     *
     */
    public function register(): View
    {
        return view('pages/register');
    }

    /**
     * Show specified view.
     *
     */
    public function errorPage(): View
    {
        return view('pages/error-page');
    }

    /**
     * Show specified view.
     *
     */
    public function updateProfile(): View
    {
        return view('pages/update-profile', [
            'layout' => 'side-menu',
            'user' => auth()->user(),
        ]);
    }

    public function updateProfileCustomer(): View
    {
        return view('pages/update-profile', [
            'layout' => 'side-menu',
            'user' => auth('client')->user(),
        ]);
    }

    /**
     * Show specified view.
     *
     */
    public function changePassword(): View
    {
        return view('pages/change-password');
    }

    /**
     * Update user profile.
     */
    public function updateProfilePost(Request $request)
    {
        $user = auth()->user();
        
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
            $fileName = $file->getClientOriginalName();
            $filePath = 'uploads/user';
            $file->move(public_path($filePath), $fileName);
            $user->photo = $fileName;
        }

        $user->save();

        return redirect()->route('update-profile')->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePostCustomer(Request $request)
    {
        $user = auth('client')->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients,email,' . $user->id],
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
            $fileName = $file->getClientOriginalName();
            $filePath = 'uploads/user';
            $file->move(public_path($filePath), $fileName);
            $user->photo = $fileName;
        }

        $user->save();

        return redirect()->route('customer.update-profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show specified view.
     *
     */
    public function regularTable(): View
    {
        return view('pages/regular-table');
    }

    /**
     * Show specified view.
     *
     */
    public function tabulator(): View
    {
        return view('pages/tabulator');
    }

    /**
     * Show specified view.
     *
     */
    public function modal(): View
    {
        return view('pages/modal');
    }

    /**
     * Show specified view.
     *
     */
    public function slideOver(): View
    {
        return view('pages/slide-over');
    }

    /**
     * Show specified view.
     *
     */
    public function notification(): View
    {
        return view('pages/notification');
    }

    /**
     * Show specified view.
     *
     */
    public function tab(): View
    {
        return view('pages/tab');
    }

    /**
     * Show specified view.
     *
     */
    public function accordion(): View
    {
        return view('pages/accordion');
    }

    /**
     * Show specified view.
     *
     */
    public function button(): View
    {
        return view('pages/button');
    }

    /**
     * Show specified view.
     *
     */
    public function alert(): View
    {
        return view('pages/alert');
    }

    /**
     * Show specified view.
     *
     */
    public function progressBar(): View
    {
        return view('pages/progress-bar');
    }

    /**
     * Show specified view.
     *
     */
    public function tooltip(): View
    {
        return view('pages/tooltip');
    }

    /**
     * Show specified view.
     *
     */
    public function dropdown(): View
    {
        return view('pages/dropdown');
    }

    /**
     * Show specified view.
     *
     */
    public function typography(): View
    {
        return view('pages/typography');
    }

    /**
     * Show specified view.
     *
     */
    public function icon(): View
    {
        return view('pages/icon');
    }

    /**
     * Show specified view.
     *
     */
    public function loadingIcon(): View
    {
        return view('pages/loading-icon');
    }

    /**
     * Show specified view.
     *
     */
    public function regularForm(): View
    {
        return view('pages/regular-form');
    }

    /**
     * Show specified view.
     *
     */
    public function datepicker(): View
    {
        return view('pages/datepicker');
    }

    /**
     * Show specified view.
     *
     */
    public function tomSelect(): View
    {
        return view('pages/tom-select');
    }

    /**
     * Show specified view.
     *
     */
    public function fileUpload(): View
    {
        return view('pages/file-upload');
    }

    /**
     * Show specified view.
     *
     */
    public function wysiwygEditorClassic(): View
    {
        return view('pages/wysiwyg-editor-classic');
    }

    /**
     * Show specified view.
     *
     */
    public function wysiwygEditorInline(): View
    {
        return view('pages/wysiwyg-editor-inline');
    }

    /**
     * Show specified view.
     *
     */
    public function wysiwygEditorBalloon(): View
    {
        return view('pages/wysiwyg-editor-balloon');
    }

    /**
     * Show specified view.
     *
     */
    public function wysiwygEditorBalloonBlock(): View
    {
        return view('pages/wysiwyg-editor-balloon-block');
    }

    /**
     * Show specified view.
     *
     */
    public function wysiwygEditorDocument(): View
    {
        return view('pages/wysiwyg-editor-document');
    }

    /**
     * Show specified view.
     *
     */
    public function validation(): View
    {
        return view('pages/validation');
    }

    /**
     * Show specified view.
     *
     */
    public function chart(): View
    {
        return view('pages/chart');
    }

    /**
     * Show specified view.
     *
     */
    public function slider(): View
    {
        return view('pages/slider');
    }

    /**
     * Show specified view.
     *
     */
    public function imageZoom(): View
    {
        return view('pages/image-zoom');
    }

    public function profile()
    {
        return view('customer.profile', [
            'client' => auth('client')->user()
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $client = auth('client')->user();

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', 'unique:clients,email,' . $client->id],
            'mobile_number'  => ['nullable', 'string', 'max:20'],
            'photo'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $client->name  = $validated['name'];
        $client->email = $validated['email'];
        $client->mobile_number = $validated['mobile_number'] ?? null;

        if ($request->hasFile('photo')) {
            if ($client->photo && file_exists(public_path('uploads/client/' . $client->photo))) {
                unlink(public_path('uploads/client/' . $client->photo));
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/client';

            $file->move(public_path($filePath), $fileName);

            $client->photo = $fileName;
        }

        $client->save();

        return back()->with('success', 'Profile updated successfully');
    }

}
