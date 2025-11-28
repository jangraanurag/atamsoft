@php
function iFormat($num) {
    $num = (string)$num;

    // Split integer and decimal parts
    $parts = explode('.', $num);
    $int = $parts[0];
    $dec = $parts[1] ?? null;

    // If integer part is 3 digits or less, no special formatting
    if (strlen($int) <= 3) {
        $result = $int;
    } else {
        $last3 = substr($int, -3);
        $rest = substr($int, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        $result = $rest . ',' . $last3;
    }

    // Add decimal part back if exists
    return $dec ? $result . '.' . $dec : $result;
}
@endphp
{{ iFormat($ordersForTable->count()) }}

@extends('layout')
@section('title', 'Manage All Orders')

@section('header')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.tailwindcss.css">
<style>
  .table-wrapper {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    position: relative;
    border: 1px solid #ccc;
}

.order-table {
    border-collapse: collapse;
    width: auto; /* Changed from 100% to auto */
    min-width: 100%;
    table-layout: auto; /* Allow natural column sizing */
}

.order-table th,
.order-table td {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background: #f8fbff;
    white-space: nowrap;
    font-size: 125%;
}

/* Sticky header */
.order-table thead th {
    position: sticky;
    top: 0;
    background: #0168fa !important;
    color: #fff !important;
    z-index: 10;
    font-size: 16px;
    font-weight: 700;
}

/* Sticky first column (Order No + Checkbox) */
.order-table th:nth-child(1),
.order-table td:nth-child(1) {
    position: sticky;
    left: 0;
    z-index: 8;
    background: #f8fbff;
    min-width: 60px;
    width: 60px;
}

/* Sticky second column (Image/CDR) */
.order-table th:nth-child(2),
.order-table td:nth-child(2) {
    position: sticky;
    left: 85px; /* Width of first column */
    z-index: 8;
    background: #f8fbff;
    min-width: 90px;
    width: 90px;
}


/* Ensure sticky columns have proper borders and shadows */
.order-table th:nth-child(1),
.order-table td:nth-child(1),
.order-table th:nth-child(2),
.order-table td:nth-child(2) {
    border-right: 2px solid #0168fa;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

/* Ensure header sticky columns have higher z-index */
.order-table thead th:nth-child(1),
.order-table thead th:nth-child(2) {
    z-index: 11;
}

/* Row styling */
#orders-table tbody tr:nth-child(odd) {
    background-color: #e2eeff !important;
}

#orders-table tbody tr:nth-child(odd) td {
    background-color: #e2eeff !important;
}

#orders-table tbody tr:nth-child(even) td {
    background-color: #f8fbff !important;
}

/* Ensure sticky columns maintain row background colors */
#orders-table tbody tr:nth-child(odd) td:nth-child(1),
#orders-table tbody tr:nth-child(odd) td:nth-child(2),
#orders-table tbody tr:nth-child(odd) td:last-child {
    background-color: #e2eeff !important;
}

#orders-table tbody tr:nth-child(even) td:nth-child(1),
#orders-table tbody tr:nth-child(even) td:nth-child(2),
#orders-table tbody tr:nth-child(even) td:last-child {
    background-color: #f8fbff !important;
}

/* DataTables specific styling */
#orders-table tbody td {
    border: 1px solid black;
    padding: 0;
    vertical-align: top;
}

#orders-table tbody td > div {
    padding: 10px 12px;
}

/* Specific column classes - only for sticky columns */
.col-checkbox {
    width: 80px !important;
    min-width: 60px !important;
    max-width: 80px !important;
}

.col-image {
    width: 90px !important;
    min-width: 90px !important;
    max-width: 90px !important;
}

/* Content columns - allow natural sizing */
.order-table td:not(.col-checkbox):not(.col-image) {
    white-space: normal;
    word-break: break-word;
    min-width: 120px; /* Minimum width for readability */
    width: auto; /* Allow natural growth */
}

/* Remove fixed widths from content columns */
.order-table th:not(.col-checkbox):not(.col-image),
.order-table td:not(.col-checkbox):not(.col-image) {
    max-width: none; /* Remove max-width constraints */
}

table, table th, table td {
    text-transform: uppercase !important;
}

