<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Buyer;
use App\Models\d_unit;
use App\Models\LabelType;
use App\Models\Order;
use App\Models\Product;
use App\Models\shipper;
use App\Models\status;
use App\Models\supplier;
use App\Models\unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new order.
     *
     * @return \Illuminate\View\View
     */
    public function new()
    {
        // Fetch all necessary data for the dropdowns in the form.
        $products = Product::orderBy('product_name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $buyers = Buyer::orderBy('buyer_name')->get();
        $d_units = d_unit::orderBy('dunit_name')->get();
        $labelTypes = LabelType::orderBy('labeltype_name')->get();
        $shippers = shipper::orderBy('shipper_name')->get();
        $statuses = status::orderBy('status_name')->get();
        $units = unit::orderBy('unit_name')->get();
        $suppliers = supplier::orderBy('name')->get();

        return view('order.new', compact(
            'products', 'brands', 'buyers', 'd_units', 'labelTypes', 
            'shippers', 'statuses', 'units', 'suppliers'
        ));
    }

    /**
     * Store a newly created order in storage.
     */
    public function create(Request $request)
    {
        // Validate the incoming request data against the form fields.
        $validatedData = $request->validate([
            'order_buyer' => 'required|exists:buyers,id',
            'order_brand' => 'nullable|exists:brands,id',
            'order_productName' => 'nullable|exists:products,id',
            'order_labelType' => 'nullable|exists:label_types,id',
            'order_date' => 'nullable|date',
            'order_deliverydate' => 'nullable|date',
            'order_qty' => 'nullable|numeric',
            'order_manufacturedqty' => 'nullable|numeric',
            'order_unit' => 'nullable|exists:units,id',
            'order_weight' => 'nullable|numeric',
            'order_length' => 'nullable|numeric',
            'order_width' => 'nullable|numeric',
            'order_d_unit' => 'nullable|exists:d_units,id',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string|max:100', // Validate each color
            'color_qtys' => 'nullable|array', // For color quantities
            'color_qtys.*' => 'nullable|numeric', // For color quantities
            'order_shipvia' => 'nullable|exists:shippers,id',
            'order_shipmentno' => 'nullable|string|max:100',
            'order_status' => 'nullable|exists:statuses,id',
            'order_salesman' => 'nullable|string|max:100',
            'order_nailmaterial' => 'nullable|string|max:255',
            'dieset' => 'nullable|boolean',
            'order_logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'order_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'order_cdr' => 'nullable|file|max:5120', // 5MB max
            'order_po' => 'nullable|file|max:5120',
            'order_price' => 'nullable|numeric',
            'order_mouldprice' => 'nullable|numeric',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_price' => 'nullable|numeric',
            'supplier_mould_price' => 'nullable|numeric',
            'bill_to_name' => 'nullable|string|max:255',
            'bill_to_address_line_1' => 'nullable|string|max:255',
            'bill_to_address_line_2' => 'nullable|string|max:255',
            'bill_to_city' => 'nullable|string|max:100',
            'bill_to_state' => 'nullable|string|max:100',
            'bill_to_postal_code' => 'nullable|string|max:20',
            'bill_to_country' => 'nullable|string|max:100',
            'ship_to_name' => 'nullable|string|max:255',
            'ship_to_address_line_1' => 'nullable|string|max:255',
            'ship_to_address_line_2' => 'nullable|string|max:255',
            'ship_to_city' => 'nullable|string|max:100',
            'ship_to_state' => 'nullable|string|max:100',
            'ship_to_postal_code' => 'nullable|string|max:20',
            'ship_to_country' => 'nullable|string|max:100',
            'order_remark' => 'nullable|string',
            'buyer_remark' => 'nullable|string',
            'supplier_remark' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('order_logo')) {
            $validatedData['order_logo'] = $request->file('order_logo')->store('logos', 'public');
        }
        if ($request->hasFile('order_image')) {
            $validatedData['order_image'] = $request->file('order_image')->store('order_images', 'public');
        }
        if ($request->hasFile('order_cdr')) {
            $validatedData['order_cdr'] = $request->file('order_cdr')->store('order_files', 'public');
        }
        if ($request->hasFile('order_po')) {
            $validatedData['order_po'] = $request->file('order_po')->store('order_files', 'public');
        }

        // Handle colors (with qty)
        if ($request->has('colors')) {
            $colorsData = [];
            foreach ($request->colors as $index => $colorName) {
                if (!empty($colorName)) {
                    $colorsData[] = [
                        'color' => $colorName,
                        'qty' => $request->color_qtys[$index] ?? null
                    ];
                }
            }
            $validatedData['colors'] = $colorsData; // Already an array, will be cast to JSON
        }

        // Handle the 'dieset' checkbox.
        $validatedData['dieset'] = $request->has('dieset');

        // Recalculate totals
        $validatedData['buyer_total'] = ($validatedData['order_price'] ?? 0) + ($validatedData['order_mouldprice'] ?? 0);
        $validatedData['supplier_total'] = ($validatedData['supplier_price'] ?? 0) + ($validatedData['supplier_mould_price'] ?? 0);

        // Create the order
        Order::create($validatedData);

        return redirect()->route('order.index')->with('success', 'Order created successfully!');
    }

    /**
     * Display the consolidated order management page.
     */
    public function index(Request $request)
    {
        // 1. Get the status from the URL (e.g., ?status=pending)
        $filterStatus = $request->get('status');
        
        // 2. Get all statuses for dropdowns
        $statuses = Status::all();
        
        // 3. Get all suppliers for dropdowns
        $suppliers = Supplier::orderBy('name')->get();

        // 4. Create a base query for the main table, respecting the filter
        $orderQuery = Order::query();

        if ($filterStatus && $filterStatus !== 'all') {
            $statusId = Status::where('status_name', $filterStatus)->value('id');
            if ($statusId) {
                $orderQuery->where('order_status', $statusId);
            }
        }
        
        // 5. Fetch orders for the main table and reports
        $ordersForTable = $orderQuery->with(['buyer', 'supplier'])->get();

        // 6. Create a separate query JUST for the "Pending" summary
        $pendingStatusId = Status::where('status_name', 'pending')->value('id');
        $pendingOrders = Order::where('order_status', $pendingStatusId)->get();

        // 7. Aggregate data for the buyer summary table
        $buyerReports = $ordersForTable->groupBy('order_buyer')->map(function ($buyerOrders) {
            $buyer = $buyerOrders->first()->buyer;
            return [
                'buyer_name' => $buyer->buyer_name ?? 'N/A',
                'number_of_orders' => $buyerOrders->count(),
                'total_amount' => $buyerOrders->sum('buyer_total'),
                'buyer_id' => $buyer->id ?? null,
            ];
        })->values();

        // 8. Aggregate data for the supplier summary table
        $supplierReports = $ordersForTable->whereNotNull('supplier_id')->groupBy('supplier_id')->map(function ($supplierOrders) {
            $supplier = $supplierOrders->first()->supplier;
            if (!$supplier) return null; // Skip if supplier is somehow missing
            return [
                'supplier_name' => $supplier->name ?? 'N/A',
                'number_of_orders' => $supplierOrders->count(),
                'total_amount' => $supplierOrders->sum('supplier_total'),
                'supplier_id' => $supplier->id ?? null,
            ];
        })->filter()->values();

        // 9. Pass ALL data to the view, using the correct collections
        return view('order.index', compact(
            'pendingOrders', // Used for the summary boxes
            'ordersForTable', // Used for the report tables
            'statuses', 
            'suppliers', 
            'buyerReports', 
            'filterStatus',
            'supplierReports'
        ));
    }

    /**
     * Display the specified order (PO Page).
     */
    public function show($id)
    {
        $order = Order::with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status', 'supplier'])->findOrFail($id);
        return view('order.show', compact('order'));
    }

    /**
     * Display multiple orders for printing (Bulk PO).
     */
    public function bulkPo(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $orders = Order::with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status', 'supplier'])
            ->whereIn('id', $request->order_ids)
            ->get();

        return view('order.print_bulk', compact('orders'));
    }

    /**
     * Show the form for editing the specified order in a modal.
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        
        // Fetch all data for dropdowns, just like your new() method
        $products = Product::orderBy('product_name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $buyers = Buyer::orderBy('buyer_name')->get();
        $d_units = d_unit::orderBy('dunit_name')->get();
        $labelTypes = LabelType::orderBy('labeltype_name')->get();
        $shippers = shipper::orderBy('shipper_name')->get();
        $statuses = status::orderBy('status_name')->get();
        $units = unit::orderBy('unit_name')->get();
        $suppliers = supplier::orderBy('name')->get();

        // This view ONLY contains the form, no layout.
        return view('order.edit', compact(
            'order', 'products', 'brands', 'buyers', 'd_units', 
            'labelTypes', 'shippers', 'statuses', 'units', 'suppliers'
        ));
    }

    /**
     * Update the specified order in storage from the edit modal.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Use the same validation as your create() method
        $validatedData = $request->validate([
            'order_buyer' => 'required|exists:buyers,id',
            'order_brand' => 'nullable|exists:brands,id',
            'order_productName' => 'nullable|exists:products,id',
            'order_labelType' => 'nullable|exists:label_types,id',
            'order_date' => 'nullable|date',
            'order_deliverydate' => 'nullable|date',
            'order_qty' => 'nullable|numeric',
            'order_manufacturedqty' => 'nullable|numeric',
            'order_unit' => 'nullable|exists:units,id',
            'order_weight' => 'nullable|numeric',
            'order_length' => 'nullable|numeric',
            'order_width' => 'nullable|numeric',
            'order_d_unit' => 'nullable|exists:d_units,id',
            
            'colors' => 'nullable|array', 
            'colors.*' => 'nullable|string|max:100',
            'color_qtys' => 'nullable|array',
            'color_qtys.*' => 'nullable|numeric',

            'order_shipvia' => 'nullable|exists:shippers,id',
            'order_shipmentno' => 'nullable|string|max:100',
            'order_status' => 'nullable|exists:statuses,id',
            'order_salesman' => 'nullable|string|max:100',
            'order_nailmaterial' => 'nullable|string|max:255',
            'dieset' => 'nullable|boolean',
            'order_logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'order_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'order_cdr' => 'nullable|file|max:5120',
            'order_po' => 'nullable|file|max:5120',
            'order_price' => 'nullable|numeric',
            'order_mouldprice' => 'nullable|numeric',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_price' => 'nullable|numeric',
            'supplier_mould_price' => 'nullable|numeric',
            'bill_to_name' => 'nullable|string|max:255',
            'bill_to_address_line_1' => 'nullable|string|max:255',
            'bill_to_address_line_2' => 'nullable|string|max:255',
            'bill_to_city' => 'nullable|string|max:100',
            'bill_to_state' => 'nullable|string|max:100',
            'bill_to_postal_code' => 'nullable|string|max:20',
            'bill_to_country' => 'nullable|string|max:100',
            'ship_to_name' => 'nullable|string|max:255',
            'ship_to_address_line_1' => 'nullable|string|max:255',
            'ship_to_address_line_2' => 'nullable|string|max:255',
            'ship_to_city' => 'nullable|string|max:100',
            'ship_to_state' => 'nullable|string|max:100',
            'ship_to_postal_code' => 'nullable|string|max:20',
            'ship_to_country' => 'nullable|string|max:100',
            'order_remark' => 'nullable|string',
            'buyer_remark' => 'nullable|string',
            'supplier_remark' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('order_logo')) {
            if ($order->order_logo) Storage::disk('public')->delete($order->order_logo);
            $validatedData['order_logo'] = $request->file('order_logo')->store('logos', 'public');
        }
        if ($request->hasFile('order_image')) {
            if ($order->order_image) Storage::disk('public')->delete($order->order_image);
            $validatedData['order_image'] = $request->file('order_image')->store('order_images', 'public');
        }
        if ($request->hasFile('order_cdr')) {
            if ($order->order_cdr) Storage::disk('public')->delete($order->order_cdr);
            $validatedData['order_cdr'] = $request->file('order_cdr')->store('order_files', 'public');
        }
        if ($request->hasFile('order_po')) {
            if ($order->order_po) Storage::disk('public')->delete($order->order_po);
            $validatedData['order_po'] = $request->file('order_po')->store('order_files', 'public');
        }

        // Handle colors (with qty)
        if ($request->has('colors')) {
            $colorsData = [];
            foreach ($request->colors as $index => $colorName) {
                if (!empty($colorName)) {
                    $colorsData[] = [
                        'color' => $colorName,
                        'qty' => $request->color_qtys[$index] ?? null
                    ];
                }
            }
            $validatedData['colors'] = $colorsData;
        }

        // Handle checkbox
        $validatedData['dieset'] = $request->has('dieset');

        // Recalculate totals
        $validatedData['buyer_total'] = ($validatedData['order_price'] ?? $order->order_price) + ($validatedData['order_mouldprice'] ?? $order->order_mouldprice);
        $validatedData['supplier_total'] = ($validatedData['supplier_price'] ?? $order->supplier_price) + ($validatedData['supplier_mould_price'] ?? $order->supplier_mould_price);

        // Update the order
        $order->update($validatedData);

        return redirect()->route('order.index')->with('success', 'Order #' . $order->id . ' updated successfully!');
    }


    /**
     * Handle the inline-editing form submission.
     */
    public function updateStatus(Request $request)
    {
         $request->validate([
            'updates' => 'required|array',
            'order_ids' => 'required|array', // Make sure we have selected orders
        ], [
            'updates.required' => 'No data was submitted.',
            'order_ids.required' => 'Please select at least one order to update.',
        ]);

        $updates = $request->input('updates', []);
        $selectedIds = $request->input('order_ids', []);
        $errors = [];
        $updatedCount = 0;

        foreach ($updates as $orderId => $data) {
            // Only update orders that were actually selected
            if (!in_array($orderId, $selectedIds)) {
                continue;
            }
            
            $order = Order::find($orderId);

            if (!$order) {
                $errors[] = "Order with ID #{$orderId} could not be found.";
                continue; 
            }

            // Create a validator for the data
            $validator = Validator::make($data, [
                'order_status' => 'nullable|exists:statuses,id',
                'supplier_id' => 'nullable|exists:suppliers,id', 
                'order_manufacturedqty' => 'nullable|numeric|min:0',
                'order_date' => 'nullable|date', 
                'order_deliverydate' => 'nullable|date',
                'order_weight' => 'nullable|numeric|min:0',
                'order_price' => 'nullable|numeric|min:0',
                'order_mouldprice' => 'nullable|numeric|min:0',
                'supplier_price' => 'nullable|numeric|min:0',
                'supplier_mould_price' => 'nullable|numeric|min:0',
                'buyer_total' => 'nullable|numeric|min:0',
                'supplier_total' => 'nullable|numeric|min:0',
                'order_remark' => 'nullable|string|max:1000',
            ]);

            // 4. If validation fails, collect errors
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $errors[] = "Error on Order #{$orderId}: {$error}";
                }
                continue; 
            }

            // 5. Update the order
            $validatedData = $validator->validated();
            
            // Recalculate totals (client-side calculation is just for show)
            if (isset($validatedData['order_price']) || isset($validatedData['order_mouldprice'])) {
                $price = $validatedData['order_price'] ?? $order->order_price;
                $mould = $validatedData['order_mouldprice'] ?? $order->order_mouldprice;
                $validatedData['buyer_total'] = $price + $mould;
            }
            if (isset($validatedData['supplier_price']) || isset($validatedData['supplier_mould_price'])) {
                $price = $validatedData['supplier_price'] ?? $order->supplier_price;
                $mould = $validatedData['supplier_mould_price'] ?? $order->supplier_mould_price;
                $validatedData['supplier_total'] = $price + $mould;
            }
            
            $order->update($validatedData);
            $updatedCount++;
        }

        // Redirect back
        if (count($errors) > 0) {
            return back()->withErrors($errors)->with('message', 'Some orders could not be updated.');
        }
        return back()->with('message', "Successfully updated {$updatedCount} orders.");
    }
    
    /**
     * Remove the specified order from storage.
     */
    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return back()->with('success', 'Order deleted successfully!');
    }


    /**
     * This is the NEW and ONLY data method for the DataTables.
     */
