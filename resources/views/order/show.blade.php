<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - PO #{{ $order->id }}</title>
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
                font-size: 12px !important;
                font-family: Arial, sans-serif !important;
            }

            @page {
                size: A4;
                margin: 10mm;
            }

            #po-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
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
            padding: 8px 12px;
            vertical-align: top;
        }
        
        .vertical-table td:first-child {
            font-weight: 600;
            width: 35%;
            background-color: #f8fafc;
        }
        
        .vertical-table td:last-child {
            width: 65%;
        }
        
        .instruction-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-gray-100 p-4 sm:p-6 lg:p-8">

    <!-- ======== HEADER CONTROLS (HIDDEN IN PRINT) ======== -->
    <div id="print-controls" class="max-w-4xl mx-auto mb-4 flex justify-end items-center space-x-4">
        <a href="{{ route('order.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Orders
        </a>
        <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm">
            <i class="fa-solid fa-print mr-2"></i>Print PO
        </button>
    </div>

    <!-- ======== MAIN CONTAINER ======== -->
    <div id="po-container" class="bg-white max-w-4xl mx-auto shadow-lg rounded-lg border border-gray-200">
        <div class="p-8">

            <!-- HEADER - Order number and dates on right -->
            <header class="flex justify-between items-start pb-4 border-b-2 border-gray-300">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">PURCHASE ORDER</h1>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">#{{ $order->id }}</div>
                    <div class="mt-2 text-sm text-gray-600">
                        <p><strong>Order Date:</strong> {{ $order->order_date ? $order->order_date->format('m/d/Y') : 'N/A' }}</p>
                        <p><strong>Order Delivery:</strong> {{ $order->order_deliverydate ? $order->order_deliverydate->format('m/d/Y') : 'N/A' }}</p>
                    </div>
                </div>
            </header>

            <!-- INSTRUCTION BOX -->
            <div class="instruction-box section-break">
                <p class="text-sm font-semibold text-blue-800 text-center">
                    MAKE SHOT EXACT SIZE FOLLOW ART WORK FILE FOR FINAL SIZE
                </p>
            </div>

            <!-- VERTICAL TABLE FOR ORDER DETAILS -->
            <div class="section-break">
                <table class="vertical-table">
                    <tbody>
                        <tr>
                            <td>Item Name</td>
                            <td>{{ $order->brand->brand_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Product</td>
                            <td>{{ $order->product->product_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Length</td>
                            <td>{{ $order->order_length ?? 'N/A' }} {{ $order->d_unit->dunit_shorthand ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Width</td>
                            <td>{{ $order->order_width ?? 'N/A' }} {{ $order->d_unit->dunit_shorthand ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Ordered Quantity</td>
                            <td>{{ number_format($order->order_qty) }} {{ $order->unit->unit_shorthand ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Manufactured Quantity</td>
                            <td>{{ $order->order_manufacturedqty ?? '0' }}%</td>
                        </tr>
                        <tr>
                            <td>Modal Price</td>
                            <td>{{ number_format($order->supplier_mould_price ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Rate</td>
                            <td>{{ number_format($order->supplier_price ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td>{{ number_format($order->supplier_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Shipment Number</td>
                            <td>{{ $order->order_shipmentno ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Weight</td>
                            <td>{{ $order->order_weight ?? 'N/A' }} Grams</td>
                        </tr>
                        <tr>
                            <td>Die Set</td>
                            <td>{{ $order->dieset ?? 'NO' }}</td>
                        </tr>
                        <tr>
                            <td>Null With Loop</td>
                            <td>{{ $order->order_nailmaterial ? 'YES' : 'NO' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- REMARKS SECTION -->
            <div class="mt-6 section-break">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Remarks</h3>
                <div class="p-3 bg-gray-50 rounded border border-gray-200">
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->order_remark ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- ATTACHMENTS SECTION -->
            @if($order->order_image || $order->order_cdr)
            <div class="mt-6 section-break">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Attachments</h3>
                <div class="flex space-x-4">
                    @if($order->order_image)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Order Image</p>
                            <a href="{{ asset('storage/app/public/' . $order->order_image) }}" target="_blank">
                                <img src="{{ asset('storage/app/public/' . $order->order_image) }}" class="h-24 w-auto border rounded shadow-sm object-cover">
                            </a>
                        </div>
                    @endif
                    @if($order->order_cdr)
                        <div class="flex items-center">
                            <a href="{{ asset('storage/app/public/' . $order->order_cdr) }}" target="_blank" class="flex items-center text-blue-600 hover:underline">
                                <i class="fa-solid fa-file-zipper w-5 mr-2"></i> CDR File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- FOOTER -->
            <div class="mt-8 pt-4 border-t text-center text-xs text-gray-500">
                <p>Purchase Order #{{ $order->id }} - Generated on {{ now()->format('m/d/Y') }}</p>
            </div>

        </div>
    </div>
</body>
</html>