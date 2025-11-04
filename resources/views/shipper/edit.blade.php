@extends('layout')
@section('title', 'Edit Shipper')
@section('content')
  <!-- Main Content -->
  <main class="flex-grow p-6">
    <div class="bg-white rounded-xl shadow p-6 max-w-md mx-auto">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Shipper</h2>

      @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <strong class="font-bold">Whoops!</strong>
          <span class="block sm:inline">There were some problems with your input.</span>
          <ul class="mt-3 list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('shipper.update', $shipper->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
          <label for="shipper_name" class="block text-gray-700 text-sm font-bold mb-2">Shipper Name:</label>
          <input type="text" name="shipper_name" id="shipper_name" value="{{ old('shipper_name', $shipper->shipper_name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Update Shipper
          </button>
          <a href="{{ route('shipper.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </main>
@endsection