/**
 * This is the NEW and ONLY data method for the DataTables.
 */
public function getOrdersData(Request $request)
{
    // --- 1. Get Parameters from DataTables ---
    $draw = $request->input('draw');
    $start = $request->input('start');
    $length = $request->input('length');
    $searchValue = $request->input('search.value');
    $orderColumnIndex = $request->input('order.0.column');
    $orderColumnName = $request->input("columns.{$orderColumnIndex}.data", 'id');
    $orderDir = $request->input('order.0.dir', 'desc');
    $filterStatusName = $request->input('status'); 

    // --- 2. Base Query ---
    $query = Order::with(['product', 'brand', 'buyer', 'unit', 'd_unit', 'shipper', 'status', 'supplier']);

    // --- 3. Status Filter ---
    if ($filterStatusName && $filterStatusName !== 'all') {
        $statusId = Status::where('status_name', $filterStatusName)->value('id');
        if ($statusId) {
            $query->where('order_status', $statusId);
        }
    }

    // --- 4. Total Records ---
    $recordsTotal = $query->count();

    // --- 5. Global Search ---
    if (!empty($searchValue)) {
        $query->where(function ($q) use ($searchValue) {
            $q->where('id', 'like', "%{$searchValue}%")
              ->orWhere('order_remark', 'like', "%{$searchValue}%")
              ->orWhere('order_shipmentno', 'like', "%{$searchValue}%") // This is the new line
              ->orWhereHas('product', function ($subQ) use ($searchValue) {
                  $subQ->where('product_name', 'like', "%{$searchValue}%");
              })
              ->orWhereHas('brand', function ($subQ) use ($searchValue) {
                  $subQ->where('brand_name', 'like', "%{$searchValue}%");
              })
              ->orWhereHas('buyer', function ($subQ) use ($searchValue) {
                  $subQ->where('buyer_name', 'like', "%{$searchValue}%");
              })
              ->orWhereHas('supplier', function ($subQ) use ($searchValue) {
                  $subQ->where('name', 'like', "%{$searchValue}%");
              });
        });
    }

    // --- 6. Filtered Count ---
    $recordsFiltered = $query->count();

    // --- 7. Sorting & Pagination ---
    $query->select('orders.*'); // Explicitly select from orders table to avoid ambiguous columns

    // Define columns that belong to the `orders` table for direct SQL sorting
    $orderTableColumns = ['id', 'order_date', 'order_deliverydate', 'order_qty', 'order_price', 'buyer_total', 'supplier_total'];

    if (in_array($orderColumnName, $orderTableColumns)) {
        // Sort by a column in the 'orders' table
        $query->orderBy('orders.' . $orderColumnName, $orderDir);
    } elseif ($orderColumnName == 'product_name') {
        $query->leftJoin('products', 'orders.order_productName', '=', 'products.id')
              ->orderBy('products.product_name', $orderDir);
    } elseif ($orderColumnName == 'buyer_name') {
        $query->leftJoin('buyers', 'orders.order_buyer', '=', 'buyers.id')
              ->orderBy('buyers.buyer_name', $orderDir);
    } elseif ($orderColumnName == 'supplier_name') {
        $query->leftJoin('suppliers', 'orders.supplier_id', '=', 'suppliers.id')
              ->orderBy('suppliers.name', $orderDir);
    } elseif ($orderColumnName == 'status_name') {
        $query->leftJoin('statuses', 'orders.order_status', '=', 'statuses.id')
              ->orderBy('statuses.status_name', $orderDir);
    } else {
        // Default sort for any other case
        $query->orderBy('orders.id', 'desc');
    }

    // --- 8. Execute Query ---
    $orders = $query->skip($start)->take($length)->get();

    // --- 9. Format Data - MUST MATCH CLIENT-SIDE COLUMNS EXACTLY ---
    $data = $orders->map(function($order) {
        return [
            // Column 0: Checkbox + Order No (FIXED LEFT)
            'id' => $order->id,
            
            // Column 1: Image/CDR (FIXED LEFT)
            'order_image' => $order->order_image,
            'order_cdr' => $order->order_cdr,
            
            // Column 2: Brand (SCROLLABLE)
            'brand_name' => $order->brand->brand_name ?? '',
            
            // Column 3: Product (SCROLLABLE)
            'product_name' => $order->product->product_name ?? '',
            
            // Column 4: Qty/Unit (SCROLLABLE)
            'order_qty' => $order->order_qty,
            'unit_shorthand' => $order->unit->unit_shorthand ?? '',
            
            // Column 5: Dims/Weight (SCROLLABLE)
            'order_length' => $order->order_length,
            'order_width' => $order->order_width,
            'dunit_shorthand' => $order->d_unit->dunit_shorthand ?? '',
            'order_weight' => $order->order_weight,
            
            // Column 6: Dates (SCROLLABLE)
            'order_date' => $order->order_date ? $order->order_date->format('d-m-Y') : '',
            'order_deliverydate' => $order->order_deliverydate ? $order->order_deliverydate->format('d-m-Y') : '',
            
            // Column 7: Supplier (SCROLLABLE)
            'supplier_name' => $order->supplier->name ?? '',
            
            // Column 8: Supplier Amount (SCROLLABLE)
            'supplier_price' => $order->supplier_price,
            'supplier_mould_price' => $order->supplier_mould_price,
            'supplier_total' => $order->supplier_total,
            
            // Column 9: Buyer (SCROLLABLE)
            'buyer_name' => $order->buyer->buyer_name ?? '',
            
            // Column 10: Buyer Amount (SCROLLABLE)
            'order_price' => $order->order_price,
            'order_mouldprice' => $order->order_mouldprice,
            'buyer_total' => $order->buyer_total,
            
            // Column 11: Shipping (SCROLLABLE)
            'shipper_name' => $order->shipper->shipper_name ?? '',
            'order_shipmentno' => $order->order_shipmentno,
            
            // Column 12: Actions (FIXED RIGHT)
            'actions' => $order->id
            
            // REMOVED: Remarks and other columns that are not in the client-side table
        ];
    });

    // --- 10. Response ---
    return response()->json([
        'draw' => (int)$draw,
        'recordsTotal' => (int)$recordsTotal,
        'recordsFiltered' => (int)$recordsFiltered,
        'data' => $data,
    ]);
}

    /**
     * Duplicate an existing order.
     */
    public function duplicate($id)
    {
        $originalOrder = Order::findOrFail($id);
        
        // Replicate creates a new instance without saving it
        $newOrder = $originalOrder->replicate();

        // Set new values as requested
        $newOrder->order_date = Carbon::today();
        
        // Find and set pending status
        $pendingStatusId = Status::where('status_name', 'pending')->value('id');
        $newOrder->order_status = $pendingStatusId;

        // Clear fields that shouldn't be copied
        $newOrder->order_shipmentno = null;
        $newOrder->order_manufacturedqty = null;
        $newOrder->order_deliverydate = null; // Clear old delivery date
        
        // Set new timestamps
        $newOrder->created_at = now();
        $newOrder->updated_at = now();

        $newOrder->save();

        return redirect()->route('order.index')->with('success', 'Order #' . $originalOrder->id . ' duplicated successfully as new Order #' . $newOrder->id);
    }
    
    /**
     * Show the new bulk edit page with the selected orders.
     */
    public function showBulkEditForm(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'integer|exists:orders,id'
        ], [
            'order_ids.required' => 'Please select at least one order to edit.'
        ]);

        $orderIds = $request->input('order_ids');
        
        $orders = Order::with(['status', 'supplier', 'product', 'buyer', 'unit', 'd_unit', 'brand', 'shipper'])
                        ->whereIn('id', $orderIds)
                        ->get();
        
        // Fetch all data for dropdowns
        $statuses = Status::all();
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('product_name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $buyers = Buyer::orderBy('buyer_name')->get();
        $units = Unit::orderBy('unit_name')->get();
        $d_units = D_unit::orderBy('dunit_name')->get();
        $shippers = Shipper::orderBy('shipper_name')->get();


        return view('order.bulk-edit', compact(
            'orders', 'statuses', 'suppliers', 'products', 
            'brands', 'buyers', 'units', 'd_units', 'shippers'
        ));
    }


    /**
     * Handle the bulk update form submission from the new page.
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulk_status' => 'nullable|exists:statuses,id',
            'bulk_delivery_date' => 'nullable|date',
            'updates' => 'required|array', // Ensure we have updates
            'updates.*.id' => 'required|exists:orders,id',
            
            // Validate all fields from the bulk edit form
            'updates.*.order_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'updates.*.order_qty' => 'nullable|numeric',
            'updates.*.order_unit' => 'nullable|exists:units,id',
            'updates.*.order_productName' => 'nullable|exists:products,id',
            'updates.*.order_brand' => 'nullable|exists:brands,id',
            'updates.*.order_buyer' => 'nullable|exists:buyers,id',
            'updates.*.supplier_id' => 'nullable|exists:suppliers,id',
            'updates.*.order_price' => 'nullable|numeric',
            'updates.*.order_mouldprice' => 'nullable|numeric',
            'updates.*.supplier_price' => 'nullable|numeric',
            'updates.*.supplier_mould_price' => 'nullable|numeric',
            'updates.*.order_status' => 'nullable|exists:statuses,id',
            'updates.*.order_shipvia' => 'nullable|exists:shippers,id',
            'updates.*.order_shipmentno' => 'nullable|string|max:255',
            'updates.*.order_date' => 'nullable|date',
            'updates.*.order_deliverydate' => 'nullable|date',
            'updates.*.order_length' => 'nullable|numeric',
            'updates.*.order_width' => 'nullable|numeric',
            'updates.*.order_d_unit' => 'nullable|exists:d_units,id',
            'updates.*.order_weight' => 'nullable|numeric',
            'updates.*.order_remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error', 'Bulk update failed validation.');
        }

        $bulkStatus = $request->input('bulk_status');
        $bulkDeliveryDate = $request->input('bulk_delivery_date');
        $updates = $request->input('updates', []);

        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($updates as $id => $data) {
                $order = Order::find($id);
                if (!$order) continue;

                // 1. Apply row-specific edits
                // We map all fields from your form
                $updateData = [
                    'order_qty' => $data['order_qty'] ?? $order->order_qty,
                    'order_unit' => $data['order_unit'] ?? $order->order_unit,
                    'order_productName' => $data['order_productName'] ?? $order->order_productName,
                    'order_brand' => $data['order_brand'] ?? $order->order_brand,
                    'order_buyer' => $data['order_buyer'] ?? $order->order_buyer,
                    'supplier_id' => $data['supplier_id'] ?? $order->supplier_id,
                    'order_price' => $data['order_price'] ?? $order->order_price,
                    'order_mouldprice' => $data['order_mouldprice'] ?? $order->order_mouldprice,
                    'supplier_price' => $data['supplier_price'] ?? $order->supplier_price,
                    'supplier_mould_price' => $data['supplier_mould_price'] ?? $order->supplier_mould_price,
                    'order_status' => $data['order_status'] ?? $order->order_status,
                    'order_shipvia' => $data['order_shipvia'] ?? $order->order_shipvia,
                    'order_shipmentno' => $data['order_shipmentno'] ?? $order->order_shipmentno,
                    'order_date' => $data['order_date'] ?? $order->order_date,
                    'order_deliverydate' => $data['order_deliverydate'] ?? $order->order_deliverydate,
                    'order_length' => $data['order_length'] ?? $order->order_length,
                    'order_width' => $data['order_width'] ?? $order->order_width,
                    'order_d_unit' => $data['order_d_unit'] ?? $order->order_d_unit,
                    'order_weight' => $data['order_weight'] ?? $order->order_weight,
                    'order_remark' => $data['order_remark'] ?? $order->order_remark,
                ];

                // 2. Apply bulk edits (if they are provided)
                if (!empty($bulkStatus)) {
                    $updateData['order_status'] = $bulkStatus;
                }
                if (!empty($bulkDeliveryDate)) {
                    $updateData['order_deliverydate'] = $bulkDeliveryDate;
                }
                
                // 3. Handle File Upload
                if ($request->hasFile("updates.{$id}.order_image")) {
                    if ($order->order_image) Storage::disk('public')->delete($order->order_image);
                    $updateData['order_image'] = $request->file("updates.{$id}.order_image")->store('order_images', 'public');
                }
                
                // 4. Recalculate totals
                $qty = $updateData['order_qty'] ?? $order->order_qty;
                $updateData['buyer_total'] = ($updateData['order_price'] ?? $order->order_price) * $qty + ($updateData['order_mouldprice'] ?? $order->order_mouldprice);
                $updateData['supplier_total'] = ($updateData['supplier_price'] ?? $order->supplier_price) * $qty + ($updateData['supplier_mould_price'] ?? $order->supplier_mould_price);
                
                $order->update($updateData);
                $updatedCount++;
            }
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Bulk update failed: '. $e->getMessage());
            return redirect()->route('order.index')->with('error', 'An error occurred during the bulk update.');
        }

        return redirect()->route('order.index')->with('success', "Successfully updated {$updatedCount} orders.");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $deletedCount = Order::destroy($request->order_ids);

        return back()->with('success', "Successfully deleted {$deletedCount} orders.");
    }

    public function bulkDuplicate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $duplicatedCount = 0;
        $pendingStatusId = Status::where('status_name', 'pending')->value('id');

        foreach ($request->order_ids as $id) {
            $originalOrder = Order::find($id);
            if ($originalOrder) {
                $newOrder = $originalOrder->replicate();
                $newOrder->order_date = Carbon::today();
                $newOrder->order_status = $pendingStatusId;
                $newOrder->order_shipmentno = null;
                $newOrder->order_manufacturedqty = null;
                $newOrder->order_deliverydate = null;
                $newOrder->created_at = now();
                $newOrder->updated_at = now();
                $newOrder->save();
                $duplicatedCount++;
            }
        }

        return back()->with('success', "Successfully duplicated {$duplicatedCount} orders.");
    }
}