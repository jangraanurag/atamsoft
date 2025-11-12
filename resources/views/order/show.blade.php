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
            }

            @page {
                size: A4;
                margin: 6mm;
            }

            #po-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            img {
                max-height: 120px !important;
                object-fit: contain !important;
            }

            /* Force side-by-side layout for summary section */
            .print-flex {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: flex-start !important;
                gap: 20px !important;
            }

            .print-colors {
                width: 58% !important;
            }

            .print-summary {
                width: 40% !important;
            }

            /* Prevent splitting important sections */
            #po-container, #po-container * {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-4 sm:p-6 lg:p-8">

    <!-- ======== HEADER CONTROLS (HIDDEN IN PRINT) ======== -->
    <div id="print-controls" class="max-w-4xl mx-auto mb-4 flex justify-end items-center space-x-4">
        <a href="{{ route('order.all') }}" class="text-sm text-gray-600 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Orders
        </a>
        <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm">
            <i class="fa-solid fa-print mr-2"></i>Print PO
        </button>
    </div>

    <!-- ======== MAIN CONTAINER ======== -->
    <div id="po-container" class="bg-white max-w-4xl mx-auto shadow-lg rounded-lg border border-gray-200">
        <div class="p-6 sm:p-12">

            <!-- HEADER -->
            <header class="flex justify-between items-start pb-6 border-b-2 border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mt-4">Purchase Order</h1>
                    <p class="text-gray-500">PO Number: #{{ $order->id }}</p>
                </div>
                <div class="text-right">
                    {{-- Optional company info --}}
                </div>
            </header>

            <!-- SUPPLIER DETAILS -->
            <div class="grid grid-cols-2 gap-8 my-8">
                <div>
                    <h3 class="font-semibold text-gray-700 uppercase tracking-wide">Vendor / Supplier</h3>
                    <address class="mt-2 not-italic text-gray-600">
                        <strong class="text-gray-900">{{ $order->supplier->name ?? 'N/A' }}</strong><br>
                        {{ $order->supplier->address_line_1 ?? 'N/A' }}<br>
                        {{ $order->supplier->city ?? '' }}, {{ $order->supplier->state ?? '' }} {{ $order->supplier->postal_code ?? '' }}
                    </address>
                </div>
                <div>
                    {{-- Ship To (if needed) --}}
                </div>
            </div>

            <!-- ORDER INFO BAR -->
            <div class="p-4 bg-gray-50 rounded-lg grid grid-cols-3 gap-4 text-sm">
                <div>
                    <strong class="text-gray-600 block">Order Date</strong>
                    <span class="text-gray-800">{{ $order->order_date ? $order->order_date->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div>
                    <strong class="text-gray-600 block">Delivery Date</strong>
                    <span class="text-gray-800">{{ $order->order_deliverydate ? $order->order_deliverydate->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div>
                    <strong class="text-gray-600 block">Status</strong>
                    <span class="text-gray-800">{{ $order->status->status_name ?? 'N/A' }}</span>
                </div>
                <div>
                    <strong class="text-gray-600 block">Ship Via</strong>
                    <span class="text-gray-800">{{ $order->shipper->shipper_name ?? $order->order_shipvia ?? 'N/A' }}</span>
                </div>
                <div>
                    <strong class="text-gray-600 block">Shipment No.</strong>
                    <span class="text-gray-800">{{ $order->order_shipmentno ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- LINE ITEMS -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900">Order Details</h3>
                <table class="w-full mt-4 text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-sm font-semibold text-gray-700">Product / Brand</th>
                            <th class="p-3 text-sm font-semibold text-gray-700">Specs (L x W)</th>
                            <th class="p-3 text-sm font-semibold text-gray-700 text-right">Qty</th>
                            <th class="p-3 text-sm font-semibold text-gray-700 text-right">Supplier Price</th>
                            <th class="p-3 text-sm font-semibold text-gray-700 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="p-3 align-top">
                                <p class="font-medium text-gray-800">{{ $order->product->product_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-600">{{ $order->brand->brand_name ?? 'N/A' }}</p>
                            </td>
                            <td class="p-3 align-top text-gray-600">
                                {{ $order->order_length }} x {{ $order->order_width }} {{ $order->d_unit->dunit_name ?? '' }}
                            </td>
                            <td class="p-3 align-top text-gray-600 text-right">
                                Order Qty: {{ number_format($order->order_qty) }} {{ $order->unit->unit_name ?? '' }}<br/>
                                Manufactured Qty: ±{{ number_format($order->order_manufacturedqty) }}%
                            </td>
                            <td class="p-3 align-top text-gray-600 text-right">{{ number_format($order->supplier_price, 2) }}</td>
                            <td class="p-3 align-top text-gray-800 font-medium text-right">{{ number_format($order->supplier_price * $order->order_qty, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- COLORS & SUMMARY (SIDE BY SIDE) -->
            <div class="mt-8 print-flex flex flex-col md:flex-row justify-between">
                <div class="print-colors mb-8 md:mb-0">
                    <h3 class="text-base font-semibold text-gray-900">Colors, Materials & Other Info</h3>
                    @if(!empty($order->colors) && (is_array($order->colors) || is_object($order->colors)))
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($order->colors as $colorInfo)
                                <span class="flex items-center text-sm font-medium text-gray-700 bg-gray-200 px-3 py-1 rounded-full">
                                    {{ $colorInfo['color'] ?? 'N/A' }} 
                                    @if(isset($colorInfo['qty']) && !empty($colorInfo['qty']))
                                        <span class="ml-1.5 text-xs text-gray-500">({{ $colorInfo['qty'] }} pcs)</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <p class="text-sm text-gray-600 mt-2">Material: {{ $order->order_nailmaterial ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 mt-2">Weight: {{ $order->order_weight ?? 'N/A' }} GRAMS</p>
                    <p class="text-sm text-gray-600 mt-2">Die Set: {{ trim($order->dieset ?? '') !== '' ? $order->dieset : 'No' }}</p>
                    <p class="text-sm text-gray-600 mt-2">Nail With Logo: {{ trim($order->order_nailmaterial ?? '') !== '' ? $order->order_nailmaterial : 'No' }}</p>
                </div>

                <div class="print-summary w-full max-w-xs text-gray-700">
                    <h3 class="text-base font-semibold text-gray-900">Summary</h3>
                    <div class="space-y-3 mt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium text-gray-900">{{ number_format($order->supplier_price * $order->order_qty, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mould Price:</span>
                            <span class="font-medium text-gray-900">{{ number_format($order->supplier_mould_price, 2) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between">
                            <strong class="text-gray-900">PO Total:</strong>
                            <strong class="text-xl text-gray-900">{{ number_format($order->supplier_total, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- REMARKS -->
            <div class="mt-12 border-t pt-8">
                <h3 class="text-base font-semibold text-gray-900">Remarks</h3>
                <p class="text-gray-600 text-sm mt-2">{{ $order->supplier_remark ?? $order->order_remark ?? 'N/A' }}</p>
            </div>

            <!-- ATTACHMENTS -->
            <div class="mt-8">
                <h3 class="text-base font-semibold text-gray-900">Attachments</h3>
                <div class="flex space-x-6 mt-2">
                    @if($order->order_image)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Order Image</p>
                            <a href="{{ asset('storage/app/public/' . $order->order_image) }}" target="_blank">
                                <img src="{{ asset('storage/app/public/' . $order->order_image) }}" class="h-40 w-auto border rounded-lg shadow-sm object-cover">
                            </a>
                        </div>
                    @endif
                    <div class="space-y-2">
                        @if($order->order_logo)
                            <a href="{{ asset('storage/app/public/' . $order->order_logo) }}" target="_blank" class="flex items-center text-indigo-600 hover:underline"><i class="fa-solid fa-image w-5 mr-2"></i> View Logo</a>
                        @endif
                        @if($order->order_cdr)
                            <a href="{{ asset('storage/app/public/' . $order->order_cdr) }}" target="_blank" class="flex items-center text-indigo-600 hover:underline"><i class="fa-solid fa-file-zipper w-5 mr-2"></i> Download CDR File</a>
                        @endif
                        @if($order->order_po)
                            <a href="{{ asset('storage/app/public/' . $order->order_po) }}" target="_blank" class="flex items-center text-indigo-600 hover:underline"><i class="fa-solid fa-file-invoice w-5 mr-2"></i> View Purchase Order Doc</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="mt-16 border-t pt-6 text-center text-xs text-gray-500">
                <p>Purchase Order #{{ $order->id }}</p>
            </div>

        </div>
    </div>
</body>
</html>
