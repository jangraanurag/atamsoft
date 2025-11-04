@extends('layout')
@section('title', 'Create New Buyer')
@section('content')

<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6 sm:p-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800">Create New Buyer</h1>
                    <p class="text-gray-500 mt-2">Fill out the buyer's details below.</p>
                </div>

                <form method="POST" action="{{ route('buyer.store') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="buyer_name" class="block text-sm font-semibold text-gray-700 mb-1">Buyer Name</label>
                        <input type="text" id="buyer_name" name="buyer_name" maxlength="50" required class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="buyer_email_id" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="buyer_email_id" name="buyer_email_id" maxlength="100" class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" />
                        </div>
                        <div>
                            <label for="buyer_mobile_no" class="block text-sm font-semibold text-gray-700 mb-1">Mobile No.</label>
                            <input type="text" id="buyer_mobile_no" name="buyer_mobile_no" maxlength="15" class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" />
                        </div>
                    </div>

                    <div>
                        <label for="buyer_address" class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                        <textarea id="buyer_address" name="buyer_address" rows="3" class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="buyer_city" class="block text-sm font-semibold text-gray-700 mb-1">City</label>
                            <input type="text" id="buyer_city" name="buyer_city" maxlength="50" class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" />
                        </div>
                        <div>
                            <label for="buyer_state" class="block text-sm font-semibold text-gray-700 mb-1">State</label>
                            <input type="text" id="buyer_state" name="buyer_state" maxlength="50" class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" />
                        </div>
                        <div>
                            <label for="buyer_country" class="block text-sm font-semibold text-gray-700 mb-1">Country</label>
                            <input type="text" id="buyer_country" name="buyer_country" maxlength="50" class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" />
                        </div>
                    </div>

                    <div>
                        <label for="buyer_tax_no" class="block text-sm font-semibold text-gray-700 mb-1">Tax No. (e.g., GST)</label>
                        <input type="text" id="buyer_tax_no" name="buyer_tax_no" maxlength="30" class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" />
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{ route('buyer.index') }}" class="px-6 py-2 text-sm font-semibold text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Cancel</a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            Save Buyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
