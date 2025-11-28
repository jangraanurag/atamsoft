@extends('layout')
@section('title', 'Generated Supplier Report')
@section('header')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 5mm;
        }
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-size: 10px; /* Smaller font for print */
            background-color: white !important;
        }
        /* Hide navigation and other non-print elements */
        header, footer, .no-print {
            display: none !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        /* Compact table styles */
        table {
            width: 100% !important;
            font-size: 9px;
        }
        th, td {
            padding: 2px 4px !important;
            white-space: normal; /* Allow wrapping */
        }
        /* Ensure images print */
        img {
            max-width: 40px !important;
            height: auto !important;
        }
    }
</style>
@endsection

@section('content')

<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Supplier Report</h1>
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i> Print Report
            </button>
        </div>

        <div class="mb-8">
            <table class="w-full sm:w-2/3 md:w-1/2 border-2 border-cyan-700">
                <thead>
                    <tr class="bg-cyan-100" style="background-color: #e0f7fa;">
                        <th class="px-4 py-2 text-left text-xs font-bold text-cyan-800 uppercase border-b border-cyan-700">No of Suppliers</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-cyan-800 uppercase border-b border-cyan-700">Number of Orders</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-cyan-800 uppercase border-b border-cyan-700">Total Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-cyan-800 uppercase border-b border-cyan-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-cyan-100" style="background-color: #e0f7fa;">
                        <td class="px-4 py-2 text-sm text-gray-800 border-t border-cyan-700">{{ $orders->unique('supplier_id')->count() }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 border-t border-cyan-700">{{ $totalOrders }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 border-t border-cyan-700">{{ number_format($totalAmount, 2) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 border-t border-cyan-700">
                            <form action="{{ route('order.bulkEditForm') }}" method="POST">
                                @csrf
                                @foreach($orders as $order)
                                    <input type="hidden" name="order_ids[]" value="{{ $order->id }}">
                                @endforeach
                                <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 text-xs">
                                    Edit Orders
                                </button>
                            </form>
                        </td>
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
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Supplier Name</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Date(s)</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">TAT</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-cyan-800 uppercase">Payment / Shipper</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyan-200" style="background-color: #e0f7fa;">
                        @php
                            $totalQuantity = 0;
                            $totalAmount = 0;
                            $totalPayment = 0; 
                        @endphp

                        @forelse($orders as $order)
                        <tr style="background-color: #e0f7fa;">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->id }}</td>
                            <td class="px-4 py-3">
                                @if($order->order_image)
                                    <img src="{{ asset('storage/app/public/'.$order->order_image) }}" alt="img" class="h-10 w-10 object-cover rounded">
                                @else
                                    <span class="text-gray-400 text-xs">[No Image]</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->product->product_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->brand->brand_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->order_qty ?? 0 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ number_format($order->supplier_price, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ number_format($order->supplier_total, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->supplier->supplier_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->order_date ? $order->order_date->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->order_deliverydate ? $order->order_deliverydate->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $order->shipper->shipper_name ?? 'N/A' }}</td>
                        </tr>
                            @php
                                $totalQuantity += $order->order_qty ?? 0;
                                $totalAmount += $order->supplier_total ?? 0;
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
                            <td class="px-4 py-3 text-sm uppercase" colspan="4">Total Amount</td>
                            <td class="px-4 py-3 text-sm">{{ $totalQuantity }}</td>
                            <td class="px-4 py-3 text-sm"></td>
                            <td class="px-4 py-3 text-sm">{{ number_format($totalAmount, 2) }}</td>
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
