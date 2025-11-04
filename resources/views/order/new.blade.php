@extends('layout')
@section('title', 'Create New Order')

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    .modal-overlay {
        transition: opacity 0.3s ease;
    }
    .modal-container {
        transition: transform 0.3s ease;
    }
    .required-label::after {
        content: ' *';
        color: #ef4444; /* red-500 */
    }
    /* Custom scrollbar for better aesthetics */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9; /* slate-100 */
    }
    ::-webkit-scrollbar-thumb {
        background: #94a3b8; /* slate-400 */
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #64748b; /* slate-500 */
    }
    .custom-file-input::-webkit-file-upload-button {
        visibility: hidden;
    }
    .custom-file-input::before {
        content: 'Select file';
        display: inline-block;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        outline: none;
        white-space: nowrap;
        -webkit-user-select: none;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.875rem;
        color: #4338ca;
    }
    .custom-file-input:hover::before {
        border-color: #a78bfa;
    }
    .custom-file-input:active::before {
        background-color: #e0e7ff;
    }

</style>
@endsection

@section('content')
<div class="bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">

        <div class="max-w-5xl mx-auto">
            <div class="flex items-center mb-8">
                <span class="p-3 bg-white rounded-full shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                <h1 class="ml-4 text-3xl font-bold text-slate-800 tracking-tight">Create New Order</h1>
            </div>

            <form action="{{ route('order.create') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Section: Core Order Details -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        Core Details
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Buyer -->
                        <div>
                            <label for="order_buyer" class="block text-sm font-medium text-slate-700 required-label">Buyer</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_buyer" name="order_buyer" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_buyer') border-red-500 @enderror" required>
                                    <option value="">Select a Buyer</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}" @if(old('order_buyer') == $buyer->id) selected @endif>{{ $buyer->buyer_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddModal('buyer')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                            @error('order_buyer')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Brand -->
                        <div>
                            <label for="order_brand" class="block text-sm font-medium text-slate-700">Brand</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_brand" name="order_brand" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_brand') border-red-500 @enderror">
                                    <option value="">Select a Brand</option>
                                     @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @if(old('order_brand') == $brand->id) selected @endif>{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddModal('brand')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                             @error('order_brand')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <!-- Product Name -->
                        <div>
                            <label for="order_productName" class="block text-sm font-medium text-slate-700">Product Name</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_productName" name="order_productName" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_productName') border-red-500 @enderror">
                                    <option value="">Select a Product</option>
                                     @foreach($products as $product)
                                        <option value="{{ $product->id }}" @if(old('order_productName') == $product->id) selected @endif>{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddModal('product')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                             @error('order_productName')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <!-- Label Type -->
                        <div>
                            <label for="order_labelType" class="block text-sm font-medium text-slate-700">Label Type</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_labelType" name="order_labelType" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_labelType') border-red-500 @enderror">
                                   <option value="">Select a Label Type</option>
                                    @foreach($labelTypes as $labelType)
                                        <option value="{{ $labelType->id }}" @if(old('order_labelType') == $labelType->id) selected @endif>{{ $labelType->labeltype_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddModal('labelType')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                             @error('order_labelType')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <!-- Order Date & Delivery Date -->
                        <div>
                            <label for="order_date" class="block text-sm font-medium text-slate-700">Order Date</label>
                            <input type="date" name="order_date" id="order_date" value="{{ old('order_date') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_date') border-red-500 @enderror">
                            @error('order_date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_deliverydate" class="block text-sm font-medium text-slate-700">Delivery Date</label>
                            <input type="date" name="order_deliverydate" id="order_deliverydate" value="{{ old('order_deliverydate') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_deliverydate') border-red-500 @enderror">
                            @error('order_deliverydate')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Quantity & Dimensions -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                        Quantity & Dimensions
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="order_qty" class="block text-sm font-medium text-slate-700">Order Qty</label>
                            <input type="number" step="any" name="order_qty" id="order_qty" value="{{ old('order_qty') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_qty') border-red-500 @enderror">
                            @error('order_qty')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_manufacturedqty" class="block text-sm font-medium text-slate-700">Manufactured Qty</label>
                            <input type="number" step="any" name="order_manufacturedqty" id="order_manufacturedqty" value="{{ old('order_manufacturedqty') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_manufacturedqty') border-red-500 @enderror">
                            @error('order_manufacturedqty')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_unit" class="block text-sm font-medium text-slate-700">Unit</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_unit" name="order_unit" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_unit') border-red-500 @enderror">
                                    <option value="">Select a Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" @if(old('order_unit') == $unit->id) selected @endif>{{ $unit->unit_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddModal('unit')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                            @error('order_unit')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_weight" class="block text-sm font-medium text-slate-700">Weight</label>
                            <input type="number" step="any" name="order_weight" id="order_weight" value="{{ old('order_weight') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_weight') border-red-500 @enderror">
                            @error('order_weight')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_length" class="block text-sm font-medium text-slate-700">Length</label>
                            <input type="number" step="any" name="order_length" id="order_length" value="{{ old('order_length') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_length') border-red-500 @enderror">
                            @error('order_length')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_width" class="block text-sm font-medium text-slate-700">Width</label>
                            <input type="number" step="any" name="order_width" id="order_width" value="{{ old('order_width') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_width') border-red-500 @enderror">
                            @error('order_width')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label for="order_d_unit" class="block text-sm font-medium text-slate-700">Dimension Unit</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_d_unit" name="order_d_unit" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_d_unit') border-red-500 @enderror">
                                    <option value="">Select Dimension Unit</option>
                                    @foreach($d_units as $d_unit)
                                        <option value="{{ $d_unit->id }}" @if(old('order_d_unit') == $d_unit->id) selected @endif>{{ $d_unit->dunit_name }}</option>
                                    @endforeach
                                </select>
                               <button type="button" onclick="openAddModal('d_unit')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                            @error('order_d_unit')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Colors -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                        Colors
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Total Colors</label>
                            <p id="color_total_display" class="mt-1 text-2xl font-bold text-indigo-600">0</p>
                        </div>
                        <div>
                            <label for="color_add_count" class="block text-sm font-medium text-slate-700">Add Fields</label>
                            <input type="number" id="color_add_count" min="1" value="1" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <button type="button" id="add_color_fields_btn" class="w-full justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all">
                                Add Color(s)
                            </button>
                        </div>
                    </div>
                    <div id="color_fields_container" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if(is_array(old('colors')))
                            @foreach(old('colors') as $color)
                                @if(!empty($color))
                                <div class="relative group">
                                    <label for="color_{{ $loop->index }}" class="block text-sm font-medium text-slate-700 mb-1">Color {{ $loop->iteration }}</label>
                                    <input type="text" name="colors[]" id="color_{{ $loop->index }}" value="{{ $color }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter color name or hex code">
                                    <button type="button" onclick="this.parentElement.remove(); updateColorFields();" class="absolute top-8 right-2 text-slate-400 hover:text-red-500 p-1 rounded-full focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                     @error('colors.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Section: Shipping & Status -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                        Shipping & Status
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-2">
                            <label for="order_shipvia" class="block text-sm font-medium text-slate-700">Ship Via</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_shipvia" name="order_shipvia" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_shipvia') border-red-500 @enderror">
                                    <option value="">Select a Shipper</option>
                                    @foreach($shippers as $shipper)
                                        <option value="{{ $shipper->id }}" @if(old('order_shipvia') == $shipper->id) selected @endif>{{ $shipper->shipper_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddModal('shipper')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                             @error('order_shipvia')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="lg:col-span-2">
                            <label for="order_shipmentno" class="block text-sm font-medium text-slate-700">Shipment No</label>
                            <input type="text" name="order_shipmentno" id="order_shipmentno" value="{{ old('order_shipmentno') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_shipmentno') border-red-500 @enderror">
                             @error('order_shipmentno')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="lg:col-span-2">
                            <label for="order_status" class="block text-sm font-medium text-slate-700">Order Status</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <select id="order_status" name="order_status" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_status') border-red-500 @enderror">
                                    <option value="">Select a Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" @if(old('order_status') == $status->id) selected @endif>{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openAddModal('status')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                             @error('order_status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="lg:col-span-2">
                            <label for="order_salesman" class="block text-sm font-medium text-slate-700">Salesman</label>
                            <input type="text" name="order_salesman" id="order_salesman" value="{{ old('order_salesman') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_salesman') border-red-500 @enderror">
                             @error('order_salesman')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Materials & Attachments -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        Materials & Attachments
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="order_nailmaterial" class="block text-sm font-medium text-slate-700">Nail Material</label>
                            <input type="text" name="order_nailmaterial" id="order_nailmaterial" value="{{ old('order_nailmaterial') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_nailmaterial') border-red-500 @enderror">
                             @error('order_nailmaterial')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex items-center pt-6">
                             <div class="flex items-center h-5">
                                <input id="dieset" name="dieset" type="checkbox" value="1" @if(old('dieset')) checked @endif class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-slate-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="dieset" class="font-medium text-slate-700">Die Set</label>
                                <p class="text-slate-500">Check if a die set is included.</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                        <div>
                            <label for="order_logo" class="block text-sm font-medium text-slate-700">Order Logo</label>
                            <input type="file" name="order_logo" id="order_logo" class="mt-1 block w-full text-sm text-slate-500 custom-file-input @error('order_logo') border-red-500 @enderror">
                            @error('order_logo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_image" class="block text-sm font-medium text-slate-700">Order Image</label>
                            <input type="file" name="order_image" id="order_image" class="mt-1 block w-full text-sm text-slate-500 custom-file-input @error('order_image') border-red-500 @enderror">
                             @error('order_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_cdr" class="block text-sm font-medium text-slate-700">CDR File</label>
                            <input type="file" name="order_cdr" id="order_cdr" class="mt-1 block w-full text-sm text-slate-500 custom-file-input @error('order_cdr') border-red-500 @enderror">
                             @error('order_cdr')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="order_po" class="block text-sm font-medium text-slate-700">PO File</label>
                            <input type="file" name="order_po" id="order_po" class="mt-1 block w-full text-sm text-slate-500 custom-file-input @error('order_po') border-red-500 @enderror">
                             @error('order_po')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Pricing -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Pricing
                    </h2>
                    <div x-data="{ supplierSection: {{ old('supplier_id') ? 'true' : 'false' }} }">
                        <!-- Buyer Pricing -->
                        <h3 class="text-lg font-medium text-slate-600 mb-4">Buyer Pricing</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="order_price" class="block text-sm font-medium text-slate-700">Buyer Price</label>
                                <input type="number" step="any" name="order_price" id="order_price" value="{{ old('order_price') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_price') border-red-500 @enderror" oninput="calculateBuyerTotal()">
                                 @error('order_price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="order_mouldprice" class="block text-sm font-medium text-slate-700">Buyer Mould Price</label>
                                <input type="number" step="any" name="order_mouldprice" id="order_mouldprice" value="{{ old('order_mouldprice') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_mouldprice') border-red-500 @enderror" oninput="calculateBuyerTotal()">
                                 @error('order_mouldprice')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="buyer_total" class="block text-sm font-medium text-slate-700">Buyer Total</label>
                                <input type="text" name="buyer_total" id="buyer_total" value="{{ old('buyer_total') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm bg-slate-100 sm:text-sm" readonly>
                            </div>
                        </div>

                        <!-- Supplier Pricing -->
                        <div class="mt-8">
                             <h3 class="text-lg font-medium text-slate-600 mb-4">Supplier Information</h3>
                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                 <div class="lg:col-span-2">
                                    <label for="supplier_id" class="block text-sm font-medium text-slate-700">Supplier</label>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <select id="supplier_id" name="supplier_id" x-on:change="supplierSection = $event.target.value !== ''" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('supplier_id') border-red-500 @enderror">
                                           <option value="">Select a Supplier</option>
                                           @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" @if(old('supplier_id') == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="openAddModal('supplier')" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-105">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </div>
                                    @error('supplier_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div x-show="supplierSection" x-cloak>
                                    <label for="supplier_price" class="block text-sm font-medium text-slate-700">Supplier Price</label>
                                    <input type="number" step="any" name="supplier_price" id="supplier_price" value="{{ old('supplier_price') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('supplier_price') border-red-500 @enderror" oninput="calculateSupplierTotal()">
                                    @error('supplier_price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                 <div x-show="supplierSection" x-cloak>
                                    <label for="supplier_mould_price" class="block text-sm font-medium text-slate-700">Supplier Mould Price</label>
                                    <input type="number" step="any" name="supplier_mould_price" id="supplier_mould_price" value="{{ old('supplier_mould_price') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('supplier_mould_price') border-red-500 @enderror" oninput="calculateSupplierTotal()">
                                    @error('supplier_mould_price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="lg:col-span-4" x-show="supplierSection" x-cloak>
                                    <label for="supplier_total" class="block text-sm font-medium text-slate-700">Supplier Total</label>
                                    <input type="text" name="supplier_total" id="supplier_total" value="{{ old('supplier_total') }}" class="mt-1 block w-full max-w-xs rounded-lg border-slate-300 shadow-sm bg-slate-100 sm:text-sm" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Billing & Shipping Addresses -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Billing & Shipping Addresses
                    </h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Bill To -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-slate-600">Bill To</h3>
                                <button type="button" onclick="fetchBuyerAddress()" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Copy from Buyer</button>
                            </div>
                            <div class="space-y-4">
                                <input type="text" name="bill_to_name" placeholder="Name" value="{{ old('bill_to_name') }}" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm @error('bill_to_name') border-red-500 @enderror">
                                <input type="text" name="bill_to_address_line_1" placeholder="Address Line 1" value="{{ old('bill_to_address_line_1') }}" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm @error('bill_to_address_line_1') border-red-500 @enderror">
                                <input type="text" name="bill_to_address_line_2" placeholder="Address Line 2" value="{{ old('bill_to_address_line_2') }}" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm @error('bill_to_address_line_2') border-red-500 @enderror">
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="bill_to_city" placeholder="City" value="{{ old('bill_to_city') }}" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm @error('bill_to_city') border-red-500 @enderror">
                                    <input type="text" name="bill_to_state" placeholder="State" value="{{ old('bill_to_state') }}" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm @error('bill_to_state') border-red-500 @enderror">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="bill_to_postal_code" placeholder="Postal Code" value="{{ old('bill_to_postal_code') }}" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm @error('bill_to_postal_code') border-red-500 @enderror">
                                    <input type="text" name="bill_to_country" placeholder="Country" value="{{ old('bill_to_country') }}" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm @error('bill_to_country') border-red-500 @enderror">
                                </div>
                            </div>
                        </div>
                        <!-- Ship To -->
                        <div x-data="{ sameAsBilling: false }">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-slate-600">Ship To</h3>
                                <div class="flex items-center">
                                    <input id="same_as_billing" type="checkbox" x-model="sameAsBilling" @change="copyBillToShipTo(sameAsBilling)" class="h-4 w-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                    <label for="same_as_billing" class="ml-2 block text-sm text-slate-900">Same as Bill To</label>
                                </div>
                            </div>
                            <div id="ship_to_fields" class="space-y-4">
                                <input type="text" name="ship_to_name" placeholder="Name" value="{{ old('ship_to_name') }}" :disabled="sameAsBilling" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm disabled:bg-slate-100 @error('ship_to_name') border-red-500 @enderror">
                                <input type="text" name="ship_to_address_line_1" placeholder="Address Line 1" value="{{ old('ship_to_address_line_1') }}" :disabled="sameAsBilling" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm disabled:bg-slate-100 @error('ship_to_address_line_1') border-red-500 @enderror">
                                <input type="text" name="ship_to_address_line_2" placeholder="Address Line 2" value="{{ old('ship_to_address_line_2') }}" :disabled="sameAsBilling" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm disabled:bg-slate-100 @error('ship_to_address_line_2') border-red-500 @enderror">
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="ship_to_city" placeholder="City" value="{{ old('ship_to_city') }}" :disabled="sameAsBilling" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm disabled:bg-slate-100 @error('ship_to_city') border-red-500 @enderror">
                                    <input type="text" name="ship_to_state" placeholder="State" value="{{ old('ship_to_state') }}" :disabled="sameAsBilling" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm disabled:bg-slate-100 @error('ship_to_state') border-red-500 @enderror">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="ship_to_postal_code" placeholder="Postal Code" value="{{ old('ship_to_postal_code') }}" :disabled="sameAsBilling" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm disabled:bg-slate-100 @error('ship_to_postal_code') border-red-500 @enderror">
                                    <input type="text" name="ship_to_country" placeholder="Country" value="{{ old('ship_to_country') }}" :disabled="sameAsBilling" class="block w-full rounded-lg border-slate-300 shadow-sm sm:text-sm disabled:bg-slate-100 @error('ship_to_country') border-red-500 @enderror">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Remarks -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6 border-b pb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2V7a2 2 0 012-2h2m8-4H5a2 2 0 00-2 2v10a2 2 0 002 2h11l4 4V7a2 2 0 00-2-2z" /></svg>
                        Remarks
                    </h2>
                     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="order_remark" class="block text-sm font-medium text-slate-700">General Order Remark</label>
                            <textarea id="order_remark" name="order_remark" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('order_remark') border-red-500 @enderror">{{ old('order_remark') }}</textarea>
                            @error('order_remark')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="buyer_remark" class="block text-sm font-medium text-slate-700">Buyer Remark</label>
                            <textarea id="buyer_remark" name="buyer_remark" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('buyer_remark') border-red-500 @enderror">{{ old('buyer_remark') }}</textarea>
                            @error('buyer_remark')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                         <div>
                            <label for="supplier_remark" class="block text-sm font-medium text-slate-700">Supplier Remark</label>
                            <textarea id="supplier_remark" name="supplier_remark" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('supplier_remark') border-red-500 @enderror">{{ old('supplier_remark') }}</textarea>
                            @error('supplier_remark')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                     </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-5">
                    <button type="button" class="bg-slate-100 py-2 px-5 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center py-2 px-5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add New Item Modal -->
<div id="add-modal" class="modal-overlay fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 hidden z-50">
    <div class="modal-container bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all" onclick="event.stopPropagation()">
        <div class="p-6">
            <div class="flex justify-between items-center pb-3 border-b border-slate-200">
                <h2 id="modal-title" class="text-xl font-bold text-slate-800">Add New Item</h2>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 rounded-full p-1 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div id="modal-body" class="mt-4 space-y-4">
                <!-- Dynamic form fields will be injected here -->
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="bg-slate-100 py-2 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
                <button type="button" id="save-modal-button" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-indigo-700">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateColorFields();
    });

    const modalConfig = {
        buyer: {
            title: 'Add New Buyer',
            fields: [
                { name: 'buyer_name', label: 'Buyer Name', type: 'text', required: true },
                { name: 'buyer_email_id', label: 'Email', type: 'email' },
                { name: 'buyer_mobile_no', label: 'Mobile', type: 'tel' },
            ],
            api: { create: '{{ route("api.buyers.store") }}', list: '{{ route("api.buyers.index") }}' },
            selectId: 'order_buyer', optionValue: 'id', optionText: 'buyer_name'
        },
        brand: {
            title: 'Add New Brand',
            fields: [
                { name: 'brand_name', label: 'Brand Name', type: 'text', required: true },
                { name: 'buyer_id', label: 'Buyer', type: 'hidden' }
            ],
            api: { create: '{{ route("api.brands.store") }}', list: '{{ route("api.brands.index") }}' },
            selectId: 'order_brand', optionValue: 'id', optionText: 'brand_name'
        },
        product: {
            title: 'Add New Product',
            fields: [{ name: 'product_name', label: 'Product Name', type: 'text', required: true }],
            api: { create: '{{ route("api.products.store") }}', list: '{{ route("api.products.index") }}' },
            selectId: 'order_productName', optionValue: 'id', optionText: 'product_name'
        },
        labelType: {
            title: 'Add New Label Type',
            fields: [{ name: 'labeltype_name', label: 'Label Type Name', type: 'text', required: true }],
            api: { create: '{{ route("api.label-types.store") }}', list: '{{ route("api.label-types.index") }}' },
            selectId: 'order_labelType', optionValue: 'id', optionText: 'labeltype_name'
        },
        unit: {
            title: 'Add New Unit',
            fields: [
                { name: 'unit_name', label: 'Unit Name', type: 'text', required: true },
                { name: 'unit_shorthand', label: 'Shorthand', type: 'text', required: true },
            ],
            api: { create: '{{ route("api.units.store") }}', list: '{{ route("api.units.index") }}' },
            selectId: 'order_unit', optionValue: 'id', optionText: 'unit_name'
        },
        d_unit: {
            title: 'Add New Dimension Unit',
            fields: [
                { name: 'dunit_name', label: 'Dimension Unit Name', type: 'text', required: true },
                { name: 'dunit_shorthand', label: 'Shorthand', type: 'text', required: true },
            ],
            api: { create: '{{ route("api.d-units.store") }}', list: '{{ route("api.d-units.index") }}' },
            selectId: 'order_d_unit', optionValue: 'id', optionText: 'dunit_name'
        },
        supplier: {
            title: 'Add New Supplier',
            fields: [
                { name: 'name', label: 'Supplier Name', type: 'text', required: true },
                { name: 'email', label: 'Email', type: 'email' },
            ],
            api: { create: '{{ route("api.suppliers.store") }}', list: '{{ route("api.suppliers.index") }}' },
            selectId: 'supplier_id', optionValue: 'id', optionText: 'name'
        },
        shipper: {
            title: 'Add New Shipper',
            fields: [{ name: 'shipper_name', label: 'Shipper Name', type: 'text', required: true }],
            api: { create: '{{ route("api.shippers.store") }}', list: '{{ route("api.shippers.index") }}' },
            selectId: 'order_shipvia', optionValue: 'id', optionText: 'shipper_name'
        },
        status: {
            title: 'Add New Status',
            fields: [{ name: 'status_name', label: 'Status Name', type: 'text', required: true }],
            api: { create: '{{ route("api.statuses.store") }}', list: '{{ route("api.statuses.index") }}' },
            selectId: 'order_status', optionValue: 'id', optionText: 'status_name'
        }
    };

    const modal = document.getElementById('add-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');
    const saveButton = document.getElementById('save-modal-button');

    function openAddModal(type) {
        const config = modalConfig[type];
        if (!config) return;

        modalTitle.textContent = config.title;
        modalBody.innerHTML = '';

        config.fields.forEach(field => {
            if (field.name === 'buyer_id') {
                const buyerId = document.getElementById('order_buyer').value;
                if (!buyerId) {
                    alert('Please select a buyer before adding a brand.');
                    return;
                }
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = field.name;
                input.id = `modal_${field.name}`;
                input.value = buyerId;
                modalBody.appendChild(input);
                return;
            }

            const fieldGroup = document.createElement('div');
            const label = document.createElement('label');
            label.htmlFor = `modal_${field.name}`;
            label.className = 'block text-sm font-medium text-slate-700';
            label.textContent = field.label;

            const input = document.createElement('input');
            input.type = field.type;
            input.name = field.name;
            input.id = `modal_${field.name}`;
            input.className = 'mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm';
            if (field.required) {
                input.required = true;
                label.classList.add('required-label');
            }

            fieldGroup.appendChild(label);
            fieldGroup.appendChild(input);
            modalBody.appendChild(fieldGroup);
        });

        saveButton.onclick = () => saveModalData(type);
        modal.classList.add('flex');
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function saveModalData(type) {
        const config = modalConfig[type];
        const payload = {};
        let isValid = true;

        config.fields.forEach(field => {
            const input = document.getElementById(`modal_${field.name}`);
            if (input) {
                if (input.required && !input.value) isValid = false;
                payload[field.name] = input.value;
            }
        });

        if (!isValid) {
            alert('Please fill all required fields.');
            return;
        }

        try {
            const response = await fetch(config.api.create, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to save item.');
            }

            const newItem = await response.json();
            await refreshSelect(config, newItem.id);
            closeModal();

        } catch (error) {
            console.error('Error saving data:', error);
            alert('Error: ' + error.message);
        }
    }

    async function refreshSelect(config, selectedId = null) {
        try {
            const listResponse = await fetch(config.api.list);
            if (!listResponse.ok) throw new Error('Failed to fetch updated list.');

            const items = await listResponse.json();
            const selectElement = document.getElementById(config.selectId);

            while (selectElement.options.length > 1) selectElement.remove(1);

            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item[config.optionValue];
                option.textContent = item[config.optionText];
                if (selectedId && item[config.optionValue] == selectedId) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            });

        } catch (error) {
            console.error(`Error refreshing ${config.selectId}:`, error);
        }
    }

    document.getElementById('add_color_fields_btn').addEventListener('click', () => {
        const addCountInput = document.getElementById('color_add_count');
        const container = document.getElementById('color_fields_container');
        const countToAdd = parseInt(addCountInput.value, 10);

        if (isNaN(countToAdd) || countToAdd <= 0) return;

        for (let i = 0; i < countToAdd; i++) {
            const newIndex = container.children.length;
            const fieldWrapper = document.createElement('div');
            fieldWrapper.className = 'relative group';

            const label = document.createElement('label');
            label.htmlFor = `color_${newIndex}`;
            label.className = 'block text-sm font-medium text-slate-700 mb-1';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'colors[]';
            input.id = `color_${newIndex}`;
            input.className = 'block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm';
            input.placeholder = 'Enter color name or hex';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            removeBtn.className = 'absolute top-8 right-2 text-slate-400 hover:text-red-500 p-1 rounded-full focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity';
            removeBtn.onclick = () => {
                container.removeChild(fieldWrapper);
                updateColorFields();
            };

            fieldWrapper.appendChild(label);
            fieldWrapper.appendChild(input);
            fieldWrapper.appendChild(removeBtn);
            container.appendChild(fieldWrapper);
        }
        updateColorFields();
    });

    function updateColorFields() {
        const container = document.getElementById('color_fields_container');
        const totalDisplay = document.getElementById('color_total_display');
        const fields = container.children;
        totalDisplay.textContent = fields.length;
        for (let i = 0; i < fields.length; i++) {
            fields[i].querySelector('label').textContent = `Color ${i + 1}`;
        }
    }

    function calculateTotal(priceId, mouldPriceId, totalId) {
        const price = parseFloat(document.getElementById(priceId).value) || 0;
        const mouldPrice = parseFloat(document.getElementById(mouldPriceId).value) || 0;
        document.getElementById(totalId).value = (price + mouldPrice).toFixed(2);
    }
    function calculateBuyerTotal() {
        calculateTotal('order_price', 'order_mouldprice', 'buyer_total');
    }
    function calculateSupplierTotal() {
        calculateTotal('supplier_price', 'supplier_mould_price', 'supplier_total');
    }

    async function fetchBuyerAddress() {
        const buyerId = document.getElementById('order_buyer').value;
        if (!buyerId) {
            alert('Please select a buyer first.');
            return;
        }
        try {
            const response = await fetch(`/api/buyers/${buyerId}`);
            if (!response.ok) throw new Error('Could not fetch buyer details.');
            const buyer = await response.json();

            document.querySelector('[name="bill_to_name"]').value = buyer.buyer_name || '';
            document.querySelector('[name="bill_to_address_line_1"]').value = buyer.buyer_address || '';
            document.querySelector('[name="bill_to_city"]').value = buyer.buyer_city || '';
            document.querySelector('[name="bill_to_state"]').value = buyer.buyer_state || '';
            document.querySelector('[name="bill_to_postal_code"]').value = ''; // Not in buyer table
            document.querySelector('[name="bill_to_country"]').value = buyer.buyer_country || '';
        } catch (error) {
            alert(error.message);
        }
    }

    function copyBillToShipTo(checked) {
        const shipToContainer = document.getElementById('ship_to_fields');
        const shipToInputs = shipToContainer.querySelectorAll('input');

        if (checked) {
            document.querySelector('[name="ship_to_name"]').value = document.querySelector('[name="bill_to_name"]').value;
            document.querySelector('[name="ship_to_address_line_1"]').value = document.querySelector('[name="bill_to_address_line_1"]').value;
            document.querySelector('[name="ship_to_address_line_2"]').value = document.querySelector('[name="bill_to_address_line_2"]').value;
            document.querySelector('[name="ship_to_city"]').value = document.querySelector('[name="bill_to_city"]').value;
            document.querySelector('[name="ship_to_state"]').value = document.querySelector('[name="bill_to_state"]').value;
            document.querySelector('[name="ship_to_postal_code"]').value = document.querySelector('[name="bill_to_postal_code"]').value;
            document.querySelector('[name="ship_to_country"]').value = document.querySelector('[name="bill_to_country"]').value;

            shipToInputs.forEach(input => input.disabled = true);
        } else {
             shipToInputs.forEach(input => {
                input.disabled = false;
                input.value = ''; // Clear the fields
             });
        }
    }

</script>
@endsection
