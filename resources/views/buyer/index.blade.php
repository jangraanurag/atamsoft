@extends('layout')
@section('title', 'Buyer List')

@section('content')
<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Buyer Management</h1>
            <a href="{{ route('buyer.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Buyer
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <!-- Buyer Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto p-2">
                <table id="buyersTable" class="min-w-full border-collapse text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[220px]">Buyer Info</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[260px]">Location</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[150px]">Tax No.</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider w-[120px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($buyers as $buyer)
                        <tr class="hover:bg-gray-50 transition-colors align-top">
                            <!-- Buyer Info -->
                            <td class="px-4 py-3 max-w-[220px] text-sm text-gray-700 break-words whitespace-normal">
                                <div class="font-medium text-gray-900">{{ $buyer->buyer_name }}</div>
                                <div class="text-gray-500 break-words">{{ $buyer->buyer_email_id }}</div>
                                <div class="text-gray-500 break-words">{{ $buyer->buyer_mobile_no }}</div>
                            </td>

                            <!-- Location -->
                            <td class="px-4 py-3 max-w-[260px] text-sm text-gray-700 break-words whitespace-normal">
                                <div class="text-gray-900 break-words">{{ $buyer->buyer_address }}</div>
                                <div class="text-gray-500">{{ $buyer->buyer_city }}, {{ $buyer->buyer_state }}</div>
                                <div class="text-gray-500">{{ $buyer->buyer_country }}</div>
                            </td>

                            <!-- Tax No -->
                            <td class="px-4 py-3 max-w-[150px] text-sm text-gray-700 break-words whitespace-normal">
                                {{ $buyer->buyer_tax_no ?? 'N/A' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-center font-medium whitespace-nowrap">
                                <a href="{{ route('buyer.edit', $buyer->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 font-semibold">Edit</a>
                                <span class="text-gray-300 mx-1">|</span>
                                <form action="{{ route('buyer.destroy', $buyer->id) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('Are you sure you want to delete this buyer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-10">
                                <div class="text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                                    </svg>
                                    <p class="mt-2 text-lg font-semibold">No Buyers Found</p>
                                    <p class="mt-1 text-sm">Get started by adding a new buyer.</p>
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
    $('#buyersTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        autoWidth: false,
        scrollX: false, // no forced horizontal scroll
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search buyers...",
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No matching buyers found",
            info: "Showing _START_ to _END_ of _TOTAL_ buyers",
            infoEmpty: "No buyers available",
            paginate: {
                first: "First",
                last: "Last",
                next: "→",
                previous: "←"
            }
        },
        columnDefs: [
            { orderable: false, targets: 3 },
            { width: '220px', targets: 0 },
            { width: '260px', targets: 1 },
            { width: '150px', targets: 2 },
            { width: '120px', targets: 3 }
        ]
    });
});
</script>
@endsection
