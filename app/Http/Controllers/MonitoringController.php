<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\status;
use App\Models\Supplier;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get status filter, default to 'Pending'
        // Note: The user requested "by default show pending orders data"
        $statusFilter = $request->input('status', 'Pending');

        // 2. Base query
        $query = Order::query();

        // 3. Apply filter
        if ($statusFilter !== 'All') {
             $statusId = status::where('status_name', $statusFilter)->value('id');
             if ($statusId) {
                 $query->where('order_status', $statusId);
             } else {
                 // If status name not found (e.g. if user manually types something), maybe fallback or show empty?
                 // For now, if status not found, it might return empty results which is fine.
                 // But if 'Pending' is not found in DB, we should be careful. 
                 // Assuming 'Pending' exists as per user request history.
             }
        }

        // 4. Get orders with relationships
        $orders = $query->with(['buyer', 'supplier'])->get();

        // 5. Calculate Top Box Totals
        // Total Buyer Amount
        $totalBuyerAmount = $orders->sum('buyer_total');
        // No of Buyers
        $totalBuyers = $orders->unique('order_buyer')->count();
        
        // Total Supplier Amount
        $totalSupplierAmount = $orders->sum('supplier_total');
        // No of Suppliers
        $totalSuppliers = $orders->unique('supplier_id')->count();

        // 6. Supplier List Data
        // Group by supplier
        $totalOrdersCount = $orders->count(); // For percentage calculation

        $supplierData = $orders->groupBy('supplier_id')->map(function ($supplierOrders) use ($totalOrdersCount) {
            $supplier = $supplierOrders->first()->supplier;
            $supplierName = $supplier->name ?? 'N/A'; 
            $orderCount = $supplierOrders->count();
            
            // Work Load %
            $workLoad = $totalOrdersCount > 0 ? ($orderCount / $totalOrdersCount) * 100 : 0;

            return [
                'supplier_name' => $supplierName,
                'work_load' => $workLoad,
                'total_orders' => $orderCount,
                'supplier_amount' => $supplierOrders->sum('supplier_total'),
            ];
        })->values();

        // 7. Get all statuses for the filter dropdown
        $statuses = status::all();

        return view('monitoring.index', compact(
            'orders', 
            'totalBuyerAmount', 
            'totalBuyers', 
            'totalSupplierAmount', 
            'totalSuppliers', 
            'supplierData', 
            'statuses', 
            'statusFilter'
        ));
    }
}
