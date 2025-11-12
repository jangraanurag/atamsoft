{{-- This file is loaded via AJAX into the modal. It does not extend the layout. --}}
<form action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    {{-- MODAL BODY --}}
    <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
        
        <div class="flex justify-between items-start">
            <h3 class="text-2xl font-semibold text-gray-800">Edit Order #{{ $order->id }}</h3>
            <button type="button" id="close-edit-modal-button-x" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        {{-- Main Details --}}
        <fieldset class="grid grid-cols-1 md:grid-cols-3 gap-4 border p-4 rounded-lg">
            <legend class="text-lg font-medium text-blue-600 px-2">Main Details</legend>
            <div>
                <label for="edit_order_buyer" class="block text-sm font-medium text-gray-700">Buyer *</label>
                <select id="edit_order_buyer" name="order_buyer" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach($buyers as $buyer)
                    <option value="{{ $buyer->id }}" @selected(old('order_buyer', $order->order_buyer) == $buyer->id)>{{ $buyer->buyer_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="edit_order_brand" class="block text-sm font-medium text-gray-700">Brand</label>
                <select id="edit_order_brand" name="order_brand" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Select Brand --</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(old('order_brand', $order->order_brand) == $brand->id)>{{ $brand->brand_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="edit_order_productName" class="block text-sm font-medium text-gray-700">Product</label>
                <select id="edit_order_productName" name="order_productName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(old('order_productName', $order->order_productName) == $product->id)>{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="edit_order_date" class="block text-sm font-medium text-gray-700">Order Date</label>
                <input type="date" id="edit_order_date" name="order_date" value="{{ old('order_date', $order->order_date ? $order->order_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="edit_order_deliverydate" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                <input type="date" id="edit_order_deliverydate" name="order_deliverydate" value="{{ old('order_deliverydate', $order->order_deliverydate ? $order->order_deliverydate->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="edit_order_status" class="block text-sm font-medium text-gray-700">Order Status</label>
                <select id="edit_order_status" name="order_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach($statuses as $status)
                    <option value="{{ $status->id }}" @selected(old('order_status', $order->order_status) == $status->id)>{{ $status->status_name }}</option>
                    @endforeach
                </select>
            </div>
        </fieldset>

        {{-- Quantity & Specs --}}
        <fieldset class="border p-4 rounded-lg">
            <legend class="text-lg font-medium text-blue-600 px-2">Quantity & Specs</legend>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="edit_order_qty" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="edit_order_qty" name="order_qty" value="{{ old('order_qty', $order->order_qty) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="edit_order_unit" class="block text-sm font-medium text-gray-700">Unit</label>
                    <select id="edit_order_unit" name="order_unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Select Unit --</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" @selected(old('order_unit', $order->order_unit) == $unit->id)>{{ $unit->unit_name }} ({{$unit->unit_shorthand}})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_order_manufacturedqty" class="block text-sm font-medium text-gray-700">Manuf. Qty</label>
                    <input type="number" id="edit_order_manufacturedqty" name="order_manufacturedqty" value="{{ old('order_manufacturedqty', $order->order_manufacturedqty) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="edit_order_weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                    <input type="number" step="any" id="edit_order_weight" name="order_weight" value="{{ old('order_weight', $order->order_weight) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label for="edit_order_length" class="block text-sm font-medium text-gray-700">Length</label>
                    <input type="number" step="any" id="edit_order_length" name="order_length" value="{{ old('order_length', $order->order_length) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="edit_order_width" class="block text-sm font-medium text-gray-700">Width</label>
                    <input type="number" step="any" id="edit_order_width" name="order_width" value="{{ old('order_width', $order->order_width) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="edit_order_d_unit" class="block text-sm font-medium text-gray-700">Dimension Unit</label>
                    <select id="edit_order_d_unit" name="order_d_unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Select D.Unit --</option>
                        @foreach($d_units as $d_unit)
                        <option value="{{ $d_unit->id }}" @selected(old('order_d_unit', $order->order_d_unit) == $d_unit->id)>{{ $d_unit->dunit_name }} ({{$d_unit->dunit_shorthand}})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        {{-- Colors --}}
        <fieldset class="border p-4 rounded-lg">
            <legend class="text-lg font-medium text-blue-600 px-2">Colors & Materials</legend>
            <div id="edit-colors-container" class="space-y-2">
                @php $colors = old('colors', $order->colors ?? []); @endphp
                @if(is_array($colors) && !empty($colors))
                    @foreach($colors as $index => $colorInfo)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="colors[]" placeholder="Color Name" value="{{ $colorInfo['color'] ?? $colorInfo }}" class="block w-2/3 rounded-md border-gray-300 shadow-sm">
                            <input type="number" name="color_qtys[]" placeholder="Qty" value="{{ $colorInfo['qty'] ?? '' }}" class="block w-1/3 rounded-md border-gray-300 shadow-sm">
                            <button type="button" class="remove-color-row text-red-500 hover:text-red-700">&times;</button>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center space-x-2">
                        <input type="text" name="colors[]" placeholder="Color Name" class="block w-2/3 rounded-md border-gray-300 shadow-sm">
                        <input type="number" name="color_qtys[]" placeholder="Qty" class="block w-1/3 rounded-md border-gray-300 shadow-sm">
                        <button type="button" class="remove-color-row text-red-500 hover:text-red-700">&times;</button>
                    </div>
                @endif
            </div>
            <button type="button" id="edit-add-color-button" class="mt-2 text-sm text-blue-600 hover:text-blue-800">+ Add Color</button>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                 <div>
                    <label for="edit_order_nailmaterial" class="block text-sm font-medium text-gray-700">Nail Material</label>
                    <input type="text" id="edit_order_nailmaterial" name="order_nailmaterial" value="{{ old('order_nailmaterial', $order->order_nailmaterial) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="edit_dieset" name="dieset" value="1" @checked(old('dieset', $order->dieset)) class="rounded border-gray-300 text-blue-600 shadow-sm">
                        <span class="text-sm font-medium text-gray-700">Dieset Included</span>
                    </label>
                </div>
            </div>
        </fieldset>

        {{-- Pricing --}}
        <fieldset class="border p-4 rounded-lg">
             <legend class="text-lg font-medium text-blue-600 px-2">Pricing</legend>
             <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="edit_order_price" class="block text-sm font-medium text-gray-700">Buyer Price</label>
                    <input type="number" step="any" id="edit_order_price" name="order_price" value="{{ old('order_price', $order->order_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="edit_order_mouldprice" class="block text-sm font-medium text-gray-700">Buyer Mould</label>
                    <input type="number" step="any" id="edit_order_mouldprice" name="order_mouldprice" value="{{ old('order_mouldprice', $order->order_mouldprice) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="edit_supplier_price" class="block text-sm font-medium text-gray-700">Supplier Price</label>
                    <input type="number" step="any" id="edit_supplier_price" name="supplier_price" value="{{ old('supplier_price', $order->supplier_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="edit_supplier_mould_price" class="block text-sm font-medium text-gray-700">Supplier Mould</label>
                    <input type="number" step="any" id="edit_supplier_mould_price" name="supplier_mould_price" value="{{ old('supplier_mould_price', $order->supplier_mould_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
             </div>
        </fieldset>

        {{-- Files --}}
        <fieldset class="border p-4 rounded-lg">
            <legend class="text-lg font-medium text-blue-600 px-2">File Attachments</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="edit_order_image" class="block text-sm font-medium text-gray-700">Order Image</label>
                    @if($order->order_image)
                    <div class="mt-1 text-xs text-gray-500">Current: <a href="{{ asset('storage/' . $order->order_image) }}" target="_blank" class="text-blue-600">View Image</a></div>
                    @endif
                    <input type="file" id="edit_order_image" name="order_image" class="mt-1 block w-full text-sm">
                </div>
                <div>
                    <label for="edit_order_logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    @if($order->order_logo)
                    <div class="mt-1 text-xs text-gray-500">Current: <a href="{{ asset('storage/' . $order->order_logo) }}" target="_blank" class="text-blue-600">View Logo</a></div>
                    @endif
                    <input type="file" id="edit_order_logo" name="order_logo" class="mt-1 block w-full text-sm">
                </div>
                <div>
                    <label for="edit_order_po" class="block text-sm font-medium text-gray-700">Purchase Order (PO)</label>
                    @if($order->order_po)
                    <div class="mt-1 text-xs text-gray-500">Current: <a href="{{ asset('storage/' . $order->order_po) }}" target="_blank" class="text-blue-600">View PO</a></div>
                    @endif
                    <input type="file" id="edit_order_po" name="order_po" class="mt-1 block w-full text-sm">
                </div>
                <div>
                    <label for="edit_order_cdr" class="block text-sm font-medium text-gray-700">CDR File</label>
                    @if($order->order_cdr)
                    <div class="mt-1 text-xs text-gray-500">Current: <a href="{{ asset('storage/' . $order->order_cdr) }}" target="_blank" class="text-blue-600">Download CDR</a></div>
                    @endif
                    <input type="file" id="edit_order_cdr" name="order_cdr" class="mt-1 block w-full text-sm">
                </div>
            </div>
        </fieldset>

        {{-- Remarks --}}
        <fieldset class="border p-4 rounded-lg space-y-4">
            <legend class="text-lg font-medium text-blue-600 px-2">Remarks</legend>
            <div>
                <label for="edit_order_remark" class="block text-sm font-medium text-gray-700">Order Remark</label>
                <textarea id="edit_order_remark" name="order_remark" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('order_remark', $order->order_remark) }}</textarea>
            </div>
            <div>
                <label for="edit_buyer_remark" class="block text-sm font-medium text-gray-700">Buyer Remark</label>
                <textarea id="edit_buyer_remark" name="buyer_remark" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('buyer_remark', $order->buyer_remark) }}</textarea>
            </div>
            <div>
                <label for="edit_supplier_remark" class="block text-sm font-medium text-gray-700">Supplier Remark</label>
                <textarea id="edit_supplier_remark" name="supplier_remark" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('supplier_remark', $order->supplier_remark) }}</textarea>
            </div>
        </fieldset>

    </div>
    
    {{-- Form Actions (Modal Footer) --}}
    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t">
        <button type="button" id="close-edit-modal-button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            Cancel
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 shadow-sm">
            Save Changes
        </button>
    </div>
</form>

{{-- Script to handle dynamic color rows --}}
<script>
    document.getElementById('edit-add-color-button').addEventListener('click', function() {
        const container = document.getElementById('edit-colors-container');
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center space-x-2';
        newRow.innerHTML = `
            <input type="text" name="colors[]" placeholder="Color Name" class="block w-2/3 rounded-md border-gray-300 shadow-sm">
            <input type="number" name="color_qtys[]" placeholder="Qty" class="block w-1/3 rounded-md border-gray-300 shadow-sm">
            <button type="button" class="remove-color-row text-red-500 hover:text-red-700">&times;</button>
        `;
        container.appendChild(newRow);
    });

    // Use event delegation for remove buttons
    document.getElementById('edit-colors-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-color-row')) {
            e.target.parentElement.remove();
        }
    });
</script>