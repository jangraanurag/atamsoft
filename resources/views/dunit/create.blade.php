@extends('layout')
@section('title', 'Create DUnit')
@section('content')
  <!-- Main Content -->
  <main class="flex-grow p-6">
    <div class="bg-white rounded-xl shadow p-6 max-w-md mx-auto">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New DUnit</h2>

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

      <form action="{{ route('dunit.store') }}" method="POST">
        @csrf
        <div class="mb-4">
          <label for="dunit_name" class="block text-gray-700 text-sm font-bold mb-2">DUnit Name:</label>
          <input type="text" name="dunit_name" id="dunit_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('dunit_name') }}" required>
        </div>
        //block for short hand
        <div class="mb-4">
          <label for="shorthand" class="block text-gray-700 text-sm font-bold mb-2">Short Hand:</label>
          <input type="text" name="dunit_shorthand" id="dunit_shorthand" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('shorthand') }}">
        </div>

        <div class="flex items-center justify-between">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">Create DUnit</button>
          <a href="{{ route('dunit.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">Cancel</a>
        </div>
      </form>
    </div>
  </main>
@endsection
