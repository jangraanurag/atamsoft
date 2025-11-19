@extends('layout')
@section('title', 'Buyer Dashboard')
@section('content')

  <!-- Main Dashboard -->
  <main class="flex-grow p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <!-- Total Orders -->
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Orders</h2>
        <p class="text-3xl font-bold text-blue-600">256</p>
      </div>

      <!-- Total Order Value -->
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Order Value</h2>
        <p class="text-3xl font-bold text-green-600">1,23,45,000</p>
      </div>

      <!-- Delivered Orders -->
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Delivered Orders</h2>
        <p class="text-3xl font-bold text-purple-600">230</p>
      </div>

      <!-- Timely Delivered -->
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Timely Delivered</h2>
        <p class="text-3xl font-bold text-emerald-600">210</p>
      </div>

      <!-- Rejected Orders -->
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Rejected Orders</h2>
        <p class="text-3xl font-bold text-red-600">6</p>
      </div>

    </div>
  </main>

@endsection
