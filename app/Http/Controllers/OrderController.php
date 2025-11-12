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
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new order.
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

        // 4. Create a base query for summary boxes
        $orderQuery = Order::query();

        // 5. Apply the status filter if it exists
        if ($filterStatus && $filterStatus !== 'all') {
            $statusId = Status::where('status_name', $filterStatus)->value('id');
            if ($statusId) {
                $orderQuery->where('order_status', $statusId);
            }
        }
        
        // 6. Fetch orders for the summary boxes
        $orders = $orderQuery->with(['buyer', 'supplier'])->get();

        // 7. Aggregate data for the buyer summary table
        $buyerReports = $orders->groupBy('order_buyer')->map(function ($buyerOrders) {
            $buyer = $buyerOrders->first()->buyer;
            return [
                'buyer_name' => $buyer->buyer_name ?? 'N/A',
                'number_of_orders' => $buyerOrders->count(),
                'total_amount' => $buyerOrders->sum('buyer_total'),
                'buyer_id' => $buyer->id ?? null,
            ];
        })->values();

        // 8. Aggregate data for the supplier summary table
        $supplierReports = $orders->whereNotNull('supplier_id')->groupBy('supplier_id')->map(function ($supplierOrders) {
            $supplier = $supplierOrders->first()->supplier;
            if (!$supplier) return null; // Skip if supplier is somehow missing
            return [
                'supplier_name' => $supplier->name ?? 'N/A',
                'number_of_orders' => $supplierOrders->count(),
                'total_amount' => $supplierOrders->sum('supplier_total'),
                'supplier_id' => $supplier->id ?? null,
            ];
        })->filter()->values(); // filter() removes any null entries

        // 9. Pass ALL data to the view
        return view('order.index', compact(
            'orders', 'statuses', 'suppliers', 'buyerReports', 'filterStatus',
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

        // --- 2. Build Base Query ---
        $query = Order::with(['product', 'buyer', 'status', 'supplier']);

        // --- 3. Apply Status Filter ---
        if ($filterStatusName && $filterStatusName !== 'all') {
            $statusId = Status::where('status_name', $filterStatusName)->value('id');
            if ($statusId) {
                $query->where('order_status', $statusId);
            }
        }
        
        // --- 4. Get Total Record Counts ---
        $recordsTotal = $query->count();

        // --- 5. Apply Global Search ---
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('id', 'like', "%{$searchValue}%")
                  ->orWhere('order_remark', 'like', "%{$searchValue}%")
                  ->orWhereHas('product', function ($subQ) use ($searchValue) {
                      $subQ->where('product_name', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('buyer', function ($subQ) use ($searchValue) {
                      $subQ->where('buyer_name', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('supplier', function ($subQ) use ($searchValue) {
                      $subQ->where('name', 'like', "%{$searchValue}%");
                  });
            });
        }

        // --- 6. Get Filtered Record Count ---
        $recordsFiltered = $query->count();

        // --- 7. Apply Sorting & Pagination ---
        if ($orderColumnName == 'product_name') {
            $query->leftJoin('products', 'orders.order_productName', '=', 'products.id')
                  ->orderBy('products.product_name', $orderDir)
                  ->select('orders.*');
        } elseif ($orderColumnName == 'buyer_name') {
            $query->leftJoin('buyers', 'orders.order_buyer', '=', 'buyers.id')
                  ->orderBy('buyers.buyer_name', $orderDir)
                  ->select('orders.*');
        } elseif ($orderColumnName == 'supplier_name') { 
            $query->leftJoin('suppliers', 'orders.supplier_id', '=', 'suppliers.id')
                  ->orderBy('suppliers.name', $orderDir)
                  ->select('orders.*');
        } elseif ($orderColumnName == 'status_name') { 
            $query->leftJoin('statuses', 'orders.order_status', '=', 'statuses.id')
                  ->orderBy('statuses.status_name', $orderDir)
                  ->select('orders.*');
        } else {
            // Sort on the 'orders' table itself (e.g., id, order_date)
            $query->orderBy($orderColumnName, $orderDir);
        }
        
        $query->skip($start)->take($length);

        // --- 8. Execute Query ---
        $orders = $query->get();

        // --- 9. Format Data for Response ---
        $data = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'order_image' => $order->order_image,
                'product_name' => $order->product->product_name ?? '-',
                'buyer_name' => $order->buyer->buyer_name ?? '-',
                'supplier_name' => $order->supplier->name ?? '-', 
                'supplier_id' => $order->supplier_id,
                'order_qty' => $order->order_qty,
                'status_name' => $order->status->status_name ?? '-',
                'order_status' => $order->order_status,
                'order_manufacturedqty' => $order->order_manufacturedqty,
                'order_date' => $order->order_date ? $order->order_date->format('Y-m-d') : '-',
                'order_deliverydate' => $order->order_deliverydate ? $order->order_deliverydate->format('Y-m-d') : '-',
                'order_weight' => $order->order_weight,
                'order_price' => $order->order_price,
                'order_mouldprice' => $order->order_mouldprice,
                'supplier_price' => $order->supplier_price,
                'supplier_mould_price' => $order->supplier_mould_price,
                'buyer_total' => $order->buyer_total,
                'supplier_total' => $order->supplier_total,
                'order_remark' => $order->order_remark,
                'actions' => $order->id
            ];
        });

        // --- 10. Return the JSON Response ---
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
}