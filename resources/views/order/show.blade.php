@extends('layout')
@section('title', 'Order Details - ' . $order->order_no)
@section('content')



<main class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->order_no }}</h1>
                 <ol class="flex items-center whitespace-nowrap min-w-0 mt-1" aria-label="breadcrumb">
                    <li class="text-sm">
                        <a class="flex items-center text-gray-500 hover:text-indigo-600" href="{{ route('home') }}">
                        Home
                        <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                        </a>
                    </li>
                    <li class="text-sm">
                        <a class="flex items-center text-gray-500 hover:text-indigo-600" href="{{ route('order.all') }}">
                        Orders
                        <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                        </a>
                    </li>
                    <li class="text-sm font-semibold text-gray-800 truncate" aria-current="page">
                        Order Details
                    </li>
                </ol>
            </div>
            <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fa-solid fa-print mr-2"></i>Print
                </button>
                <a href="{{ route('order.edit', ['id' => $order->id]) }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700">
                    <i class="fa-solid fa-pencil mr-2"></i>Edit Order
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6 sm:p-8 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Purchase Order</h2>
                        <p class="text-sm text-gray-500 mt-1">Product: {{ $order->order_productName }}</p>
                    </div>
                    <div class="flex flex-wrap sm:flex-nowrap gap-6 sm:gap-8 text-left sm:text-right">
                         <div>
                            <p class="text-sm text-gray-500">Order Date</p>
                            <p class="font-medium text-gray-800">{{ $order->order_date ? $order->order_date->format('F j, Y') : 'N/A' }}</p>
                        </div>
                         <div>
                            <p class="text-sm text-gray-500">Delivery Date</p>
                            <p class="font-medium text-gray-800">{{ $order->order_deliverydate ? $order->order_deliverydate->format('F j, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                {{ $order->status->Status_name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-gray-700">Bill To:</h3>
                        <address class="mt-2 not-italic text-gray-600">
                            <strong class="text-gray-900">{{ $order->bill_to_name }}</strong><br>
                            {{ $order->bill_to_address_line_1 }}<br>
                            @if($order->bill_to_address_line_2){{ $order->bill_to_address_line_2 }}<br>@endif
                            {{ $order->bill_to_city }}, {{ $order->bill_to_state }} {{ $order->bill_to_postal_code }}<br>
                            {{ $order->bill_to_country }}
                        </address>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Ship To:</h3>
                         <address class="mt-2 not-italic text-gray-600">
                            <strong class="text-gray-900">{{ $order->ship_to_name }}</strong><br>
                            {{ $order->ship_to_address_line_1 }}<br>
                            @if($order->ship_to_address_line_2){{ $order->ship_to_address_line_2 }}<br>@endif
                            {{ $order->ship_to_city }}, {{ $order->ship_to_state }} {{ $order->ship_to_postal_code }}<br>
                            {{ $order->ship_to_country }}
                        </address>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Product Details</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div><p class="text-sm text-gray-500">Product Name</p><p class="font-medium text-gray-800">{{ $order->product->product_name ?? $order->order_productName }}</p></div>
                        <div><p class="text-sm text-gray-500">Brand</p><p class="font-medium text-gray-800">{{ $order->brand->brand_name ?? $order->order_brand }}</p></div>
                        <div><p class="text-sm text-gray-500">Buyer</p><p class="font-medium text-gray-800">{{ $order->buyer->buyer_name ?? $order->order_buyer }}</p></div>
                        <div><p class="text-sm text-gray-500">Label Type</p><p class="font-medium text-gray-800">{{ $order->labelType->labeltype_name ?? $order->order_labelType }}</p></div>
                        <div><p class="text-sm text-gray-500">Quantity</p><p class="font-medium text-gray-800">{{ number_format($order->order_qty) }} {{ $order->unit->unit_shorthand ?? $order->order_unit }}</p></div>
                        <div><p class="text-sm text-gray-500">Manufactured Qty</p><p class="font-medium text-gray-800">{{ number_format($order->order_manufacturedqty) }}</p></div>
                        <div><p class="text-sm text-gray-500">Weight</p><p class="font-medium text-gray-800">{{ $order->order_weight }} kg</p></div>
                        <div><p class="text-sm text-gray-500">Dimensions (L x W)</p><p class="font-medium text-gray-800">{{ $order->order_length }} x {{ $order->order_width }} {{ $order->d_unit->d_unit_shorthand ?? $order->order_d_unit }}</p></div>
                        <div><p class="text-sm text-gray-500">Nail Material</p><p class="font-medium text-gray-800">{{ $order->order_nailmaterial ?? 'N/A' }}</p></div>
                    </div>
                    @if(!empty($order->colors))
                    <div class="mt-6">
                        <p class="text-sm text-gray-500">Colors</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if(is_array($order->colors) || is_object($order->colors))
    @foreach($order->colors as $color)
        <span class="flex items-center text-sm font-medium text-gray-700 bg-gray-200 px-3 py-1 rounded-full">
            {{ $color }}
        </span>
    @endforeach
@elseif(is_string($order->colors) && !empty($order->colors))
    {{-- Handle the case where it's a single color string --}}
    <span class="flex items-center text-sm font-medium text-gray-700 bg-gray-200 px-3 py-1 rounded-full">
        {{ $order->colors }}
    </span>
@endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Buyer Financials</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between"><span class="text-gray-600">Unit Price:</span><span class="font-medium text-gray-900">${{ number_format($order->order_price, 2) }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Mould Price:</span><span class="font-medium text-gray-900">${{ number_format($order->order_mouldprice, 2) }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Dieset Included:</span><span class="font-medium text-gray-900">{{ $order->dieset ? 'Yes' : 'No' }}</span></div>
                            <hr class="my-2">
                            <div class="flex justify-between"><strong class="text-gray-900">Buyer Total:</strong><strong class="text-lg text-indigo-600">${{ number_format($order->buyer_total, 2) }}</strong></div>
                        </div>
                    </div>
                     <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Supplier Financials</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between"><span class="text-gray-600">Supplier:</span><span class="font-medium text-gray-900">{{ $order->supplier->supplier_name ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Supplier Price:</span><span class="font-medium text-gray-900">${{ number_format($order->supplier_price, 2) }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Supplier Mould Price:</span><span class="font-medium text-gray-900">${{ number_format($order->supplier_mould_price, 2) }}</span></div>
                            <hr class="my-2">
                            <div class="flex justify-between"><strong class="text-gray-900">Supplier Total:</strong><strong class="text-lg text-gray-900">${{ number_format($order->supplier_total, 2) }}</strong></div>
                        </div>
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Logistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between"><span class="text-gray-600">Ship Via:</span><span class="font-medium text-gray-900">{{ $order->shipper->shipper_name ?? $order->order_shipvia }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Shipment No:</span><span class="font-mono text-gray-900">{{ $order->order_shipmentno ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Salesman:</span><span class="font-medium text-gray-900">{{ $order->order_salesman ?? 'N/A' }}</span></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Attachments</h3>
                        <div class="space-y-2">
                            @if($order->order_logo)<a href="{{ asset('storage/' . $order->order_logo) }}" target="_blank" class="flex items-center text-indigo-600 hover:underline"><i class="fa-solid fa-image w-5 mr-2"></i> View Logo</a>@endif
                            @if($order->order_image)<a href="{{ asset('storage/' . $order->order_image) }}" target="_blank" class="flex items-center text-indigo-600 hover:underline"><i class="fa-solid fa-camera-retro w-5 mr-2"></i> View Product Image</a>@endif
                            @if($order->order_cdr)<a href="{{ asset('storage/' . $order->order_cdr) }}" target="_blank" class="flex items-center text-indigo-600 hover:underline"><i class="fa-solid fa-file-zipper w-5 mr-2"></i> Download CDR File</a>@endif
                            @if($order->order_po)<a href="{{ asset('storage/' . $order->order_po) }}" target="_blank" class="flex items-center text-indigo-600 hover:underline"><i class="fa-solid fa-file-invoice w-5 mr-2"></i> View Purchase Order Doc</a>@endif
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Remarks</h3>
                    <div class="space-y-4">
                        @if($order->order_remark)<div class="p-4 bg-gray-50 rounded-lg"><p class="text-sm font-semibold text-gray-700">General Remarks</p><p class="text-gray-600 mt-1">{{ $order->order_remark }}</p></div>@endif
                        @if($order->buyer_remark)<div class="p-4 bg-blue-50 rounded-lg"><p class="text-sm font-semibold text-blue-800">Buyer Remarks</p><p class="text-blue-700 mt-1">{{ $order->buyer_remark }}</p></div>@endif
                        @if($order->supplier_remark)<div class="p-4 bg-green-50 rounded-lg"><p class="text-sm font-semibold text-green-800">Supplier Remarks</p><p class="text-green-700 mt-1">{{ $order->supplier_remark }}</p></div>@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
