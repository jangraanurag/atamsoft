
<div id="buyer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Buyer</h3>
            <div id="modal-error-container" class="my-2 text-left"></div>
            <form id="new-buyer-form" class="mt-2 space-y-3 text-left">
                <div>
                    <label for="modal_buyer_name" class="block text-sm font-medium text-gray-700">Buyer Name</label>
                    <input type="text" name="buyer_name" id="modal_buyer_name" required class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="modal_buyer_email_id" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="buyer_email_id" id="modal_buyer_email_id" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="modal_buyer_mobile_no" class="block text-sm font-medium text-gray-700">Mobile No</label>
                        <input type="text" name="buyer_mobile_no" id="modal_buyer_mobile_no" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                </div>
                 <div>
                    <label for="modal_buyer_address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="buyer_address" id="modal_buyer_address" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="modal_buyer_city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="buyer_city" id="modal_buyer_city" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="modal_buyer_state" class="block text-sm font-medium text-gray-700">State</label>
                        <input type="text" name="buyer_state" id="modal_buyer_state" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="modal_buyer_country" class="block text-sm font-medium text-gray-700">Country</label>
                        <input type="text" name="buyer_country" id="modal_buyer_country" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                </div>
                <div>
                    <label for="modal_buyer_tax_no" class="block text-sm font-medium text-gray-700">Tax No.</label>
                    <input type="text" name="buyer_tax_no" id="modal_buyer_tax_no" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div class="items-center px-4 py-3">
                    <button id="save-buyer-button" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none">Save Buyer</button>
                    <button id="close-modal-button" type="button" class="mt-2 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
