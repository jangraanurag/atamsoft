@extends('layout')
@section('title', 'Brand List')
@section('content')

<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-5xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Brand Management</h1>
            <a href="{{ route('brand.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Add Brand
            </a>
        </div>

        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Brand Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Associated Buyer</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Image</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($brands as $brand)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $brand->brand_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($brand->buyer)
                                    <div class="text-sm text-gray-800">{{ $brand->buyer->buyer_name }}</div>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($brand->brand_image)
                                <img src="{{ asset('storage/brands/' . $brand->brand_image) }}" alt="{{ $brand->brand_name }}" class="h-10 w-10 object-cover rounded-full inline-block ring-2 ring-white">
                                @else
                                <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('brand.edit', $brand->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Edit</a>
                                <span class="text-gray-300 mx-1">|</span>
                                <form action="{{ route('brand.destroy', $brand->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this brand?');">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                                    <p class="mt-2 text-lg font-semibold">No Brands Found</p>
                                    <p class="mt-1 text-sm">Get started by adding a new brand.</p>
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

@endsection
