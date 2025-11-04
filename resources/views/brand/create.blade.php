@extends('layout')
@section('title', 'Create Brand')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<main class="flex-grow p-4 sm:p-6 lg:p-8 bg-gray-50">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6 sm:p-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800">Add a New Brand</h1>
                    <p class="text-gray-500 mt-2">Fill out the details below and assign a buyer.</p>
                </div>
@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
        <strong class="font-bold">Oops! Something went wrong.</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <form method="POST" action="{{ route('brand.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                    @csrf
                    <div>
                        <label for="buyer_id" class="block text-sm font-semibold text-gray-700 mb-1">Assign to Buyer</label>
                        <div class="flex items-center space-x-2">
                            <select id="buyer_id" name="buyer_id" required class="flex-grow w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white">
                                <option value="" disabled selected>Select a buyer</option>
                                @foreach($buyers as $buyer)
                                    <option value="{{ $buyer->id }}">{{ $buyer->buyer_name }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="add-buyer-button" class="flex-shrink-0 p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="brand_name" class="block text-sm font-semibold text-gray-700 mb-1">Brand Name</label>
                        <input type="text" id="brand_name" name="brand_name" maxlength="100" required class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white" placeholder="e.g., Nike, Apple, etc." />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Brand Image</label>
                        <div class="mt-2 flex items-center justify-center w-full">
                            <label for="brand_image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div id="image-preview" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 2048KB)</p>
                                </div>
                                <input id="brand_image" name="brand_image" type="file" class="hidden" accept=".jpg,.jpeg,.png,.gif,.svg" onchange="previewImage(event)" />
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{ route('brand.index') }}" class="px-6 py-2 text-sm font-semibold text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Cancel</a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">Save Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@include('buyer.partials.modal_form')
<script>
    // The JavaScript does not need any changes.
    // It will automatically pick up the new form fields.
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('buyer-modal');
        const addBuyerButton = document.getElementById('add-buyer-button');
        const closeModalButton = document.getElementById('close-modal-button');
        const newBuyerForm = document.getElementById('new-buyer-form');
        const mainBuyerSelect = document.getElementById('buyer_id');
        const modalErrorContainer = document.getElementById('modal-error-container');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        addBuyerButton.addEventListener('click', () => modal.classList.remove('hidden'));
        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
            modalErrorContainer.innerHTML = '';
            newBuyerForm.reset();
        });

        newBuyerForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            modalErrorContainer.innerHTML = '';
            const formData = new FormData(newBuyerForm);

            try {
                const response = await fetch("{{ route('api.buyers.store') }}", {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
                    body: formData,
                });

                const data = await response.json();
                if (!response.ok) {
                    if(data.errors) {
                        const errorList = document.createElement('ul');
                        errorList.className = 'list-disc list-inside bg-red-100 text-red-700 p-3 rounded';
                        for (const key in data.errors) {
                            const li = document.createElement('li');
                            li.textContent = data.errors[key][0];
                            errorList.appendChild(li);
                        }
                        modalErrorContainer.appendChild(errorList);
                    }
                    throw new Error('Server responded with an error.');
                }

                mainBuyerSelect.innerHTML = '<option value="" disabled>Select a buyer</option>';
                data.buyers.forEach(buyer => {
                    const option = document.createElement('option');
                    option.value = buyer.id;
                    option.textContent = buyer.buyer_name;
                    mainBuyerSelect.appendChild(option);
                });

                mainBuyerSelect.value = data.new_buyer_id;
                modal.classList.add('hidden');
                newBuyerForm.reset();
            } catch (error) {
                console.error('Error adding buyer:', error);
            }
        });
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('image-preview');
            output.innerHTML = `<img src="${reader.result}" class="h-full w-full object-contain p-2" />`;
            output.parentElement.classList.remove('h-64');
            output.parentElement.classList.add('h-auto', 'p-4');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
