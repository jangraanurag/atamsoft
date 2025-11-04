@extends('layout')

@section('title', 'Create Color Set')

@section('content')
<main class="flex-grow p-6">
  <div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Color Set</h2>

    <form method="POST" action="{{ route('colorset.store') }}" class="space-y-6">
      @csrf

      <!-- Color Set Name -->
      <div>
        <label for="colorset_name" class="block text-sm font-medium text-gray-700">Color Set Name</label>
        <input type="text" id="colorset_name" name="colorset_name" maxlength="50" required class="mt-1 w-full border rounded px-3 py-2" />
      </div>

      <!-- Submit Button -->
      <div class="text-right">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save Color Set</button>
      </div>
    </form>
  </div>
</main>
@endsection
