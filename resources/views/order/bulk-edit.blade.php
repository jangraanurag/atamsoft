@extends('layout')
@section('title', 'Bulk Edit Orders')

@section('header')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.tailwindcss.css">
<style>
    .action-button {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-weight: 500;
        color: #374151;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.1s ease-in-out;
        text-decoration: none;
    }
    .action-button:hover {
        background-color: #f9fafb;
    }
    
    /* Table wrapper for scrolling with sticky columns */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        position: relative;
        border: 1px solid #ccc;
    }
    
    .bulk-edit-table {
        border-collapse: collapse;
        width: auto;
        min-width: 100%;
        table-layout: fixed;
    }
    
    .bulk-edit-table th {
        background-color: #0168fa !important;
        color: white !important;
        font-weight: 700;
        padding: 12px 8px;
        border: 1px solid #ddd;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .bulk-edit-table td {
        padding: 8px;
        border: 1px solid #ddd;
        vertical-align: top;
        background: #f8fbff;
    }
    
    /* Sticky first column (Order No) */
    .bulk-edit-table th:nth-child(1),
    .bulk-edit-table td:nth-child(1) {
        position: sticky;
        left: 0;
        z-index: 8;
        background: #f8fbff;
        min-width: 80px;
        width: 80px;
        border-right: 2px solid #0168fa;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    
    /* Sticky second column (Image) */
    .bulk-edit-table th:nth-child(2),
    .bulk-edit-table td:nth-child(2) {
        position: sticky;
        left: 80px; /* Width of first column */
        z-index: 8;
        background: #f8fbff;
        min-width: 120px;
        width: 120px;
        border-right: 2px solid #0168fa;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    
    /* Ensure header sticky columns have higher z-index */
    .bulk-edit-table thead th:nth-child(1),
    .bulk-edit-table thead th:nth-child(2) {
        z-index: 11;
    }
    
    /* Alternating row colors */
    .bulk-edit-table tbody tr:nth-child(odd) td {
        background-color: #e2eeff !important;
    }
    
    .bulk-edit-table tbody tr:nth-child(even) td {
        background-color: #f8fbff !important;
    }
    
    /* Ensure sticky columns maintain row background colors */
    .bulk-edit-table tbody tr:nth-child(odd) td:nth-child(1),
    .bulk-edit-table tbody tr:nth-child(odd) td:nth-child(2) {
        background-color: #e2eeff !important;
    }
    
    .bulk-edit-table tbody tr:nth-child(even) td:nth-child(1),
    .bulk-edit-table tbody tr:nth-child(even) td:nth-child(2) {
        background-color: #f8fbff !important;
    }
    
    /* Form controls styling */
    .bulk-edit-table input[type="text"],
    .bulk-edit-table input[type="number"],
    .bulk-edit-table input[type="date"],
    .bulk-edit-table select,
    .bulk-edit-table textarea {
        font-size: 0.75rem;
        padding: 0.4rem 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #d1d5db;
        width: 100%;
        box-sizing: border-box;
    }
    
    .bulk-edit-table textarea {
        min-height: 80px;
        resize: vertical;
    }
    
    .total-field {
        background-color: #f3f4f6;
        font-weight: 600;
        color: #1f2937;
    }
    
    /* Section styling */
    .section-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    /* Order counter badge */
    .order-count-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        background-color: #0168fa;
        color: white;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-left: 0.5rem;
    }
    
    /* Column widths for better readability */
    .col-order-no { width: 80px; }
    .col-image { width: 120px; }
    .col-qty-unit { width: 150px; }
    .col-product-brand { width: 200px; }
    .col-buyer-supplier { width: 200px; }
    .col-buyer-price { width: 180px; }
    .col-supplier-price { width: 180px; }
    .col-status { width: 150px; }
    .col-shipment { width: 180px; }
    .col-dates { width: 150px; }
    .col-dims-weight { width: 180px; }
    .col-remarks { width: 250px; }
</style>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-100 text-green-700">
        {!! session('success') !!}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 rounded-md bg-red-100 text-red-700">
        <strong>Whoops! Something went wrong.</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Header Section -->
<div class="section-card">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                Bulk Edit Orders
                <span class="order-count-badge">{{ $orders->count() }}</span>
            </h1>
            <p class="text-sm text-gray-600 mt-1">Edit multiple orders simultaneously. Changes will apply to all selected orders.</p>
        </div>
        <div class="flex items-center space-x-2 mt-4 md:mt-0">
            <a href="{{ route('order.index') }}" class="action-button">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Orders
            </a>
            <button type="submit" form="bulk-edit-form" class="action-button bg-blue-600 text-white border-blue-600 hover:bg-blue-700">
                <i class="fa-solid fa-save mr-2"></i> Save All Changes
            </button>
        </div>
    </div>
</div>

<!-- Bulk Apply Section -->
<div class="section-card">
    <h2 class="section-title">Bulk Apply to All Orders</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="bulk_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="bulk_status" name="bulk_status" class="w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- No Change --</option>
                @foreach($statuses as $status)
                <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="bulk_delivery_date" class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
            <input type="date" id="bulk_delivery_date" name="bulk_delivery_date" class="w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label for="bulk_supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
            <select id="bulk_supplier" name="bulk_supplier" class="w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- No Change --</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="button" id="bulk-apply-btn" class="w-full action-button bg-blue-600 text-white border-blue-600 hover:bg-blue-700 justify-center">
                <i class="fa-solid fa-bolt mr-2"></i> Apply to All
            </button>
        </div>
    </div>
</div>

<!-- Editable Orders Table -->
<div class="section-card p-0 overflow-hidden">
    <div class="p-4 border-b bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800">Order Details</h2>
        <p class="text-sm text-gray-600">Edit individual order fields below. First two columns are fixed for easy reference.</p>
    </div>
    
    <form action="{{ route('order.bulkUpdate') }}" method="POST" enctype="multipart/form-data" id="bulk-edit-form">
        @csrf
        <div class="table-wrapper">
            <table class="bulk-edit-table">
                <thead>
                    <tr>
                        <th class="col-order-no">Order No</th>
                        <th class="col-image">Image</th>
                        <th class="col-qty-unit">Quantity / Unit</th>
                        <th class="col-product-brand">Product / Brand</th>
                        <th class="col-buyer-supplier">Buyer / Supplier</th>
                        <th class="col-buyer-price">Buyer Price</th>
                        <th class="col-supplier-price">Supplier Price</th>
                        <th class="col-status">Status</th>
                        <th class="col-shipment">Shipment</th>
                        <th class="col-dates">Dates</th>
                        <th class="col-dims-weight">Dimensions / Weight</th>
                        <th class="col-remarks">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="row-data">
                            <!-- Order No - STICKY COLUMN -->
                            <td class="font-medium align-top">
                                <div class="flex items-center">
                                    <span class="text-blue-600 font-bold">#{{ $order->id }}</span>
                                </div>
                                <input type="hidden" name="updates[{{ $order->id }}][id]" value="{{ $order->id }}">
                            </td>
                            
                            <!-- Image - STICKY COLUMN -->
                            <td class="align-top">
                                @if($order->order_image)
                                    <img src="{{ asset('storage/app/public/' . $order->order_image) }}" alt="Order Image" class="w-16 h-16 object-cover rounded border mb-2 mx-auto">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded border flex items-center justify-center mb-2 mx-auto">
                                        <span class="text-xs text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <input type="file" name="updates[{{ $order->id }}][order_image]" class="block w-full text-xs text-center border-0">
                            </td>
                            
                            <!-- Quantity / Unit -->
                            <td class="align-top space-y-1">
                                <div>
                                    <label class="text-xs text-gray-500 block">Quantity</label>
                                    <input type="number" name="updates[{{ $order->id }}][order_qty]" value="{{ $order->order_qty }}" class="calc-qty" data-row-id="{{ $order->id }}" min="0">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Unit</label>
                                    <select name="updates[{{ $order->id }}][order_unit]">
                                        <option value="">-- Unit --</option>
                                        @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" @selected($order->order_unit == $unit->id)>{{ $unit->unit_shorthand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            
                            <!-- Product / Brand -->
                            <td class="align-top space-y-1">
                                <div>
                                    <label class="text-xs text-gray-500 block">Product</label>
                                    <select name="updates[{{ $order->id }}][order_productName]">
                                        <option value="">-- Product --</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" @selected($order->order_productName == $product->id)>{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Brand</label>
                                    <select name="updates[{{ $order->id }}][order_brand]">
                                        <option value="">-- Brand --</option>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @selected($order->order_brand == $brand->id)>{{ $brand->brand_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            
                            <!-- Buyer / Supplier -->
                            <td class="align-top space-y-1">
                                <div>
                                    <label class="text-xs text-gray-500 block">Buyer</label>
                                    <select name="updates[{{ $order->id }}][order_buyer]">
                                        @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}" @selected($order->order_buyer == $buyer->id)>{{ $buyer->buyer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Supplier</label>
                                    <select name="updates[{{ $order->id }}][supplier_id]">
                                        <option value="">-- Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected($order->supplier_id == $supplier->id)>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            
                            <!-- Buyer Price -->
                            <td class="align-top space-y-1">
                                <div>
                                    <label class="text-xs text-gray-500 block">Price</label>
                                    <input type="number" step="any" name="updates[{{ $order->id }}][order_price]" value="{{ $order->order_price }}" placeholder="0.00" class="calc-b-price" data-row-id="{{ $order->id }}" min="0">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Mould Price</label>
                                    <input type="number" step="any" name="updates[{{ $order->id }}][order_mouldprice]" value="{{ $order->order_mouldprice }}" placeholder="0.00" class="calc-b-mould" data-row-id="{{ $order->id }}" min="0">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Total</label>
                                    <input type="number" step="any" value="{{ $order->buyer_total }}" class="total-field calc-b-total" readonly>
                                </div>
                            </td>
                            
                            <!-- Supplier Price -->
                            <td class="align-top space-y-1">
                                <div>
                                    <label class="text-xs text-gray-500 block">Price</label>
                                    <input type="number" step="any" name="updates[{{ $order->id }}][supplier_price]" value="{{ $order->supplier_price }}" placeholder="0.00" class="calc-s-price" data-row-id="{{ $order->id }}" min="0">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Mould Price</label>
                                    <input type="number" step="any" name="updates[{{ $order->id }}][supplier_mould_price]" value="{{ $order->supplier_mould_price }}" placeholder="0.00" class="calc-s-mould" data-row-id="{{ $order->id }}" min="0">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Total</label>
                                    <input type="number" step="any" value="{{ $order->supplier_total }}" class="total-field calc-s-total" readonly>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="align-top">
                                <select name="updates[{{ $order->id }}][order_status]" class="bulk-edit-status">
                                    @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" @selected($order->order_status == $status->id)>{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <!-- Shipment -->
                            <td class="align-top space-y-1">
                                <div>
                                    <label class="text-xs text-gray-500 block">Ship Via</label>
                                    <select name="updates[{{ $order->id }}][order_shipvia]">
                                        <option value="">-- Ship Via --</option>
                                        @foreach($shippers as $shipper)
                                        <option value="{{ $shipper->id }}" @selected($order->order_shipvia == $shipper->id)>{{ $shipper->shipper_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Shipment No.</label>
                                    <input type="text" name="updates[{{ $order->id }}][order_shipmentno]" value="{{ $order->order_shipmentno }}" placeholder="Shipment No.">
                                </div>
                            </td>
                            
                            <!-- Dates -->
                            <td class="align-top space-y-1">
                                <div>
                                    <label class="text-xs text-gray-500 block">Order Date</label>
                                    <input type="date" name="updates[{{ $order->id }}][order_date]" value="{{ $order->order_date ? $order->order_date->format('Y-m-d') : '' }}">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Delivery Date</label>
                                    <input type="date" name="updates[{{ $order->id }}][order_deliverydate]" value="{{ $order->order_deliverydate ? $order->order_deliverydate->format('Y-m-d') : '' }}" class="bulk-edit-delivery-date">
                                </div>
                            </td>
                            
                            <!-- Dimensions / Weight -->
                            <td class="align-top space-y-1">
                                <div class="grid grid-cols-2 gap-1">
                                    <div>
                                        <label class="text-xs text-gray-500 block">Length</label>
                                        <input type="number" step="any" name="updates[{{ $order->id }}][order_length]" value="{{ $order->order_length }}" placeholder="0.0">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 block">Width</label>
                                        <input type="number" step="any" name="updates[{{ $order->id }}][order_width]" value="{{ $order->order_width }}" placeholder="0.0">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Dimension Unit</label>
                                    <select name="updates[{{ $order->id }}][order_d_unit]">
                                        <option value="">-- Unit --</option>
                                        @foreach($d_units as $d_unit)
                                        <option value="{{ $d_unit->id }}" @selected($order->order_d_unit == $d_unit->id)>{{ $d_unit->dunit_shorthand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 block">Weight (grams)</label>
                                    <input type="number" step="any" name="updates[{{ $order->id }}][order_weight]" value="{{ $order->order_weight }}" placeholder="0.0">
                                </div>
                            </td>
                            
                            <!-- Remarks -->
                            <td class="align-top">
                                <textarea name="updates[{{ $order->id }}][order_remark]" rows="4" placeholder="Add remarks...">{{ $order->order_remark }}</textarea>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Save Button at Bottom -->
        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm">
                <i class="fa-solid fa-save mr-2"></i>Save All Changes
            </button>
        </div>
    </form>
</div>

@endsection

@section('footer')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Bulk Apply Logic
    $('#bulk-apply-btn').on('click', function() {
        const bulkStatus = $('#bulk_status').val();
        const bulkDate = $('#bulk_delivery_date').val();
        const bulkSupplier = $('#bulk_supplier').val();

        if (bulkStatus) {
            $('.bulk-edit-status').val(bulkStatus);
        }
        if (bulkDate) {
            $('.bulk-edit-delivery-date').val(bulkDate);
        }
        if (bulkSupplier) {
            $('select[name*="[supplier_id]"]').val(bulkSupplier);
        }
        
        // Show confirmation
        alert('Bulk changes applied to all orders. Click "Save All Changes" to confirm.');
    });

    // Client-side calculation logic
    function calculateTotals(rowId) {
        const row = $(`.row-data:has(input[data-row-id="${rowId}"])`);
        
        const qty = parseFloat(row.find('.calc-qty').val()) || 0;
        
        const b_price = parseFloat(row.find('.calc-b-price').val()) || 0;
        const b_mould = parseFloat(row.find('.calc-b-mould').val()) || 0;
        
        const s_price = parseFloat(row.find('.calc-s-price').val()) || 0;
        const s_mould = parseFloat(row.find('.calc-s-mould').val()) || 0;

        // Calculation: (price * qty) + mould
        const buyerTotal = (b_price * qty) + b_mould;
        const supplierTotal = (s_price * qty) + s_mould;
        
        row.find('.calc-b-total').val(buyerTotal.toFixed(2));
        row.find('.calc-s-total').val(supplierTotal.toFixed(2));
    }

    // Add event listeners for all calculation inputs
    $('.bulk-edit-table').on('input', '.calc-qty, .calc-b-price, .calc-b-mould, .calc-s-price, .calc-s-mould', function() {
        const rowId = $(this).data('row-id');
        calculateTotals(rowId);
    });
    
    // Initialize calculations on page load
    $('.row-data').each(function() {
        const rowId = $(this).find('.calc-qty').data('row-id');
        calculateTotals(rowId);
    });
});
</script>
@endsection