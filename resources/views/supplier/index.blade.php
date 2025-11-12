@extends('layout')
@section('title', 'Suppliers')

@section('content')
<main class="flex-grow bg-gray-50 p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa fa-industry text-blue-600"></i> Supplier Management
            </h1>
            <a href="{{ route('supplier.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fa fa-plus mr-2"></i> Add New Supplier
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-md shadow-sm">
            <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        <!-- Supplier Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto p-2">
                <table id="supplierTable" class="min-w-full border-collapse text-sm text-gray-700">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[200px]">Name</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[250px]">Email</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[150px]">Phone</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[150px]">Tax ID</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider w-[150px]">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition-colors align-top">
                            <!-- Name -->
                            <td class="px-4 py-3 max-w-[200px] break-words whitespace-normal font-medium text-gray-900">
                                {{ $supplier->name }}
                            </td>
                            <!-- Email -->
                            <td class="px-4 py-3 max-w-[250px] break-words whitespace-normal text-gray-800">
                                {{ $supplier->email ?? 'N/A' }}
                            </td>
                            <!-- Phone -->
                            <td class="px-4 py-3 max-w-[150px] break-words whitespace-normal text-gray-800">
                                {{ $supplier->phone ?? 'N/A' }}
                            </td>
                            <!-- Tax ID -->
                            <td class="px-4 py-3 max-w-[150px] break-words whitespace-normal text-gray-800">
                                {{ $supplier->tax_id ?? 'N/A' }}
                            </td>
                            <!-- Actions -->
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('supplier.edit', $supplier->id) }}" 
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow-sm transition">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm transition">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4v16m8-8H4" />
                                    </svg>
                                    <p class="font-semibold text-lg">No Suppliers Found</p>
                                    <p class="text-sm">Add a new supplier to get started.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.min.js"></script>

<script>
$(document).ready(function() {
    $('#supplierTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        autoWidth: false,
        scrollX: false,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search suppliers...",
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No matching suppliers found",
            info: "Showing _START_ to _END_ of _TOTAL_ suppliers",
            infoEmpty: "No suppliers available",
            paginate: {
                first: "First",
                last: "Last",
                next: "→",
                previous: "←"
            }
        },
        columnDefs: [
            { orderable: false, targets: 4 },
            { width: '200px', targets: 0 },
            { width: '250px', targets: 1 },
            { width: '150px', targets: 2 },
            { width: '150px', targets: 3 },
            { width: '150px', targets: 4 }
        ]
    });
});
</script>
@endsection
