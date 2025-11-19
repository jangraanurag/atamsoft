<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class ReportController extends Controller
{
    
    //create a buyer report method it will show BUYERS	NUMBERS OF ORDERS	TOTAL AMOUNT	EDIT ORDERS for orders posted on this 
//in next table show posted order in table with fields ORDER NO	PHOTO	PRODUCT	BRAND	QUANTITY	PRICE	AMOUNT	BUYER NAME	DATED	TAT	SHIPMENT
//create method
    public function generateBuyerReport(Request $request)
    {
        // 1. Validate that we received an array of order IDs.
        $validated = $request->validate([
            'order_ids'   => 'required|array|min:1',
            'order_ids.*' => 'integer|exists:orders,id', // Ensure every ID exists
        ], [
            'order_ids.required' => 'Please select at least one order to generate a report.',
        ]);

        // 2. Fetch all selected orders with their relationships
        $orders = Order::with(['product', 'brand', 'buyer', 'shipper', 'status'])
            ->whereIn('id', $validated['order_ids'])
            ->orderBy('id', 'asc') // Order them by ID
            ->get();

        // 3. Calculate the summary data
        // We group by buyer to handle multiple buyers in one report
        $summary = $orders->groupBy('order_buyer')->map(function ($buyerOrders) {
            return [
                'buyer_name' => $buyerOrders->first()->buyer->buyer_name ?? 'N/A',
                'order_count' => $buyerOrders->count(),
                'total_amount' => $buyerOrders->sum('buyer_total'),
            ];
        });

        // 4. Calculate grand totals for the top box
        $totalOrders = $orders->count();
        $totalAmount = $orders->sum('buyer_total');
        // This is a guess for "GST Orders" from your screenshot. You can change this logic.
        // Let's assume GST is a flat 18% of the total amount.
        $totalGst = $totalAmount * 0.18; 

        // 5. Send all data to the new view
        return view('reports.buyerreport', [
            'orders'      => $orders,
            'summary'     => $summary,
            'totalOrders' => $totalOrders,
            'totalAmount' => $totalAmount,
            'totalGst'    => $totalGst,
        ]);
    }

    public function generateSupplierReport(Request $request)
    {
        // 1. Validate that we received an array of order IDs.
        $validated = $request->validate([
            'order_ids'   => 'required|array|min:1',
            'order_ids.*' => 'integer|exists:orders,id', // Ensure every ID exists
        ], [
            'order_ids.required' => 'Please select at least one order to generate a report.',
        ]);

        // 2. Fetch all selected orders with their relationships
        $orders = Order::with(['product', 'brand', 'buyer', 'shipper', 'status', 'supplier'])
            ->whereIn('id', $validated['order_ids'])
            ->orderBy('id', 'asc') // Order them by ID
            ->get();

        // 3. Calculate the summary data
        // We group by supplier to handle multiple suppliers in one report
        $summary = $orders->groupBy('supplier_id')->map(function ($supplierOrders) {
            return [
                'supplier_name' => $supplierOrders->first()->supplier->supplier_name ?? 'N/A',
                'order_count' => $supplierOrders->count(),
                'total_amount' => $supplierOrders->sum('supplier_total'),
            ];
        });

        // 4. Calculate grand totals for the top box
        $totalOrders = $orders->count();
        $totalAmount = $orders->sum('supplier_total');
        // Assuming GST logic is same or not needed, but keeping variable for consistency if needed
        // For supplier report, maybe we don't need GST or it's different. 
        // I'll keep it simple for now.

        // 5. Send all data to the new view
        return view('reports.supplierreport', [
            'orders'      => $orders,
            'summary'     => $summary,
            'totalOrders' => $totalOrders,
            'totalAmount' => $totalAmount,
        ]);
    }
}
