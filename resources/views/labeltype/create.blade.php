@extends('layout')
@section('title', 'Create Label Type')
@section('content')
  <!-- Main Content -->
  <main class="flex-grow p-6">
    <div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create Label Type</h2>
      @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded">
        <strong>There were some errors with your submission:</strong>
        <ul class="list-disc pl-5 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
      <form method="POST" action="{{ route('labeltype.store') }}" class="space-y-6">
        @csrf

        <!-- Label Type Name -->
        <div>
          <label for="labeltype" class="block text-sm font-medium text-gray-700">Label Type</label>
          <input type="text" id="labeltype_name" name="labeltype_name" maxlength="50" required class="mt-1 w-full border rounded px-3 py-2" />
        </div>

        <!-- Submit Button -->
        <div class="text-right">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
      </form>
    </div>
  </main>
@endsection