/* Ensure DataTables doesn't override our widths */
#orders-table {
    width: auto !important;
}

/* Custom Checkbox Styling */
#select-all, .order-checkbox {
    transform: scale(1.5);
}
</style>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 rounded-md bg-green-100 text-green-700">
        {!! session('success') !!}
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 p-4 rounded-md bg-red-100 text-red-700">
        <strong>Whoops! Something went wrong.</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Summary Section has been moved to the left sidebar -->

<!-- Forms for actions -->
<form action="{{ route('reports.generateBuyerReport') }}" method="POST" id="report-form">@csrf <div id="report-ids"></div></form>
<form action="{{ route('order.bulkEditForm') }}" method="POST" id="bulk-edit-form">@csrf <div id="bulk-edit-ids-container"></div></form>
<form action="{{ route('order.bulkPo') }}" method="POST" id="bulk-po-form" target="_blank">@csrf <div id="bulk-po-ids-container"></div></form>
<form action="{{ route('order.bulkDuplicate') }}" method="POST" id="bulk-duplicate-form">@csrf <div id="bulk-duplicate-ids-container"></div></form>
<form action="{{ route('reports.generateSupplierReport') }}" method="POST" id="supplier-report-form">@csrf <div id="supplier-report-ids"></div></form>
<form action="{{ route('order.bulkDelete') }}" method="POST" id="delete-form">@csrf <div id="delete-ids"></div></form>

