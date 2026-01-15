<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\ToolAssignItem;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class InventoryCalculationController extends Controller
{
    public function index(Request $request)
    { 
        if ($request->ajax()) {
            $search = $request->get('search', '');

            $query = Product::with(['category', 'unit'])
                ->orderBy('product_name');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('barcode_number', 'like', "%{$search}%")
                    ->orWhere('tool_code', 'like', "%{$search}%");
                });
            }

            $products = $query->get();

            $inventory = [];
            foreach ($products as $product) {
                $inventory[] = $this->calculateInventory($product);
            }

            return response()->json([
                'data' => $inventory,
                'total' => count($inventory),
            ]);
        }

        $products = Product::with(['category', 'unit'])
            ->orderBy('product_name')
            ->get();

        $inventory = [];
        foreach ($products as $product) {
            $inventory[] = $this->calculateInventory($product);
        }

        $totalRemainingValue = collect($inventory)->sum('remaining_value');

        return view('pages.inventory-calculation.index', [
            'inventory' => $inventory,
            'totalRemainingValue' => $totalRemainingValue,
        ]);
    }


    private function calculateInventory($product)
    {
        // Get purchases ordered by bill_date or created_at for FIFO
        $purchases = PurchaseItem::where('product_id', $product->id)
            ->with('purchase')
            ->get()
            ->sortBy(function($pi) {
                return $pi->purchase->bill_date ?? $pi->purchase->created_at;
            });

        $totalQty = $purchases->sum('quantity');
        $totalValue = $purchases->sum('amount');

        // Get opening stock
        $openingStock = \App\Models\OpeningStock::where('product_id', $product->id)->first();
        $openingQty = $openingStock ? $openingStock->quantity : 0;
        $openingSaleRate = $openingStock ? $openingStock->sale_rate : 0;
        $openingMrp = $openingStock ? $openingStock->mrp : 0;
        $openingPurchasePrice = $openingStock ? $openingStock->purchase_price : 0;

        // Include opening stock in total quantity
        $totalQty += $openingQty;

        // Total assigned quantity across all tool assigns for this product
        $totalAssigned = ToolAssignItem::where('product_id', $product->id)->sum('quantity');

        // FIFO deduction (considering purchases and opening stock)
        $remainingQty = $totalQty;
        $remainingValue = $totalValue;
        $deducted = 0;
        foreach ($purchases as $pi) {
            if ($deducted >= $totalAssigned) break;
            $toDeduct = min($pi->quantity, $totalAssigned - $deducted);
            $remainingQty -= $toDeduct;
            $remainingValue -= ($toDeduct / $pi->quantity) * $pi->amount;
            $deducted += $toDeduct;
        }

        return [
            'product' => $product,
            'opening_stock' => [
                'qty' => $openingQty,
                'sale_rate' => $openingSaleRate,
                'mrp' => $openingMrp,
                'purchase_price' => $openingPurchasePrice,
            ],
            'total_purchased_qty' => $totalQty,
            'total_purchased_value' => $totalValue,
            'total_assigned_qty' => $totalAssigned,
            'remaining_qty' => $remainingQty,
            'remaining_value' => $remainingValue,
        ];
    }

    public function purchaseHistory(Product $product)
    {
        $purchases = PurchaseItem::where('product_id', $product->id)
            ->with('purchase')
            ->get()
            ->sortBy(function($pi) {
                return $pi->purchase->bill_date ?? $pi->purchase->created_at;
            });

        $totalQty = $purchases->sum('quantity');
        $totalValue = $purchases->sum('amount');

        return response()->json([
            'purchases' => $purchases->map(function($pi) {
                return [
                    'bill_date' => $pi->purchase->bill_date ? $pi->purchase->bill_date->format('d M Y') : 'N/A',
                    'bill_number' => $pi->purchase->bill_number ?? 'N/A',
                    'quantity' => number_format($pi->quantity, 2),
                    'rate' => number_format($pi->rate, 2),
                    'amount' => number_format($pi->amount, 2),
                ];
            }),
            'total_qty' => number_format($totalQty, 2),
            'total_value' => number_format($totalValue, 2),
        ]);
    }

    public function assignHistory(Request $request, Product $product)
    {
        try {
            $query = ToolAssignItem::where('product_id', $product->id)
                ->with(['toolAssign.department', 'employee']);

            $employeeId = $request->get('employee_id');
            if ($employeeId) {
                $query->where('emp_id', $employeeId);
            }

            $assigns = $query->get();

            $totalQty = $assigns->sum('quantity');
            $totalValue = 0;

            $assignsData = $assigns->map(function ($ai) use (&$totalValue) {

                // Safe last purchase lookup
                $lastPurchase = PurchaseItem::where('product_id', $ai->product_id)
                    ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                    ->orderByRaw('COALESCE(purchases.bill_date, purchase_items.created_at) DESC')
                    ->select('purchase_items.*')
                    ->first();

                $rate = $lastPurchase->rate ?? 0;
                $amount = $ai->quantity * $rate;
                $totalValue += $amount;

                $toolAssign = $ai->toolAssign;
                $department = $toolAssign->department ?? 'N/A';

                return [
                    'date' => $toolAssign?->date?->format('d M Y') ?? 'N/A',
                    'department' => $toolAssign?->department?->name ?? 'N/A',
                    'employee' => $ai->employee?->name ?? 'N/A',
                    'quantity' => number_format($ai->quantity, 2),
                    'rate' => number_format($rate, 2),
                    'amount' => number_format($amount, 2),
                ];
            });

            return response()->json([
                'assigns' => $assignsData,
                'total_qty' => number_format($totalQty, 2),
                'total_value' => number_format($totalValue, 2),
            ]);

        } catch (\Exception $e) {

            \Log::error('Assign History Error', [
                'product_id' => $product->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal error occurred while loading assign history.',
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    public function getInventoryData($search = '')
    {
        $query = Product::with(['category', 'unit'])->orderBy('product_name');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('barcode_number', 'like', '%' . $search . '%')
                    ->orWhere('tool_code', 'like', '%' . $search . '%');
            });
        }

        $products = $query->get();
        $inventory = [];
        foreach ($products as $product) {
            $inventory[] = $this->calculateInventory($product);
        }

        return $inventory;
    }

    public function printView(Request $request)
    {
        $search = $request->get('search', '');
        $inventory = $this->getInventoryData($search);
        return view('exports.inventory-print', compact('inventory'));
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search', '');
        return Excel::download(new InventoryExport($search), 'inventory-calculation.xlsx');
    }

}
