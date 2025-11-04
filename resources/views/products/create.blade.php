@extends('layout')
@section('title', 'Buyer Dashboard')
@section('content')

  <!-- Main Content -->
  <main class="flex-grow p-6">
    <div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Product</h2>

      <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
         @csrf

        <!-- Product Name -->
        <div>
          <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
          <input type="text" id="product_name" name="product_name" maxlength="100" required class="mt-1 w-full border rounded px-3 py-2" />
        </div>



        <!-- Submit Button -->
        <div class="text-right">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save Product</button>
        </div>
      </form>
    </div>
  </main>


@endsection
