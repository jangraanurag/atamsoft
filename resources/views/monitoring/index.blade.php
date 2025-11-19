<?php

@extends('layout')
@section('title', 'Monitoring Dashboard')

@section('header')
<style>
    @media print {
        @page { size: A4 landscape; margin: 5mm; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; font-size: 10px; background: white !important; }
        header, footer, .no-print { display: none !important; }
        main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        table { width: 100% !important; font-size: 9px; }
        th, td { padding: 2px 4px !important; white-space: normal; }
        img { max-width: 40px !important; height: auto !important; }
    }
    .summary-card { background: #f8fbff; border: 1px solid #cfe2ff; border-radius: 8px; padding: 1rem; text-align: center; }
    .summary-card h3 { font-size: 0.75rem; color: #6b7280; text-transform: uppercase; margin-bottom: 0.25rem; }
    .summary-card p { font-size: 1.25rem; font-weight: 700; color: #111827; }
    .filter-select { min-width: 180px; }
    .supplier-table th { background: #e0f7fa; color: #0f172a; }
    .supplier-table td, .supplier-table th { border: 1px solid #cfd8dc; }
    .progress-bar {
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        height: 0.75rem;
    }
    .progress-bar div { background: #3b82f6; height: 100%; }
</style>
@endsection

@section('content')
<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Monitoring Dashboard</h1>
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>

        <!-- Filters -->
        <div class="flex items-center space-x-4 mb-6">
            <label class="text-sm font-medium text-gray-700" for="status-filter">Status:</label>
            <select id="status-filter" class="filter-select border border-gray-300 rounded px-2 py-1" onchange="window.location.href='{{ url('monitoring') }}?status=' + this.value;">
                <option value="All" {{ $statusFilter == 'All' ? 'selected' : '' }}>All</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->status_name }}" {{ $statusFilter == $status->status_name ? 'selected' : '' }}>{{ $status->status_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Summary Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="summary-card">
                <h3>Total Buyer Amount</h3>
                <p>${{ number_format($totalBuyerAmount, 2) }}</p>
                <small>from {{ $totalBuyers }} buyer(s)</small>
            </div>
            <div class="summary-card">
                <h3>Total Supplier Amount</h3>
                <p>${{ number_format($totalSupplierAmount, 2) }}</p>
                <small>from {{ $totalSuppliers }} supplier(s)</small>
            </div>
        </div>

        <!-- Supplier Wise Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full supplier-table">
                    <thead class="bg-cyan-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase">Supplier Name</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase">Work Load %</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase">Total Orders</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase">Supplier Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplierData as $supplier)
                            <tr class="odd:bg-gray-50 even:bg-white">
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $supplier['supplier_name'] }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-600">{{ number_format($supplier['work_load'], 1) }}%</span>
                                        <div class="flex-1">
                                            <div class="progress-bar"><div style="width: {{ $supplier['work_load'] }}%;"></div></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $supplier['total_orders'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">${{ number_format($supplier['supplier_amount'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">No supplier data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
