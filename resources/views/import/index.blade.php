@extends('layout')
@section('title', 'Import Data')
@section('content')

<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-3xl mx-auto">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Import Old Data (CSV)</h1>

        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
            <p class="font-bold">Instructions</p>
            <p>This tool will import orders from your old database. You must upload **both** CSV files.</p>
            <ul class="list-disc list-inside mt-2">
                <li>First, run the simple SQL script to import Buyers, Suppliers, Products, and Brands.</li>
                <li>**Orders CSV:** Export your `orderdetail` table to a CSV.</li>
                <li>**Colors CSV:** Export your `color` table to a CSV.</li>
                <li>The script will import all orders, then attach the correct colors.</li>
            </ul>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 rounded-md bg-green-100 text-green-700">
                {!! session('success') !!}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 rounded-md bg-red-100 text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    {{-- ORDERS CSV --}}
                    <div>
                        <label for="orders_csv" class="block text-sm font-medium text-gray-700">1. Orders CSV File (from `orderdetail` table)</label>
                        <input type="file" name="orders_csv" id="orders_csv" accept=".csv" required 
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                    </div>
                    
                    {{-- COLORS CSV (NEW) --}}
                    <div>
                        <label for="colors_csv" class="block text-sm font-medium text-gray-700">2. Colors CSV File (from `color` table)</label>
                        <input type="file" name="colors_csv" id="colors_csv" accept=".csv" required 
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-green-50 file:text-green-700
                                      hover:file:bg-green-100">
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 text-right">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                        <i class="fas fa-upload mr-2"></i> Upload and Import Both Files
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

@endsection