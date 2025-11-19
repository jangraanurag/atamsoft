@extends('layout')

@section('title', 'Manage Orders')

@section('content')
<h1 class="text-2xl font-bold mb-4">Order Management</h1>

@if(session('message'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-300 rounded">
        {{ session('message') }}
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-300 rounded">
        <strong>Whoops! Something went wrong.</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ========================================================= --}}
{{-- ============== START: NEW SUMMARY SECTION =============== --}}
{{-- ========================================================= --}}

@php
    // Calculate totals from the collection
    $totalOrderedQty = $orders->sum('order_qty');
    $totalManufacturedQty = $orders->sum('order_manufacturedqty');

    // Calculate total sale (buyer side) and purchase (supplier side)
    $totalSale = $orders->sum('buyer_total');

    $totalPurchase = $orders->sum('supplier_total');
@endphp

<div class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Order Summary</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-100 border border-blue-200 p-4 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-blue-800">Total Ordered Qty</h3>
            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalOrderedQty) }}</p>
        </div>
        <div class="bg-green-100 border border-green-200 p-4 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-green-800">Total Manufactured Qty</h3>
            <p class="text-2xl font-bold text-green-900">{{ number_format($totalManufacturedQty) }}</p>
        </div>
        <div class="bg-yellow-100 border border-yellow-200 p-4 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-yellow-800">Total Sale (Buyer)</h3>
            <p class="text-2xl font-bold text-yellow-900">{{ number_format($totalSale, 2) }}</p>
        </div>
        <div class="bg-red-100 border border-red-200 p-4 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-red-800">Total Purchase (Supplier)</h3>
            <p class="text-2xl font-bold text-red-900">{{ number_format($totalPurchase, 2) }}</p>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- =============== END: NEW SUMMARY SECTION ================ --}}
{{-- ========================================================= --}}


<div class="bg-white shadow rounded p-4">
    <form action="{{ route('order.updateStatus') }}" method="POST">
        @csrf

        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold mb-2">In-line Order Editing</h2>
            <p class="text-sm text-gray-600 mb-4">You can edit the details for each order directly in the table below. Click the "Update All Orders" button when you are finished to save all changes.</p>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fa-solid fa-save mr-1"></i> Update All Orders
                </button>
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="window.location.reload();">
                    <i class="fa-solid fa-rotate-right mr-1"></i> Reset Page
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        {{-- Non-editable columns --}}
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Order No</th>
                        <th class="p-2 border">Product</th>
                        <th class="p-2 border">Buyer</th>
                        <th class="p-2 border">Order Qty</th>

                        {{-- Editable columns --}}
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Manuf. Qty</th>
                        <th class="p-2 border">Delivery Date</th>
                        <th class="p-2 border">Weight</th>
                        <th class="p-2 border">Buyer Price</th>
                        <th class="p-2 border">Buyer Mould</th>
                        <th class="p-2 border">Supplier Price</th>
                        <th class="p-2 border">Supplier Mould</th>
                        <th class="p-2 border">Remark</th>
                        <th class="p-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            {{-- Non-editable data --}}
                            <td class="p-1 border align-top">{{ $loop->iteration }}</td>
                            <td class="p-1 border align-top">{{ $order->id }}</td>
                            <td class="p-1 border align-top">{{ $order->product->product_name ?? '-' }}</td>
                            <td class="p-1 border align-top">{{ $order->buyer->buyer_name ?? '-' }}</td>
                            <td class="p-1 border align-top">{{ $order->order_qty }}</td>

                            {{-- Editable fields --}}
                            <td class="p-1 border">
                                <select name="updates[{{ $order->id }}][order_status]" class="w-full border-gray-300 rounded text-xs p-1">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" @if($order->order_status == $status->id) selected @endif>{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-1 border"><input type="number" name="updates[{{ $order->id }}][order_manufacturedqty]" value="{{ $order->order_manufacturedqty }}" class="w-full border-gray-300 rounded text-xs p-1"></td>
                            <td class="p-1 border"><input type="date" name="updates[{{ $order->id }}][order_deliverydate]" value="{{ $order->order_deliverydate ? \Carbon\Carbon::parse($order->order_deliverydate)->format('Y-m-d') : '' }}" class="w-full border-gray-300 rounded text-xs p-1"></td>
                            <td class="p-1 border"><input type="number" step="any" name="updates[{{ $order->id }}][order_weight]" value="{{ $order->order_weight }}" class="w-20 border-gray-300 rounded text-xs p-1"></td>
                            <td class="p-1 border"><input type="number" step="any" name="updates[{{ $order->id }}][order_price]" value="{{ $order->order_price }}" class="w-20 border-gray-300 rounded text-xs p-1"></td>
                            <td class="p-1 border"><input type="number" step="any" name="updates[{{ $order->id }}][order_mouldprice]" value="{{ $order->order_mouldprice }}" class="w-20 border-gray-300 rounded text-xs p-1"></td>
                            <td class="p-1 border"><input type="number" step="any" name="updates[{{ $order->id }}][supplier_price]" value="{{ $order->supplier_price }}" class="w-20 border-gray-300 rounded text-xs p-1"></td>
                            <td class="p-1 border"><input type="number" step="any" name="updates[{{ $order->id }}][supplier_mould_price]" value="{{ $order->supplier_mould_price }}" class="w-20 border-gray-300 rounded text-xs p-1"></td>
                            <td class="p-1 border"><textarea name="updates[{{ $order->id }}][order_remark]" class="w-full border-gray-300 rounded text-xs p-1" rows="1">{{ $order->order_remark }}</textarea></td>

                            {{-- Action links --}}
                            <td class="p-1 border align-top">
                                <div class="flex space-x-2">
                                    <a href="{{ route('order.show', $order->id) }}" class="text-blue-600 hover:underline" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('order.destroy', $order->id) }}" onclick="return confirm('Delete this order?')" class="text-red-600 hover:underline" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="p-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection

@section('footer')
{{-- No specific JS needed for this approach --}}
@endsection