<!-- Main Layout -->
<div class="flex flex-col md:flex-row gap-4 items-start">
    
    <!-- Left Sidebar: Summary & Actions (Sticky) -->
    <div class="w-full md:w-60 flex-shrink-0 md:sticky md:top-24 flex flex-col gap-2">
        <!-- Summary -->
        <div class="bg-white border border-gray-200 p-2 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Pending Orders</h3>
            <p class="text-lg font-bold text-gray-900">{{ iformat($pendingOrders->count()) }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-2 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Pending Qty</h3>
            <p class="text-lg font-bold text-gray-900">{{ iformat($pendingOrders->sum('order_qty')) }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-2 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Pending Buyer Amount</h3>
            <p class="text-lg font-bold text-blue-600">{{ iformat($pendingOrders->sum('buyer_total'), 2) }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-2 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Pending Supp. Amount</h3>
            <p class="text-lg font-bold text-green-600">{{ iformat($pendingOrders->sum('supplier_total'), 2) }}</p>
        </div>

        <!-- Actions -->
        <div class="mt-2 flex flex-col gap-2">
             <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Actions</h3>
        
            <a href="{{ route('order.new') }}" class="w-full bg-indigo-600 text-white px-3 py-2 rounded text-sm text-center hover:bg-indigo-700 shadow flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Create Order
            </a>
            
            <button type="submit" form="bulk-edit-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-pencil"></i> Edit Selected
            </button>
            
            <button type="submit" form="bulk-po-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-print"></i> Bulk PO
            </button>

            <button type="submit" form="bulk-duplicate-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-copy"></i> Bulk Duplicate
            </button>
            
            <button type="submit" form="report-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-file-invoice"></i> Buyer Report
            </button>

            <button type="submit" form="supplier-report-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-file-invoice"></i> Supplier Report
            </button>
            
            <button id="delete-selected-button" class="w-full bg-red-50 text-red-600 border border-red-200 px-3 py-2 rounded text-sm text-center hover:bg-red-100 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-trash"></i> Delete Selected
            </button>
        </div>
    </div>

    <!-- Center: Table -->
    <div class="flex-grow min-w-0 w-full bg-white shadow rounded-lg overflow-hidden table-wrapper">
        {{-- DataTables search box will be rendered here by default --}}
    
        <table id="orders-table" class="min-w-full text-sm order-table" style="width:100%">
            <thead>
                <tr>
                    <th class="p-2 sticky-col col-1"><input type="checkbox" id="select-all" class="mx-auto"> Ord No</th>
                    <th class="p-2">Image/CDR</th>
                    <th class="p-2">Brand</th>
                    <th class="p-2">Product</th>
                    <th class="p-2">Qty/Unit</th>
                    <th class="p-2">Supplier</th>
                    <th class="p-2">Supp. Amount</th>
                    <th class="p-2">Buyer</th>
                    <th class="p-2">Buyer Amount</th>
                    <th class="p-2">Dims/Weight</th>
                    <th class="p-2">Dates</th>
                    
                    <th class="p-2">Shipping</th>
                    <!--<th class="p-2">Remarks</th>
                    <th class="p-2">Color Ref.</th>-->
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Right Sidebar: Actions (Sticky) -->
    <div class="w-full md:w-40 flex-shrink-0 md:sticky md:top-24 flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-visible pb-2 md:pb-0" style="display:none;">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 hidden md:block">Actions</h3>
        
        <a href="{{ route('order.new') }}" class="w-full bg-indigo-600 text-white px-3 py-2 rounded text-sm text-center hover:bg-indigo-700 shadow flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Create Order
        </a>
        
        <button type="submit" form="bulk-edit-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-pencil"></i> Edit Selected
        </button>
        
        <button type="submit" form="bulk-po-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-print"></i> Bulk PO
        </button>

        <button type="submit" form="bulk-duplicate-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-copy"></i> Bulk Duplicate
        </button>
        
        <button type="submit" form="report-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-file-invoice"></i> Buyer Report
        </button>

        <button type="submit" form="supplier-report-form" class="w-full bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded text-sm text-center hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-file-invoice"></i> Supplier Report
        </button>
        
        <button id="delete-selected-button" class="w-full bg-red-50 text-red-600 border border-red-200 px-3 py-2 rounded text-sm text-center hover:bg-red-100 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-trash"></i> Delete Selected
        </button>
    </div>
</div>

<div id="edit-order-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
        <div id="edit-order-modal-content">
            {{-- AJAX content will be loaded here --}}
        </div>
    </div>
</div>

<div id="image-preview" style="display: none; position: absolute; border: 1px solid #ccc; background: #fff; padding: 5px; z-index: 999; box-shadow: 0 0 10px rgba(0,0,0,0.5); pointer-events: none;">
    <img src="" style="max-width: 300px; max-height: 300px;">
</div>

@endsection

@section('footer')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.tailwindcss.js"></script>

<script>
// Pass PHP variables to JavaScript
const allStatuses = @json($statuses);
const allSuppliers = @json($suppliers);
const currentStatusFilter = "{{ $filterStatus ?? '' }}";

$(document).ready(function() {
    var selectedIds = []; // Array to store selected order IDs

    // Set the filter dropdown to the value from the URL
    if(currentStatusFilter) {
        let status = allStatuses.find(s => s.status_name.toLowerCase() === currentStatusFilter.toLowerCase());
        if(status) {
            $('#filter-status').val(status.id);
        }
    }

    // Function to update hidden inputs in all forms
    function updateForms() {
        const forms = $('#report-ids, #bulk-edit-ids-container, #bulk-po-ids-container, #bulk-duplicate-ids-container, #supplier-report-ids, #delete-ids');
        forms.empty();
        selectedIds.forEach(id => {
            forms.append(`<input type="hidden" name="order_ids[]" value="${id}">`);
        });
    }
    
    function updateSelectAllCheckbox() {
        var totalRows = table.rows({ search: 'applied' }).count();
        var selectedCount = selectedIds.length;

        if (selectedCount === 0) {
            $('#select-all').prop('checked', false).prop('indeterminate', false);
        } else if (selectedCount === totalRows) {
            $('#select-all').prop('checked', true).prop('indeterminate', false);
        } else {
            $('#select-all').prop('checked', false).prop('indeterminate', true);
        }
    }

    // Initialize DataTable
    var table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY:'70vh',
        autoWidth: true,
        dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
        ajax: {
            url: "{{ route('order.data') }}", 
            type: "GET",
            data: function(d) {
                d.status = "{{ request('status') }}";
            }
        },
        "order": [[ 0, "desc" ]],
        columnDefs: [
            { targets: 0, width: "80px", className: 'col-checkbox' },
            { targets: 1, width: "120px", className: 'col-image' },
            { targets: 2, width: "80px" }, // Brand
            { targets: 4, width: "80px" }, // Qty/Unit
            { targets: 6, width: "100px" }, // Supp. Amount
            { targets: 11, width: "100px" }, // Shipping
        ],
        columns: [
            { 
                data: "id", 
                orderable: true, 
                searchable: true,
                render: function(data, type, row) {
                    var editUrl = `{{ url('order') }}/${data}/edit`;
                    return `<div class="text-center">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" class="order-checkbox" value="${data}">
                                    <p class="text-sm ml-2">${data}</p>
                                </div>
                                <button data-url="${editUrl}" class="edit-order-button text-indigo-600 hover:underline text-xs" title="Edit">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                            </div>`;
                }
            },
            { 
                data: "order_image", 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    let imgSrc = data ? `{{ asset('storage/app/public') }}/${data}` : 'https://placehold.co/90x90/e2e8f0/adb5bd?text=No+Img';
                    let cdrLink = row.order_cdr ? `<a href="{{ asset('storage/app/public') }}/${row.order_cdr}" class="text-blue-600 block text-xs hover:underline" target="_blank">CDR File</a>` : '';
                    return `
                        <img src="${imgSrc}" alt="Img" style="width:90px;" class="object-cover rounded border order-image-thumbnail">
                        ${cdrLink}
                    `;
                }
            },
            { data: "brand_name", className: 'text-sm', render: data => `<div>${data || ''}</div>` },
            { data: "product_name", render: data => `<div>${data || ''}</div>` },
            { data: "order_qty", render: (data, type, row) => `<div>${data || ''} ${row.unit_shorthand || ''}</div>` },
            { data: "supplier_name", render: data => `<div>${data || ''}</div>` },
            { 
                data: "supplier_total", 
                render: (data, type, row) => `
                    <div>
                        <div>${row.supplier_price || 0} | ${row.supplier_mould_price || 0}</div>
                        <div class="text-sm">${parseFloat(data || 0).toFixed(2)}</div>
                    </div>`
            },
            { data: "buyer_name", render: data => `<div>${data || ''}</div>` },
            { 
                data: "buyer_total", 
                render: (data, type, row) => `
                    <div>
                        <div>${row.order_price || 0} | ${row.order_mouldprice || 0}</div>
                        <div class="text-sm">${parseFloat(data || 0).toFixed(2)}</div>
                    </div>`
            },
            { 
                data: "order_length", 
                render: (data, type, row) => `
                    <div>
                        <div>${row.order_length || ''} ${row.dunit_shorthand || ''} * ${row.order_width || ''} ${row.dunit_shorthand || ''}</div>
                        <div>${row.order_weight || ''} grams</div>
                    </div>` 
            },
            { 
                data: "order_date", 
                render: (data, type, row) => `
                    <div>
                        <div>O: <span>${row.order_date || ''}</span></div>
                        <div>D: <span >${row.order_deliverydate || ''}</span></div>
                    </div>`
            },
            { 
                data: "shipper_name", 
                render: (data, type, row) => `
                    <div>
                        <div>${data || ''}</div>
                        <div>${row.order_shipmentno || ''}</div>
                    </div>`
            }
        ],
        lengthMenu: [[20, 50, 100, 300, -1], [20, 50, 100, 300, "All"]],
        pageLength: 20,
        drawCallback: function() {
            // On each draw, check boxes that are in our selectedIds array
            $('#orders-table tbody .order-checkbox').each(function() {
                $(this).prop('checked', selectedIds.includes($(this).val()));
            });
            updateSelectAllCheckbox();
        }
    });

    // --- Checkbox Handlers ---
    $('#select-all').on('click', function() {
        var isChecked = this.checked;
        var allRowIds = table.rows({ search: 'applied' }).data().pluck('id').toArray();

        if (isChecked) {
            // Add all matching rows to the selection
            selectedIds = [...new Set([...selectedIds, ...allRowIds])];
        } else {
            // Remove all matching rows from the selection
            selectedIds = selectedIds.filter(id => !allRowIds.includes(id));
        }
        
        updateForms();
        table.draw(false); // Redraw to update checkbox states
    });

    $('#orders-table tbody').on('click', '.order-checkbox', function() {
        var id = $(this).val();
        if (this.checked) {
            if (!selectedIds.includes(id)) {
                selectedIds.push(id);
            }
        } else {
            var index = selectedIds.indexOf(id);
            if (index > -1) {
                selectedIds.splice(index, 1);
            }
        }
        updateForms();
        updateSelectAllCheckbox();
    });

    // --- Filter Button Logic ---
    $('#filter-search-button').on('click', function() {
        // When filtering, we should clear selections as the context changes
        selectedIds = [];
        updateForms();
        table.ajax.reload(null, true); // `true` resets paging to first page
    });

    // --- Form submission checks ---
    $('#bulk-edit-form, #bulk-po-form, #bulk-duplicate-form, #report-form, #supplier-report-form').on('submit', function(e) {
        if (selectedIds.length === 0) {
            alert('Please select at least one order.');
            e.preventDefault();
        }
    });

    $('#delete-selected-button').on('click', function() {
        if (selectedIds.length === 0) {
            alert('Please select at least one order to delete.');
            return;
        }
        if (confirm('Are you sure you want to delete the selected orders?')) {
            $('#delete-form').submit();
        }
    });
    
    // --- "Read More" for Remarks ---
    $('#orders-table tbody').on('click', '.remark-toggle', function(e) {
        e.preventDefault();
        var short = $(this).siblings('.remark-short');
        var full = $(this).siblings('.remark-full');
        
        if (full.is(':visible')) {
            full.hide();
            short.show();
            $(this).text('...more');
        } else {
            short.hide();
            full.show();
            $(this).text('...less');
        }
    });

    // --- Edit Modal Logic ---
    const modal = $('#edit-order-modal');
    const modalContent = $('#edit-order-modal-content');

    // Open modal
    $('#orders-table tbody').on('click', '.edit-order-button', function() {
        const url = $(this).data('url');
        modalContent.html('<div class="p-8 text-center">Loading...</div>');
        modal.removeClass('hidden');

        $.get(url, function(data) {
            modalContent.html(data);
        }).fail(function() {
            modalContent.html('<div class="p-8 text-center text-red-500">Failed to load content.</div>');
        });
    });

    // Close modal (delegated)
    modal.on('click', '#close-edit-modal-button, #close-edit-modal-button-x', function() {
        modal.addClass('hidden');
        modalContent.empty();
    });

    // Close modal on background click
    modal.on('click', function(e) {
        if ($(e.target).is(modal)) {
            modal.addClass('hidden');
            modalContent.empty();
        }
    });

    // Handle form submission via AJAX
    modal.on('submit', 'form', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const formData = new FormData(this);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                modal.addClass('hidden');
                modalContent.empty();
                table.ajax.reload(null, false); // false = don't reset pagination
                // You can add a success notification here if you like
            },
            error: function(xhr) {
                // Basic error handling: show errors in the modal
                // A more robust solution would parse the validation errors
                alert('Error updating order. Please check the form for errors.');
                console.log(xhr.responseText);
            }
        });
    });

    // --- Image Preview Logic ---
    var preview = $('#image-preview');

    $('#orders-table tbody').on('mouseenter', '.order-image-thumbnail', function(e) {
        let src = $(this).attr('src');
        // Don't show preview for placeholder
        if (src.includes('placehold.co')) {
            return;
        }
        preview.find('img').attr('src', src);
        preview.show();
    });

    $('#orders-table tbody').on('mouseleave', '.order-image-thumbnail', function(e) {
        preview.hide();
    });

    $('#orders-table tbody').on('mousemove', '.order-image-thumbnail', function(e) {
        let mouseX = e.pageX;
        let mouseY = e.pageY;
        
        let previewWidth = preview.outerWidth();
        let previewHeight = preview.outerHeight();
        
        let windowWidth = $(window).width();
        let windowScrollLeft = $(window).scrollLeft();
        
        let windowHeight = $(window).height();
        let windowScrollTop = $(window).scrollTop();
        
        let top = mouseY + 10;
        let left = mouseX + 10;
        
        // If it goes off the right edge, move it to the left of the cursor
        if (left + previewWidth > windowWidth + windowScrollLeft) {
            left = mouseX - previewWidth - 10;
        }
        
        // If it goes off the bottom edge, move it above the cursor
        if (top + previewHeight > windowHeight + windowScrollTop) {
            top = mouseY - previewHeight - 10;
        }
        
        preview.css({
            top: top + 'px',
            left: left + 'px'
        });
    });
});
</script>
@endsection