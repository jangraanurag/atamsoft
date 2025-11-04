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
        $d_units = D_unit::orderBy('dunit_name')->get();
        $labelTypes = LabelType::orderBy('labeltype_name')->get();
        $shippers = Shipper::orderBy('shipper_name')->get();
        $statuses = Status::orderBy('status_name')->get();
        $units = Unit::orderBy('unit_name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('order.new', compact(
            'products',
            'brands',
            'buyers',
            'd_units',
            'labelTypes',
            'shippers',
            'statuses',
            'units',
            'suppliers'
        ));
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        // Validate the incoming request data against the form fields.
        $validatedData = $request->validate([
            // Core Details
            'order_buyer' => 'required|exists:buyers,id',
            'order_brand' => 'nullable|exists:brands,id',
            'order_productName' => 'nullable|exists:products,id',
            'order_labelType' => 'nullable|exists:label_types,id',
            'order_date' => 'nullable|date',
            'order_deliverydate' => 'nullable|date',

            // Quantity & Dimensions
            'order_qty' => 'nullable|numeric',
            'order_manufacturedqty' => 'nullable|numeric',
            'order_unit' => 'nullable|exists:units,id',
            'order_weight' => 'nullable|numeric',
            'order_length' => 'nullable|numeric',
            'order_width' => 'nullable|numeric',
            'order_d_unit' => 'nullable|exists:d_units,id',

            // Colors (validates that 'colors' is an array, and each item in it is a string)
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string|max:100',

            // Shipping & Status
            'order_shipvia' => 'nullable|exists:shippers,id',
            'order_shipmentno' => 'nullable|string|max:100',
            'order_status' => 'nullable|exists:statuses,id',
            'order_salesman' => 'nullable|string|max:100',

            // Materials & Attachments
            'order_nailmaterial' => 'nullable|string|max:255',
            'dieset' => 'nullable|boolean',
            'order_logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'order_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'order_cdr' => 'nullable|file|max:5120', // 5MB max
            'order_po' => 'nullable|file|max:5120',

            // Pricing
            'order_price' => 'nullable|numeric',
            'order_mouldprice' => 'nullable|numeric',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_price' => 'nullable|numeric',
            'supplier_mould_price' => 'nullable|numeric',

            // Addresses
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

            // Remarks
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

        // The 'colors' field from the form is an array. We can store it as a JSON string.
        // NOTE: You must add a 'colors' text or json column to your 'orders' table migration.
        if (!empty($validatedData['colors'])) {
            $validatedData['colors'] = json_encode($validatedData['colors']);
        }

        // Handle the 'dieset' checkbox. If it's not present in the request, it's false.
        $validatedData['dieset'] = $request->has('dieset');

        // Recalculate totals on the backend to ensure data integrity
        $validatedData['buyer_total'] = ($validatedData['order_price'] ?? 0) + ($validatedData['order_mouldprice'] ?? 0);
        $validatedData['supplier_total'] = ($validatedData['supplier_price'] ?? 0) + ($validatedData['supplier_mould_price'] ?? 0);

        // Create the order with all the validated data
        Order::create($validatedData);

        // Redirect to a success page, like the list of all orders.
        return redirect()->route('order.all')->with('success', 'Order created successfully!');
    }


    public function all()
    {
        $orders = Order::with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status'])->get();
        $statuses = Status::all();
        return view('order.index', compact('orders', 'statuses'));
    }

    public function pending()
    {
        $pendingStatusId = Status::where('status_name', 'pending')->first()->id;
        $statuses = Status::all();

        $orders = Order::where('order_status', $pendingStatusId)->with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status'])->get();
        return view('order.pending', compact('orders', 'statuses'));
    }

    public function shipped()
    {
        $shippedStatusId = Status::where('status_name', 'shipped')->first()->id;
        $orders = Order::where('order_status', $shippedStatusId)->with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status'])->get();
        $statuses = Status::all();

        return view('order.shipped', compact('orders', 'statuses'));
    }

    public function delivered()
    {
        $deliveredStatusId = Status::where('status_name', 'delivered')->first()->id;
        $orders = Order::where('order_status', $deliveredStatusId)->with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status'])->get();
        $statuses = Status::all();
        return view('order.delivered', compact('orders', 'statuses'));
    }

    public function ready()
    {
        $readyStatusId = Status::where('status_name', 'ready')->first()->id;
        $orders = Order::where('order_status', $readyStatusId)->with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status'])->get();
        $statuses = Status::all();
        return view('order.ready', compact('orders', 'statuses'));
    }

    public function cancelled()
    {
        $cancelledStatusId = Status::where('status_name', 'cancelled')->first()->id;
        $orders = Order::where('order_status', $cancelledStatusId)->with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status'])->get();
        $statuses = Status::all();
        return view('order.cancelled', compact('orders', 'statuses'));
    }

    public function show($id)
    {
        $order = Order::with(['product', 'brand', 'buyer', 'unit', 'labeltype', 'd_unit', 'shipper', 'status'])->findOrFail($id);
        return view('order.show', compact('order'));
    }

    public function updateStatus(Request $request)
    {
         $request->validate([
            'updates' => 'required|array',
        ], [
            'updates.required' => 'No data was submitted. Please make changes before updating.'
        ]);

        $updates = $request->input('updates', []);
        $errors = [];
        $updatedCount = 0;

        // 2. Iterate over each order's data sent from the form.
        foreach ($updates as $orderId => $data) {
            // Find the corresponding order in the database.
            $order = Order::find($orderId);

            if (!$order) {
                $errors[] = "Order with ID #{$orderId} could not be found.";
                continue; // Skip to the next order.
            }

            // 3. Create a validator for the data of this specific order.
            $validator = Validator::make($data, [
                'order_status' => 'nullable|exists:statuses,id',
                'order_manufacturedqty' => 'nullable|numeric|min:0',
                'order_deliverydate' => 'nullable|date',
                'order_weight' => 'nullable|numeric|min:0',
                'order_price' => 'nullable|numeric|min:0',
                'order_mouldprice' => 'nullable|numeric|min:0',
                'supplier_price' => 'nullable|numeric|min:0',
                'supplier_mould_price' => 'nullable|numeric|min:0',
                'order_remark' => 'nullable|string|max:1000',
            ]);

            // 4. If validation for this row fails, collect the errors.
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $errors[] = "Error on Order #{$orderId}: {$error}";
                }
                continue; // Skip this order and proceed to the next.
            }

            // 5. Get the validated data and update the order.
            // The `update` method only saves fields that have changed.
            $order->update($validator->validated());
            $updatedCount++;
        }

        // 6. Redirect back with appropriate feedback.
        if (count($errors) > 0) {
            // If there were errors, send them back to the view.
            return back()->withErrors($errors)->with('message', 'Some orders could not be updated.');
        }

        // If everything went well, return a success message.
        return back()->with('message', "Successfully updated {$updatedCount} orders.");
    }
    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return back()->with('success', 'Order deleted successfully!');
    }
}
