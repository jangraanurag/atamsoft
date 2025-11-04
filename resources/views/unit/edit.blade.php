@extends('layout')
@section('title', 'Edit Unit')
@section('content')
  <!-- Main Content -->
  <main class="flex-grow p-6">
    <div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Unit</h2>
        @if ($errors->any())
          <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
<form method="POST" action="{{ route('unit.update', ['unit' => $unit]) }}" class="space-y-6">        @csrf
        @method('PUT')

        <!-- Unit Name -->
        <div>
          <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
          <input type="text" id="unit_name" name="unit_name" maxlength="50" value="{{ old('unit_name', $unit->unit_name) }}" required class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <!-- Unit Shorthand -->
        <div>
          <label for="unit_shorthand" class="block text-sm font-medium text-gray-700">Unit Shorthand</label>
          <input type="text" id="unit_shorthand" name="unit_shorthand" maxlength="10" value="{{ old('unit_shorthand', $unit->unit_shorthand) }}" required class="mt-1 w-full border rounded px-3 py-2" />
        </div>

        <!-- Submit Button -->
        <div class="text-right">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
      </form>
    </div>
  </main>
@endsection
