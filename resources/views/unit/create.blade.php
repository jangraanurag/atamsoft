@extends('layout')

@section('title', 'Create Unit')

@section('content')
<main class="flex-grow p-6">
  <div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Unit</h2>

    <form method="POST" action="{{ route('unit.store') }}" class="space-y-6">
      @csrf

      <!-- Unit Name -->
      <div>
        <label for="unit_name" class="block text-sm font-medium text-gray-700">Unit Name</label>
        <input type="text" id="unit_name" name="unit_name" maxlength="20" required class="mt-1 w-full border rounded px-3 py-2" />
      </div>

      <!-- Unit Shorthand -->
      <div>
        <label for="unit_shorthand" class="block text-sm font-medium text-gray-700">Unit Shorthand</label>
        <input type="text" id="unit_shorthand" name="unit_shorthand" maxlength="10" required class="mt-1 w-full border rounded px-3 py-2" />
      </div>

      <!-- Submit Button -->
      <div class="text-right">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save Unit</button>
      </div>
    </form>
  </div>
</main>
@endsection
