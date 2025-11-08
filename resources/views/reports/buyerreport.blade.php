@extends('layout')
@section('title', 'Generated Buyer Report')
@section('content')

<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Buyer Report</h1>
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i> Print Report
            </button>
        </div>

        <div class="mb-8">
            <table class="w-full sm:w-1/2 md:w-1/3 border-2 border-cyan-700">
                <thead>
                    <tr class="bg-cyan-100" style="background-color: #e0f7fa;">
                        <th class="px-4 py-2 text-left text-xs font-bold text-cyan-800 uppercase border-b border-cyan-700">Number of Orders</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-cyan-800 uppercase border-b border-cyan-700">Total Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-cyan-800 uppercase border-b border-cyan-700">GST Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-cyan-100" style="background-color: #e0f7fa;">
                        <td class="px-4 py-2 text-sm text-gray-800 border-t border-cyan-700">{{ $totalOrders }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 border-t border-cyan-700">${{ number_format($totalAmount, 2) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 border-t border-cyan-700">${{ number_format($totalGst, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-cyan-100" style="background-color: #e0f7fa;">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Order No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Photo</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Brand</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Buyer Name</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Date(s)</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">TAT</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Payment / Shipper</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyan-200" style="background-color: #e0f7fa;">
                        @php
                            $totalQuantity = 0;
                            $totalAmount = 0;
                            $totalPayment = 0; // This is the '28088' number from the screenshot
                        @endphp

                        @forelse($orders as $order)
                        <tr style="background-color: #e0f7fa;">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->id }}</td>
                            <td class="px-4 py-3">
                                @if($order->order_image)
                                    <img src="{{ Storage::url($order->order_image) }}" alt="img" class="h-10 w-10 object-cover rounded">
                                @else
                                    <span class_ ="text-gray-400 text-xs">[No Image]</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->product->product_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->brand->brand_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->order_qty ?? 0 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">${{ number_format($order->order_price, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">${{ number_format($order->buyer_total, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->buyer->buyer_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->order_date ? $order->order_date->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->order_deliverydate ? $order->order_deliverydate->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->shipper->shipper_name ?? 'N/A' }}</td>
                        </tr>
                            @php
                                $totalQuantity += $order->order_qty ?? 0;
                                $totalAmount += $order->buyer_total ?? 0;
                                // This is a guess for the "28088" column. I'll sum the IDs as placeholder.
                                // You should change this to a real field if you have one.
                                $totalPayment += $order->id; 
                            @endphp
                        @empty
                        <tr style="background-color: #e0f7fa;">
                            <td colspan="11" class="text-center py-10 text-gray-500">
                                <p>No orders were selected.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background-color: #fff9c4;">
                        <tr class="font-bold text-gray-800">
                            <td class_ ="px-4 py-3 text-sm uppercase" colspan="4">Total Amount</td>
                            <td class="px-4 py-3 text-sm">{{ $totalQuantity }}</td>
                            <td class="px-4 py-3 text-sm"></td>
                            <td class="px-4 py-3 text-sm">${{ number_format($totalAmount, 2) }}</td>
                            <td class="px-4 py-3 text-sm uppercase" colspan="3">Total Quantity</td>
                            <td class="px-4 py-3 text-sm">{{ $totalPayment }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</main>
@endsection