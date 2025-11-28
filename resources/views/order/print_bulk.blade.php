<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Purchase Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        /* ========= GENERAL PRINT FIXES ========= */
        @media print {
            #print-controls {
                display: none !important;
            }

            html, body {
                width: 210mm;
                height: 297mm;
                margin: 0 auto;
                background: #fff !important;
                color: #000 !important;
                font-size: 1.3em !important; /* Adjusted font size */
                font-family: Arial, sans-serif !important;
            }

            @page {
                size: A4;
                margin: 3mm;
            }

            .po-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: 277mm; /* Fill page height */
                display: flex;
                flex-direction: column;
                page-break-after: always; /* Force new page for each PO */
            }
            
            .po-container:last-child {
                page-break-after: auto;
            }

            /* Prevent splitting important sections */
            .section-break {
                page-break-inside: avoid !important;
            }
        }
        
        /* Custom styling for vertical table layout */
        .vertical-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .vertical-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .vertical-table td {
            padding: 6px 10px; /* Reduced padding */
            vertical-align: top;
        }
        
        .vertical-table td:first-child {
            font-weight: 600;
            width: 40%;
            background-color: #f8fafc;
        }
        
        .vertical-table td:last-child {
            width: 60%;
        }
        
        .instruction-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .text-xs
        {
            font-size: 1.5rem;
        }
    </style>
</head>

<body class="bg-gray-100 p-4 sm:p-6 lg:p-8">

    <!-- ======== HEADER CONTROLS (HIDDEN IN PRINT) ======== -->
    <div id="print-controls" class="max-w-5xl mx-auto mb-4 flex justify-end items-center space-x-4">
        <a href="{{ route('order.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Orders
        </a>
        <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm">
            <i class="fa-solid fa-print mr-2"></i>Print All POs
        </button>
    </div>

    @foreach($orders as $order)
    <!-- ======== MAIN CONTAINER ======== -->
    <div class="po-container bg-white max-w-5xl mx-auto shadow-lg rounded-lg border border-gray-200 mb-8">
        <div class="p-6">

            <!-- HEADER -->
            <header class="flex justify-between items-start pb-3 border-b-2 border-gray-300">
                <div class="w-full">
                    <h1 class="text-2xl font-bold text-gray-900">PURCHASE ORDER</h1>
                </div>
                <div class="w-full text-right">
                    <div class="text-xl font-bold text-blue-600">#{{ $order->id }}</div>
                    <div class="mt-1 text-xs text-gray-600">
                        <p><strong>Order Date:</strong> {{ $order->order_date ? $order->order_date->format('m/d/Y') : 'N/A' }}</p>
                        <p><strong>Delivery Date:</strong> {{ $order->order_deliverydate ? $order->order_deliverydate->format('m/d/Y') : 'N/A' }}</p>
                    </div>
                </div>
            </header>

            <!-- SUPPLIER INFO -->
            <div class="mt-4 text-gray-700">
                 @if($order->supplier)
                    <p class="font-bold text-base">To: {{ $order->supplier->name }}</p>
                    <address class="not-italic text-xs">
                        @if($order->supplier->address_line_1) {{ $order->supplier->address_line_1 }}, @endif
                        @if($order->supplier->address_line_2) {{ $order->supplier->address_line_2 }}, @endif
                        @if($order->supplier->city || $order->supplier->state || $order->supplier->postal_code)
                            {{ $order->supplier->city }}, {{ $order->supplier->state }} {{ $order->supplier->postal_code }}
                        @endif
                        @if($order->supplier->country) | {{ $order->supplier->country }} @endif
                         @if($order->supplier->phone) | <strong>Phone:</strong> {{ $order->supplier->phone }} @endif
                         @if($order->supplier->email) | <strong>Email:</strong> {{ $order->supplier->email }} @endif
                    </address>
                @endif
            </div>

            <!-- INSTRUCTION BOX -->
            <div class="instruction-box section-break">
                <p class="text-xs font-semibold text-blue-800 text-center">
                    IMAGE IS NOT EXACT SIZE. FOLLOW ARTWORK CDR/PDF FILE FOR FINAL SIZE
                </p>
            </div>

            <!-- MAIN CONTENT (2-COLUMN) -->
            <div class="flex space-x-6 mt-4">
                <!-- Left Column: Details -->
                <div class="w-1/2 section-break">
                    <table class="vertical-table text-xs">
                        <tbody>
                            <tr><td>Item Name</td><td>{{ $order->brand->brand_name ?? 'N/A' }}</td></tr>
                            <tr><td>Product</td><td>{{ $order->product->product_name ?? 'N/A' }}</td></tr>
                            <tr><td>Length</td><td>{{ $order->order_length ?? 'N/A' }} {{ $order->d_unit->dunit_shorthand ?? '' }}</td></tr>
                            <tr><td>Width</td><td>{{ $order->order_width ?? 'N/A' }} {{ $order->d_unit->dunit_shorthand ?? '' }}</td></tr>
                            <tr><td>Ordered Quantity</td><td>{{ number_format($order->order_qty) }} {{ $order->unit->unit_shorthand ?? '' }}</td></tr>
                            <tr><td>Manufactured Qty</td><td>{{ $order->order_manufacturedqty ?? '0' }}%</td></tr>
                            <tr><td>Modal Price</td><td>{{ number_format($order->supplier_mould_price ?? 0, 2) }}</td></tr>
                            <tr><td>Rate</td><td>{{ number_format($order->supplier_price ?? 0, 2) }}</td></tr>
                            <tr><td>Amount</td><td>{{ number_format($order->supplier_total ?? 0, 2) }}</td></tr>
                            <tr><td>Shipment Number</td><td>{{ $order->order_shipmentno ?? 'N/A' }}</td></tr>
                            <tr><td>Weight</td><td>{{ $order->order_weight ?? 'N/A' }} Grams</td></tr>
                            <tr><td>Die Set</td><td>{{ $order->dieset ?? 'NO' }}</td></tr>
                            <tr><td>Null With Loop</td><td>{{ $order->order_nailmaterial ? 'YES' : 'NO' }}</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Right Column: Image & Attachments -->
                <div class="w-1/2 section-break">
                    @if($order->order_image)
                        <div class="text-center">
                            <img src="{{ asset('storage/app/public/' . $order->order_image) }}" class="w-full h-auto max-h-64 border rounded-lg shadow-sm object-contain inline-block">
                        </div>
                    @endif
                    @if($order->order_cdr)
                        <div class="mt-4 text-center">
                            <a href="{{ asset('storage/app/public/' . $order->order_cdr) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                <i class="fa-solid fa-file-zipper w-5 mr-1"></i> Download CDR File
                            </a>
                        </div>
                    @endif

                    <!-- REMARKS & SUPPLIER REMARK -->
                    <div class="mt-4">
                        <div class="w-full section-break">
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">Remarks</h3>
                            <div class="p-2 bg-gray-50 rounded border border-gray-200">
                                <p class="whitespace-pre-line font-bold text-red-600 text-xl">{{ $order->order_remark ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($order->supplier_remark)
                        <div class="w-full section-break mt-2">
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">Supplier Remark</h3>
                            <div class="p-2 bg-gray-50 rounded border border-gray-200">
                                <p class="whitespace-pre-line font-bold text-red-600 text-xl">{{ $order->supplier_remark }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="mt-6 pt-3 border-t text-center text-xs text-gray-500">
                <p>Purchase Order #{{ $order->id }} - Generated on {{ now()->format('m/d/Y') }}</p>
            </div>

        </div>
    </div>
    @endforeach

</body>
</html>

