@extends('layout')
@section('title', 'Dimension Unit List')
@section('content')
  <!-- Main Content -->
  <main class="flex-grow p-6">
    <div class="bg-white rounded-xl shadow p-6 max-w-3xl mx-auto">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">DUnit List</h2>

      <div class="mb-4 text-right">
        <a href="{{ route('dunit.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add New DUnit</a>
      </div>

      @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
          <span class="block sm:inline">{{ session('success') }}</span>
        </div>
      @endif

      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b text-left">Dimension Unit Name</th>
              <th class="py-2 px-4 border-b text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($dunits as $dunit)
              <tr>
                <td class="py-2 px-4 border-b">{{ $dunit->dunit_name }}</td>
                <td class="py-2 px-4 border-b text-center">
                  <a href="{{ route('dunit.edit', $dunit->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                  <form action="{{ route('dunit.destroy', $dunit->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this dunit?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-90">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="2" class="py-4 px-4 text-center text-gray-500">No dunits found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>
@endsection
