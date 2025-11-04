@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa fa-file-text-o text-blue-600"></i> Color Set
            </h1>
            <nav class="text-sm text-gray-500 mt-1">
                <ol class="list-reset flex space-x-2">
                    <li><i class="fa fa-home"></i> <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a></li>
                    <li>/</li>
                    <li><i class="fa fa-palette"></i> Color Set</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('colorset.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
            Add Color Set
        </a>
    </div>

    @if(Session::has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-3 font-semibold text-lg">
            Color Set List
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase">
                    <tr>
                        <th class="px-6 py-3">S.N.</th>
                        <th class="px-6 py-3">Color Set Name</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @if(!empty($colorsets))
                        @foreach($colorsets as $colorset)
                            <tr>
                                <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                <td class="px-6 py-3">{{ $colorset->colorset_name }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('colorset.edit', $colorset->id)}}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a href="{{ route('colorset.delete', $colorset->id) }}" onclick="return confirm('Are you sure to delete this record?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow">
                                            <i class="fa fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No color sets found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new DataTable('#data-table'); // Tailwind DataTables can be added if needed
    });
</script>
@endsection
