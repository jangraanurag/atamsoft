@extends('layout')
@section('title', 'Manage All Orders')

@section('header')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">
<style>
    /* Add a visual cue for the active filter button */
    .filter-button.active {
        background-color: #4338ca; /* indigo-700 */
        color: white;
    }
    /* Style for the active button to show it's disabled */
    .filter-button.active:disabled {
        opacity: 0.75;
        cursor: not-allowed;
    }
    /* Style for calculated total fields */
    .total-field {
        background-color: #f3f4f6; /* gray-100 */
        font-weight: 500;
    }
    /* Simple transition for accordion chevron */
    .chevron-icon {
        transition: transform 0.2s ease;
    }
    /* Fix for DataTables scrollbar layout */
    div.dataTables_wrapper div.dataTables_scrollBody {
        overflow-x: auto;
    }
    
</style>
@endsection

@section('content')
<h1 class="text-2xl font-bold mb-4">Order Management (All Orders)</h1>

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
{{-- ============== ACCORDION SUMMARY SECTION ================ --}}
{{-- ========================================================= --}}
<div class="mb-6 space-y-2">
    <!-- Buyer Summary Accordion -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <button class="accordion-toggle flex justify-between items-center w-full p-6" aria-expanded="false">
            <h2 class="text-xl font-semibold text-gray-700">Buyer-wise Summary</h2>
            <i class="fa-solid fa-chevron-down chevron-icon"></i>
        </button>
        <div class="accordion-content hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Buyers</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Numbers of Orders</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Total Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Edit Orders</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($buyerReports as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report['buyer_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $report['number_of_orders'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">₹{{ number_format($report['total_amount'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400">
                                <p>No buyer data found for this filter.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Supplier Summary Accordion -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <button class="accordion-toggle flex justify-between items-center w-full p-6" aria-expanded="false">
            <h2 class="text-xl font-semibold text-gray-700">Supplier-wise Summary</h2>
            <i class="fa-solid fa-chevron-down chevron-icon"></i>
        </button>
        <div class="accordion-content hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Suppliers</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Numbers of Orders</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Total Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Edit Orders</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($supplierReports as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report['supplier_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $report['number_of_orders'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">₹{{ number_format($report['total_amount'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400">
                                <p>No supplier data found for this filter.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- ============== 4-BOX SUMMARY SECTION ================ --}}
{{-- ========================================================= --}}
@php
    // These 'orders' are passed from the controller for the summary boxes
    $totalOrderedQty = $orders->sum('order_qty');
    $totalManufacturedQty = $orders->sum('order_manufacturedqty');
    $totalSale = $orders->sum('buyer_total');
    $totalPurchase = $orders->sum('supplier_total');
@endphp

<div class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Overall Summary (Filtered)</h2>
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
            <p class="text-2xl font-bold text-yellow-900">₹{{ number_format($totalSale, 2) }}</p>
        </div>
        <div class="bg-red-100 border border-red-200 p-4 rounded-lg shadow">
            <h3 class="text-sm font-semibold text-red-800">Total Purchase (Supplier)</h3>
            <p class="text-2xl font-bold text-red-900">₹{{ number_format($totalPurchase, 2) }}</p>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- =============== IN-LINE EDITING FORM ================ --}}
{{-- ========================================================= --}}
<div class="bg-white shadow rounded p-4">
    
    {{-- This form is for the "Generate Buyer Report" button --}}
    <form action="{{ route('reports.generateBuyerReport') }}" method="POST" id="report-form">
        @csrf
        <div id="selected-ids-container"></div>
    </form>

    <div class="border-b pb-4 mb-4">
        <h2 class="text-lg font-semibold mb-4">In-line Order Editing</h2>
        
        <!-- Filter Buttons (Top Row) -->
        <div class="flex space-x-1 rounded-lg bg-gray-200 p-1 mb-4 w-full md:w-auto">
            <button data-status="all" class="filter-button px-3 py-1 text-sm font-medium rounded-md text-gray-700 hover:bg-white flex-1 md:flex-none">All</button>
            <button data-status="pending" class="filter-button px-3 py-1 text-sm font-medium rounded-md text-gray-700 hover:bg-white flex-1 md:flex-none">Pending</button>
            <button data-status="ready" class="filter-button px-3 py-1 text-sm font-medium rounded-md text-gray-700 hover:bg-white flex-1 md:flex-none">Ready</button>
            <button data-status="shipped" class="filter-button px-3 py-1 text-sm font-medium rounded-md text-gray-700 hover:bg-white flex-1 md:flex-none">Shipped</button>
            <button data-status="delivered" class="filter-button px-3 py-1 text-sm font-medium rounded-md text-gray-700 hover:bg-white flex-1 md:flex-none">Delivered</button>
            <button data-status="cancelled" class="filter-button px-3 py-1 text-sm font-medium rounded-md text-gray-700 hover:bg-white flex-1 md:flex-none">Cancelled</button>
        </div>
        
        <!-- Action Buttons & Search (Bottom Row) -->
        <div class="flex flex-col md:flex-row justify-between items-center">
            <!-- Action Buttons (Left) -->
            <div class="flex space-x-2 mb-4 md:mb-0">
                <button type="submit" form="update-form" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fa-solid fa-save mr-1"></i> Update Selected
                </button>
                <button type="submit" form="report-form"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-file-invoice mr-2"></i> Generate Buyer Report
                </button>
            </div>
            
           
            
        </div>
    </div>
        
    {{-- This form is for the "Update All Orders" button --}}
    <form action="{{ route('order.updateStatus') }}" method="POST" id="update-form">
        @csrf
        <table id="orders-table" class="min-w-full text-sm text-left border border-gray-200" style="width:100%">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border w-4"><input type="checkbox" id="select-all"></th>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Image</th> 
                    <th class="p-2 border">Order No</th>
                    <th class="p-2 border">Product</th>
                    <th class="p-2 border">Buyer</th>
                    <th class="p-2 border">Supplier</th>
                    <th class="p-2 border">Order Qty</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Manuf. Qty</th>
                    <th class="p-2 border">Order Date</th>
                    <th class="p-2 border">Delivery Date</th>
                    <th class="p-2 border">Weight</th>
                    <th class="p-2 border">Buyer Price</th>
                    <th class="p-2 border">Buyer Mould</th>
                    <th class="p-2 border">Supplier Price</th>
                    <th class="p-2 border">Supplier Mould</th>
                    <th class="p-2 border">Buyer Total</th>
                    <th class="p-2 border">Supplier Total</th>
                    <th class="p-2 border">Remark</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            
            {{-- Body is empty - DataTables will fill this --}}
            <tbody></tbody>
        </table>
    </form>
</div>


{{-- ========================================================= --}}
{{-- =============== *** EDIT MODAL *** ================ --}}
{{-- ========================================================= --}}
<div id="edit-order-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
    <div class="relative top-10 mx-auto max-w-4xl w-full">
        <div class="bg-white rounded-lg shadow-xl">
            {{-- Content will be loaded here by AJAX --}}
            <div id="edit-modal-content">
                <div class="p-6 text-center">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                    <p class="mt-2 text-gray-600">Loading Order Details...</p>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ========================================================= --}}
{{-- ============= END OF MODAL ============= --}}
{{-- ========================================================= --}}

@endsection

@section('footer')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>

<script>
// Pass PHP variables to JavaScript
const allStatuses = @json($statuses);
const allSuppliers = @json($suppliers);
const currentStatusFilter = "{{ $filterStatus ?? 'all' }}"; // Get status from URL

$(document).ready(function() {
    var selectedOrderIds = [];

    // Initialize DataTable
    var table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        
        scrollX: false, 
        
        // This removes the "double header" and controls
       dom: '<"flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4"lf>rt<"flex flex-col sm:flex-row justify-between items-start sm:items-center mt-4"ip>',
        
        ajax: {
            url: "{{ route('order.data') }}", 
            type: "GET",
            data: function(d) {
                // Add the status filter to the AJAX request
                d.status = $('.filter-button.active').data('status') || currentStatusFilter;
            }
        },
        "order": [[ 3, "desc" ]], 
        columns: [
            // 0. Checkbox
            { data: "id", orderable: false, searchable: false,
              render: function(data, type, row) {
                  return `<input type="checkbox" name="order_ids[]" value="${data}" class="order-checkbox">`;
              }
            },
            // 1. Row number
            { data: null, orderable: false, searchable: false, 
              render: function (data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            // 2. Image
            { data: "order_image", orderable: false, searchable: false,
              render: function(data, type, row) {
                  if (!data) return '';
                  let imgSrc = `{{ asset('storage') }}/${data}`; 
                  return `<img src="${imgSrc}" alt="Img" class="w-12 h-12 object-cover rounded border">`;
              }
            },
            // 3. Order No
            { data: "id" },
            // 4. Product
            { data: "product_name" },
            // 5. Buyer
            { data: "buyer_name" },
            // 6. Supplier (Dropdown)
            { data: "supplier_name",
              render: function(data, type, row) {
                  let options = '<option value="">-- No Supplier --</option>';
                  allSuppliers.forEach(function(supplier) {
                      let selected = (supplier.id == row.supplier_id) ? 'selected' : '';
                      options += `<option value="${supplier.id}" ${selected}>${supplier.name}</option>`;
                  });
                  return `
                      <select name="updates[${row.id}][supplier_id]" class="w-full border-gray-300 rounded text-xs p-1" form="update-form">
                          ${options}
                      </select>
                  `;
              }
            },
            // 7. Order Qty
            { data: "order_qty" },
            // 8. Status (Dropdown)
            { data: "status_name",
              render: function(data, type, row) {
                  let options = '';
                  allStatuses.forEach(function(status) {
                      let selected = (status.id == row.order_status) ? 'selected' : '';
                      options += `<option value="${status.id}" ${selected}>${status.status_name}</option>`;
                  });
                  return `
                      <select name="updates[${row.id}][order_status]" class="w-full border-gray-300 rounded text-xs p-1" form="update-form">
                          ${options}
                      </select>
                  `;
              }
            },
            // 9. Manuf. Qty (Input)
            { data: "order_manufacturedqty",
              render: function(data, type, row) {
                  return `<input type="number" name="updates[${row.id}][order_manufacturedqty]" value="${data || ''}" class="w-full border-gray-300 rounded text-xs p-1" form="update-form">`;
              }
            },
            // 10. Order Date
            { data: "order_date",
              render: function(data, type, row) {
                  let dateVal = data ? data.split(' ')[0] : ''; // Get Y-m-d part
                  return `<input type="date" name="updates[${row.id}][order_date]" value="${dateVal}" class="w-full border-gray-300 rounded text-xs p-1" form="update-form">`;
              }
            },
            // 11. Delivery Date (Input)
            { data: "order_deliverydate",
              render: function(data, type, row) {
                  let dateVal = data ? data.split(' ')[0] : ''; // Get Y-m-d part
                  return `<input type="date" name="updates[${row.id}][order_deliverydate]" value="${dateVal}" class="w-full border-gray-300 rounded text-xs p-1" form="update-form">`;
              }
            },
            // 12. Weight (Input)
            { data: "order_weight",
              render: function(data, type, row) {
                  return `<input type="number" step="any" name="updates[${row.id}][order_weight]" value="${data || ''}" class="w-20 border-gray-300 rounded text-xs p-1" form="update-form">`;
              }
            },
            
            // 13. Buyer Price (Input)
            { data: "order_price",
              render: function(data, type, row) {
                  return `<input type="number" step="any" name="updates[${row.id}][order_price]" value="${data || ''}" class="w-20 border-gray-300 rounded text-xs p-1 calc-buyer" data-id="${row.id}" form="update-form">`;
              }
            },
            // 14. Buyer Mould (Input)
            { data: "order_mouldprice",
              render: function(data, type, row) {
                  return `<input type="number" step="any" name="updates[${row.id}][order_mouldprice]" value="${data || ''}" class="w-20 border-gray-300 rounded text-xs p-1 calc-buyer" data-id="${row.id}" form="update-form">`;
              }
            },
            // 15. Supplier Price (Input)
            { data: "supplier_price",
              render: function(data, type, row) {
                  return `<input type="number" step="any" name="updates[${row.id}][supplier_price]" value="${data || ''}" class="w-20 border-gray-300 rounded text-xs p-1 calc-supplier" data-id="${row.id}" form="update-form">`;
              }
            },
            // 16. Supplier Mould (Input)
            { data: "supplier_mould_price",
              render: function(data, type, row) {
                  return `<input type="number" step="any" name="updates[${row.id}][supplier_mould_price]" value="${data || ''}" class="w-20 border-gray-300 rounded text-xs p-1 calc-supplier" data-id="${row.id}" form="update-form">`;
              }
            },
            
            // 17. Buyer Total (Calculated)
            { data: "buyer_total",
              render: function(data, type, row) {
                  return `<input id="buyer_total_${row.id}" type="number" step="any" name="updates[${row.id}][buyer_total]" value="${data || 0}" class="w-20 border-gray-300 rounded text-xs p-1 total-field" form="update-form" readonly>`;
              }
            },
            
            // 18. Supplier Total (Calculated)
            { data: "supplier_total",
              render: function(data, type, row) {
                  return `<input id="supplier_total_${row.id}" type="number" step="any" name="updates[${row.id}][supplier_total]" value="${data || 0}" class="w-20 border-gray-300 rounded text-xs p-1 total-field" form="update-form" readonly>`;
              }
            },

            // 19. Remark (Input)
            { data: "order_remark",
              render: function(data, type, row) {
                  return `<textarea name="updates[${row.id}][order_remark]" class="w-full border-gray-300 rounded text-xs p-1" rows="1" form="update-form">${data || ''}</textarea>`;
              }
            },
            // 20. Actions
            { data: "id", orderable: false, searchable: false, render: function(data, type, row) {
                var viewUrl = `{{ url('order') }}/${data}`;
                var editUrl = `{{ url('order') }}/${data}/edit`; // <-- Edit URL
                var deleteUrl = `{{ url('order') }}/${data}`;
                var duplicateUrl = `{{ url('order') }}/${data}/duplicate`;
                return `
                    <div class="flex space-x-2">
                        <a href="${viewUrl}" class="text-blue-600 hover:underline" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        
                        <button type="button" class="text-indigo-600 hover:underline edit-button" data-url="${editUrl}" title="Edit">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        
                        <form action="${duplicateUrl}" method="POST" onsubmit="return confirm('Are you sure you want to duplicate this order?');">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline" title="Duplicate">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </form>
                        
                        <a href="#" onclick="event.preventDefault(); if(confirm('Delete this order?')) { document.getElementById('delete-form-${data}').submit(); }" class="text-red-600 hover:underline" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                        <form id="delete-form-${data}" action="${deleteUrl}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                `;
            }}
        ],
        lengthMenu: [[20, 50, 100, 300, -1], [20, 50, 100, 300, "All"]],
        pageLength: 20,
        drawCallback: function() {
            // Re-check checkboxes after table is drawn
            $('#select-all').prop('checked', false);
            updateReportForm(); // Clear the form on redraw
        }
    });

    // *** FIX: Move the DataTables search bar to your container ***
    // This finds the DataTables search box and moves it into your div
    

    // --- Filter Button Logic ---
    $('.filter-button').on('click', function() {
        // 1. Set 'active' class and disable the button
        $('.filter-button').removeClass('active').prop('disabled', false);
        $(this).addClass('active').prop('disabled', true);
        
        // 2. Reload the DataTable
        table.ajax.reload();
        
        // 3. Update summary boxes (by reloading the page with the filter)
        var newStatus = $(this).data('status');
        var newUrl = new URL(window.location.href);
        newUrl.searchParams.set('status', newStatus);
        
        // Reload the page to update the summary boxes
        window.location.href = newUrl.href;
    });

    // --- Set the active button on page load ---
    if (currentStatusFilter) {
        $(`.filter-button[data-status="${currentStatusFilter}"]`).addClass('active').prop('disabled', true);
    } else {
        $(`.filter-button[data-status="all"]`).addClass('active').prop('disabled', true);
    }


    // --- Checkbox Handlers ---
    $('#select-all').on('click', function() {
        var checked = this.checked;
        $('.order-checkbox').prop('checked', checked);
        updateReportForm();
    });

    $('#orders-table tbody').on('click', '.order-checkbox', function() {
        updateReportForm();
    });

    function updateReportForm() {
        var container = $('#selected-ids-container').empty();
        $('.order-checkbox:checked').each(function() {
            container.append(`<input type="hidden" name="order_ids[]" value="${$(this).val()}">`);
        });
    }

    // This handles the "Update All Orders" form submission
    $('#update-form').on('submit', function(e) {
        // Find all selected checkboxes
        var selectedCheckboxes = $('.order-checkbox:checked');
        
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one order to update.');
            e.preventDefault(); // Stop the form from submitting
            return;
        }

        // Clear the form of any old hidden inputs from previous clicks
        $(this).find('input[type="hidden"][name^="updates"]').remove();
        
        // Disable all inputs first
        $(this).find('select[name^="updates"]').prop('disabled', true);
        $(this).find('input[name^="updates"]').prop('disabled', true);
        $(this).find('textarea[name^="updates"]').prop('disabled', true);

        // For each selected checkbox, find its corresponding inputs and re-enable them
        selectedCheckboxes.each(function() {
            var row = $(this).closest('tr');
            
            // Find all inputs/selects in that row and enable them
            row.find('select[name^="updates"]').prop('disabled', false);
            row.find('input[name^="updates"]').prop('disabled', false);
            row.find('textarea[name^="updates"]').prop('disabled', false);
            
            // Also add the checkbox value itself, so the controller knows which order to update
            $(this).clone().hide().appendTo('#update-form');
        });

        // The form will now submit with only the data from the selected rows.
    });
    
    // --- Modal Open/Close Logic ---
    $('#orders-table tbody').on('click', '.edit-button', function() {
        var url = $(this).data('url');
        
        // Show the modal with a "loading" spinner
        $('#edit-order-modal').show();
        $('#edit-modal-content').html(`
            <div class="p-6 text-center">
                <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                <p class="mt-2 text-gray-600">Loading Order Details...</p>
            </div>
        `);
        
        // Load the form from the controller
        $('#edit-modal-content').load(url, function(response, status, xhr) {
            if (status == "error") {
                $('#edit-modal-content').html(`
                    <div class="p-6 text-center">
                        <i class="fas fa-times-circle text-4xl text-red-600"></i>
                        <p class="mt-2 text-red-600">Error loading data: ${xhr.statusText}</p>
                    </div>
                `);
            }
        });
    });

    // Close the modal when "Cancel" is clicked (uses event delegation)
    $(document).on('click', '#close-edit-modal-button, #close-edit-modal-button-x', function() {
        $('#edit-order-modal').hide();
    });
    
    // Close modal if clicking on the background overlay
    $('#edit-order-modal').on('click', function(e) {
        if ($(e.target).is(this)) {
            $(this).hide();
        }
    });

    // --- Inline Calculation Logic ---

    // Function to calculate totals for a specific row
    function calculateTotals(rowId) {
        // Find all inputs for this row
        const row = $(`#orders-table tr:has(input[data-id="${rowId}"])`);
        
        // Get Buyer values
        const b_price = parseFloat(row.find(`input[name="updates[${rowId}][order_price]"]`).val()) || 0;
        const b_mould = parseFloat(row.find(`input[name="updates[${rowId}][order_mouldprice]"]`).val()) || 0;
        
        // Get Supplier values
        const s_price = parseFloat(row.find(`input[name="updates[${rowId}][supplier_price]"]`).val()) || 0;
        const s_mould = parseFloat(row.find(`input[name="updates[${rowId}][supplier_mould_price]"]`).val()) || 0;

        // Calculate (using the logic from your controller)
        const buyerTotal = b_price + b_mould;
        const supplierTotal = s_price + s_mould;
        
        // Update the 'value' of the read-only total inputs
        $(`#buyer_total_${rowId}`).val(buyerTotal.toFixed(2));
        $(`#supplier_total_${rowId}`).val(supplierTotal.toFixed(2));
    }

    // Add event listener for Buyer fields
    $('#orders-table tbody').on('input', '.calc-buyer', function() {
        const rowId = $(this).data('id');
        calculateTotals(rowId);
    });

    // Add event listener for Supplier fields
    $('#orders-table tbody').on('input', '.calc-supplier', function() {
        const rowId = $(this).data('id');
        calculateTotals(rowId);
    });

    // *** ADD THIS SCRIPT FOR THE ACCORDION ***
    $('.accordion-toggle').on('click', function() {
        // Find the content panel associated with this button
        var content = $(this).next('.accordion-content');
        
        // Find the icon
        var icon = $(this).find('.chevron-icon');

        // Toggle the 'hidden' class on the content
        content.slideToggle(200); // Use slideToggle for a smooth animation

        // Toggle the icon rotation
        icon.toggleClass('rotate-180');
        
        // Update the aria-expanded attribute for accessibility
        var isExpanded = $(this).attr('aria-expanded') === 'true';
        $(this).attr('aria-expanded', !isExpanded);
    });
});
</script>
@endsection