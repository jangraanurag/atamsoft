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
    left: 60px; /* Width of first column */
    z-index: 8;
    background: #f8fbff;
    min-width: 90px;
    width: 90px;
}

/* Sticky last column (Actions) */
.order-table th:last-child,
.order-table td:last-child {
    position: sticky;
    right: 0;
    z-index: 8;
    background: #f8fbff;
    min-width: 100px;
    width: 100px;
}

/* Ensure sticky columns have proper borders and shadows */
.order-table th:nth-child(1),
.order-table td:nth-child(1),
.order-table th:nth-child(2),
.order-table td:nth-child(2),
.order-table th:last-child,
.order-table td:last-child {
    border-right: 2px solid #0168fa;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

.order-table th:last-child,
.order-table td:last-child {
    border-left: 2px solid #0168fa;
    border-right: 1px solid #ddd;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
}

/* Ensure header sticky columns have higher z-index */
.order-table thead th:nth-child(1),
.order-table thead th:nth-child(2),
.order-table thead th:last-child {
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

.col-actions {
    width: 100px !important;
    min-width: 100px !important;
    max-width: 100px !important;
}

/* Content columns - allow natural sizing */
.order-table td:not(.col-checkbox):not(.col-image):not(.col-actions) {
    white-space: normal;
    word-break: break-word;
    min-width: 120px; /* Minimum width for readability */
    width: auto; /* Allow natural growth */
}

/* Remove fixed widths from content columns */
.order-table th:not(.col-checkbox):not(.col-image):not(.col-actions),
.order-table td:not(.col-checkbox):not(.col-image):not(.col-actions) {
    max-width: none; /* Remove max-width constraints */
}

table, table th, table td {
    text-transform: uppercase !important;
}

/* Ensure DataTables doesn't override our widths */
#orders-table {
    width: auto !important;
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

<!-- Summary Section (Header) -->
<div class="mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 p-4 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Total Orders</h3>
            <p class="text-xl font-bold text-gray-900">{{ number_format($orders->count()) }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-4 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Total Qty</h3>
            <p class="text-xl font-bold text-gray-900">{{ number_format($orders->sum('order_qty')) }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-4 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Buyer Amount</h3>
            <p class="text-xl font-bold text-blue-600">{{ number_format($orders->sum('buyer_total'), 2) }}</p>
        </div>
        <div class="bg-white border border-gray-200 p-4 rounded-lg shadow text-center">
            <h3 class="text-xs font-semibold text-gray-500 uppercase">Supplier Amount</h3>
            <p class="text-xl font-bold text-green-600">{{ number_format($orders->sum('supplier_total'), 2) }}</p>
        </div>
    </div>
</div>

<!-- Forms for actions -->
<form action="{{ route('reports.generateBuyerReport') }}" method="POST" id="report-form">@csrf <div id="report-ids"></div></form>
<form action="{{ route('order.bulkEditForm') }}" method="POST" id="bulk-edit-form">@csrf <div id="bulk-edit-ids-container"></div></form>
<form action="{{ route('order.bulkPo') }}" method="POST" id="bulk-po-form" target="_blank">@csrf <div id="bulk-po-ids-container"></div></form>
<form action="{{ route('reports.generateSupplierReport') }}" method="POST" id="supplier-report-form">@csrf <div id="supplier-report-ids"></div></form>
<form action="" method="POST" id="delete-form">@csrf @method('DELETE') <div id="delete-ids"></div></form>

<!-- Main Layout -->
<div class="flex flex-col md:flex-row gap-4 items-start">
    
    <!-- Left Sidebar: Status Filters (Sticky) -->
    <div class="w-full md:w-32 flex-shrink-0 md:sticky md:top-24 flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-visible pb-2 md:pb-0">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 hidden md:block">Status</h3>
        
        <a href="{{ url('order') }}"
           class="px-3 py-2 rounded text-sm text-center whitespace-nowrap {{ request('status') == '' ? 'bg-blue-600 text-white font-bold shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
           All
        </a>

        <a href="{{ url('order') }}?status=Pending"
           class="px-3 py-2 rounded text-sm text-center whitespace-nowrap {{ request('status') == 'Pending' ? 'bg-blue-600 text-white font-bold shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
           Pending
        </a>

        <a href="{{ url('order') }}?status=In Progress"
           class="px-3 py-2 rounded text-sm text-center whitespace-nowrap {{ request('status') == 'In Progress' ? 'bg-blue-600 text-white font-bold shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
           In Progress
        </a>

        <a href="{{ url('order') }}?status=Completed"
           class="px-3 py-2 rounded text-sm text-center whitespace-nowrap {{ request('status') == 'Completed' ? 'bg-blue-600 text-white font-bold shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
           Completed
        </a>
    </div>

    <!-- Center: Table -->
    <div class="flex-grow min-w-0 w-full bg-white shadow rounded-lg overflow-hidden table-wrapper">
        {{-- DataTables search box will be rendered here by default --}}
    
        <table id="orders-table" class="min-w-full text-sm order-table" style="width:100%">
            <thead>
                <tr>
                    <th class="p-2 sticky-col col-1">Ord No</th>
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
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Right Sidebar: Actions (Sticky) -->
    <div class="w-full md:w-40 flex-shrink-0 md:sticky md:top-24 flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-visible pb-2 md:pb-0">
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
    
    // Set the filter dropdown to the value from the URL
    if(currentStatusFilter) {
        let status = allStatuses.find(s => s.status_name.toLowerCase() === currentStatusFilter.toLowerCase());
        if(status) {
            $('#filter-status').val(status.id);
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
    "order": [[ 0, "desc" ]], // Sort by Order No (column 0)
    
    // Column definitions for sticky columns
    // Updated columnDefs in your DataTable initialization
columnDefs: [
    { 
        targets: 0, // First column (Order No + Checkbox)
        width: "80px",
        className: 'col-checkbox'
    },
    { 
        targets: 1, // Second column (Image/CDR)
        width: "120px", 
        className: 'col-image'
    },
    { 
        targets: -1, // Last column (Actions)
        width: "100px",
        className: 'col-actions'
    },
    // Remove the '_all' target that was setting width: "auto" for all columns
    // This allows content columns to grow naturally
],
    
    columns: [
        // 0. Checkbox + Order No (STICKY LEFT)
        { 
            data: "id", 
            orderable: true, 
            searchable: true,
            render: function(data, type, row) {
                return `<div><input type="checkbox" class="order-checkbox" value="${data}">
                        <p class="text-sm ml-2">${data}</p></div>`;
            }
        },
        // 1. Image/CDR (STICKY LEFT)
        { 
            data: "order_image", 
            orderable: false, 
            searchable: false,
            render: function(data, type, row) {
                let imgSrc = data ? `{{ asset('storage/app/public') }}/${data}` : 'https://placehold.co/90x90/e2e8f0/adb5bd?text=No+Img';
                let cdrLink = row.order_cdr ? `<a href="{{ asset('storage/app/public') }}/${row.order_cdr}" class="text-blue-600 block text-xs hover:underline" target="_blank">CDR File</a>` : '';
                return `
                    <img src="${imgSrc}" alt="Img" style="width:90px;" class="object-cover rounded border">
                    ${cdrLink}
                `;
            }
        },
        // 2. Brand (SCROLLABLE)
        { 
            data: "brand_name", 
            className: 'text-sm', 
            render: data => `<div>${data || ''}</div>` 
        },
        // 3. Product (SCROLLABLE)
        { 
            data: "product_name", 
            render: data => `<div>${data || ''}</div>` 
        },
        // 4. Qty/Unit (SCROLLABLE)
        { 
            data: "order_qty", 
            render: (data, type, row) => `<div>${data || ''} ${row.unit_shorthand || ''}</div>` 
        },
        { 
            data: "supplier_name", 
            render: data => `<div>${data || ''}</div>` 
        },
        // 8. Supp. Amount (SCROLLABLE)
        { 
            data: "supplier_total", 
            render: (data, type, row) => `
                <div>
                    <div>${row.supplier_price || 0} | ${row.supplier_mould_price || 0}</div>
                    <div class="text-sm">${parseFloat(data || 0).toFixed(2)}</div>
                </div>`
        },
        // 9. Buyer (SCROLLABLE)
        { 
            data: "buyer_name", 
            render: data => `<div>${data || ''}</div>` 
        },
        // 10. Buyer Amount (SCROLLABLE)
        { 
            data: "buyer_total", 
            render: (data, type, row) => `
                <div>
                    <div>${row.order_price || 0} | ${row.order_mouldprice || 0}</div>
                    <div class="text-sm">${parseFloat(data || 0).toFixed(2)}</div>
                </div>`
        },
        // 5. Dims/Weight (SCROLLABLE)
        { 
            data: "order_length", 
            render: (data, type, row) => `
                <div>
                    <div>${row.order_length || ''} ${row.dunit_shorthand || ''} * ${row.order_width || ''} ${row.dunit_shorthand || ''}</div>
                    <div>${row.order_weight || ''} grams</div>
                </div>` 
        },
        // 6. Dates (SCROLLABLE)
        { 
            data: "order_date", 
            render: (data, type, row) => `
                <div>
                    <div>O: <span>${row.order_date || ''}</span></div>
                    <div>D: <span >${row.order_deliverydate || ''}</span></div>
                </div>`
        },
        // 7. Supplier (SCROLLABLE)
        
        // 11. Shipping (SCROLLABLE)
        { 
            data: "shipper_name", 
            render: (data, type, row) => `
                <div>
                    <div>${data || ''}</div>
                    <div>${row.order_shipmentno || ''}</div>
                </div>`
        },
        // 12. Actions (STICKY RIGHT)
        { 
            data: "id", 
            orderable: false, 
            searchable: false,
            render: function(data, type, row) {
                var viewUrl = `{{ url('order') }}/${data}`;
                var editUrl = `{{ url('order') }}/${data}/edit`; 
                var deleteUrl = `{{ url('order') }}/${data}`;
                var duplicateUrl = `{{ url('order') }}/${data}/duplicate`;
                return `
                    <div class="flex flex-col space-y-1">
                        <a href="${viewUrl}" class="text-blue-600 hover:underline text-xs" title="View">View PO</a>
                        <a href="${editUrl}" class="text-indigo-600 hover:underline text-xs" title="Edit Full">Edit</a>
                        <form action="${duplicateUrl}" method="POST" onsubmit="return confirm('Duplicate this order?');" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline text-xs" title="Duplicate">Duplicate</button>
                        </form>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Delete this order?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs" title="Delete">Delete</button>
                        </form>
                    </div>
                `;
            }
        }
    ],
    lengthMenu: [[20, 50, 100, 300, -1], [20, 50, 100, 300, "All"]],
    pageLength: 20,
    drawCallback: function() {
        $('#select-all').prop('checked', false);
        updateSelectedIds();
    }
});
    // --- Filter Button Logic ---
    $('#filter-search-button').on('click', function() {
        table.ajax.reload();
    });

    // --- Checkbox Handlers ---
    $('#select-all').on('click', function() {
        $('.order-checkbox').prop('checked', this.checked);
        updateSelectedIds();
    });

    $('#orders-table tbody').on('click', '.order-checkbox', function() {
        updateSelectedIds();
    });

    function updateSelectedIds() {
        // Clear all forms
        $('#report-ids').empty();
        $('#bulk-edit-ids-container').empty();
        $('#bulk-po-ids-container').empty();
        $('#supplier-report-ids').empty();
        
        // Re-add hidden inputs to all forms
        $('.order-checkbox:checked').each(function() {
            let id = $(this).val();
            $('#report-ids').append(`<input type="hidden" name="order_ids[]" value="${id}">`);
            $('#bulk-edit-ids-container').append(`<input type="hidden" name="order_ids[]" value="${id}">`);
            $('#bulk-po-ids-container').append(`<input type="hidden" name="order_ids[]" value="${id}">`);
            $('#supplier-report-ids').append(`<input type="hidden" name="order_ids[]" value="${id}">`);
        });
    }

    // --- Form submission check ---
    $('#bulk-edit-form').on('submit', function(e) {
        if ($('#bulk-edit-ids-container input').length === 0) {
            alert('Please select at least one order to edit.');
            e.preventDefault();
        }
    });

    $('#bulk-po-form').on('submit', function(e) {
        if ($('#bulk-po-ids-container input').length === 0) {
            alert('Please select at least one order to print.');
            e.preventDefault();
        }
    });

    $('#report-form').on('submit', function(e) {
        if ($('#report-ids input').length === 0) {
            alert('Please select at least one order for the report.');
            e.preventDefault();
        }
    });

    $('#supplier-report-form').on('submit', function(e) {
        if ($('#supplier-report-ids input').length === 0) {
            alert('Please select at least one order for the supplier report.');
            e.preventDefault();
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
});
</script>
@endsection