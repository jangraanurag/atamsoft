@extends('layout')

@section('title', 'Color Set List')

@section('content')
<main class="flex-grow bg-gray-50 p-4 sm:p-6 lg:p-8">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa fa-palette text-blue-600"></i> Color Set Management
                </h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <ol class="list-reset flex space-x-2">
                        <li><i class="fa fa-home"></i> 
                            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a>
                        </li>
                        <li>/</li>
                        <li>Color Set</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('colorset.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fa fa-plus mr-2"></i> Add Color Set
            </a>
        </div>

        <!-- Success Message -->
        @if(Session::has('message'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-md shadow-sm">
            {{ Session::get('message') }}
        </div>
        @endif

        <!-- Color Set Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto p-2">
                <table id="colorSetsTable" class="min-w-full border-collapse text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[80px]">S.N.</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider w-[350px]">Color Set Name</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider w-[150px]">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($colorsets as $colorset)
                        <tr class="hover:bg-gray-50 transition-colors align-top">
                            <!-- Serial Number -->
                            <td class="px-4 py-3 text-gray-800 text-sm text-left">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Color Set Name -->
                            <td class="px-4 py-3 max-w-[350px] break-words whitespace-normal text-gray-800">
                                {{ $colorset->colorset_name }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('colorset.edit', $colorset->id) }}" 
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow transition">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="{{ route('colorset.delete', $colorset->id) }}" 
                                       onclick="return confirm('Are you sure to delete this record?')" 
                                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow transition">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4v16m8-8H4" />
                                    </svg>
                                    <p class="font-semibold text-lg">No Color Sets Found</p>
                                    <p class="text-sm">Add a new color set to get started.</p>
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
    $('#colorSetsTable').DataTable({
        pageLength: 10,
        order: [[1, 'asc']],
        autoWidth: false,
        scrollX: false,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search color sets...",
            lengthMenu: "Show _MENU_ entries",
            zeroRecords: "No matching color sets found",
            info: "Showing _START_ to _END_ of _TOTAL_ color sets",
            infoEmpty: "No color sets available",
            paginate: {
                first: "First",
                last: "Last",
                next: "→",
                previous: "←"
            }
        },
        columnDefs: [
            { orderable: false, targets: 2 },
            { width: '80px', targets: 0 },
            { width: '350px', targets: 1 },
            { width: '150px', targets: 2 }
        ]
    });
});
</script>
@endsection
