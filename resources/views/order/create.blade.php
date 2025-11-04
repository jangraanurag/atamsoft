<!--use tailwind for design
create create order view for order creation

also add +button with every select option which linked to another table and after add list box should refresh new data and call should from api

add text field(no input only) and add color option

on click of add color new field of colors input should be added total no of field should be amount inserted in new text field.
-->
@extends('layout')
@section('title', 'Create Order')
@section('content')
hjhj
  <!-- Main Content -->
  <main class="flex-grow p-6">
    <div class="bg-white rounded-xl shadow p-6 max-w-4xl mx-auto">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Order</h2>

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

      <form action="{{ route('order.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <label for="shipper_id" class="block text-gray-700 text-sm font-bold mb-2">Shipper:</label>
            <div class="flex items-center">
              <select name="shipper_id" id="shipper_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Shipper</option>
                @foreach ($shippers as $shipper)
                  <option value="{{ $shipper->id }}" {{ old('shipper_id') == $shipper->id ? 'selected' : '' }}>{{ $shipper->shipper_name }}</option>
                @endforeach
              </select>
              <button type="button" id="addShipperBtn" class="ml-2 bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 text-sm">Add</button>
            </div>
          </div>

          <div>
            <label for="order_no" class="block text-gray-700 text-sm font-bold mb-2">Order No:</label>
            <input type="text" name="order_no" id="order_no" value="{{ old('order_no') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
          </div>

          <div>
            <label for="order_date" class="block text-gray-700 text-sm font-bold mb-2">Order Date:</label>
            <input type="date" name="order_date" id="order_date" value="{{ old('order_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
          </div>

          <div>
            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
          </div>

          <div>
            jjkjk
            <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Currency:</label>
            <input type="text" name="currency" id="currency" value="{{ old('currency') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
          </div>

          <div>
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
            <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Status</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
          </div>
        </div>

        <div class="mb-6">
          <label for="color_count" class="block text-gray-700 text-sm font-bold mb-2">Number of Colors:</label>
          <div class="flex items-center">
            <input type="number" name="color_count" id="color_count" value="{{ old('color_count', 0) }}" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <button type="button" id="addColorFieldsBtn" class="ml-2 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 text-sm">Add Colors</button>
          </div>
        </div>

        <div id="color_fields_container" class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Color input fields will be added here dynamically -->
        </div>

        <div class="flex items-center justify-between">
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Create Order
          </button>
          <a href="{{ route('order.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </main>

  <!-- Modal for adding new Shipper -->  <div id="shipperModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3 text-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Shipper</h3>
        <div class="mt-2 px-7 py-3">
          <input type="text" id="new_shipper_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Shipper Name">
        </div>
        <div class="items-center px-4 py-3">
          <button id="saveShipperBtn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
            Save
          </button>
          <button id="cancelShipperBtn" class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const addShipperBtn = document.getElementById('addShipperBtn');
      const shipperModal = document.getElementById('shipperModal');
      const saveShipperBtn = document.getElementById('saveShipperBtn');
      const cancelShipperBtn = document.getElementById('cancelShipperBtn');
      const newShipperNameInput = document.getElementById('new_shipper_name');
      const shipperSelect = document.getElementById('shipper_id');
      const addColorFieldsBtn = document.getElementById('addColorFieldsBtn');
      const colorCountInput = document.getElementById('color_count');
      const colorFieldsContainer = document.getElementById('color_fields_container');

      addShipperBtn.addEventListener('click', function() {
        shipperModal.classList.remove('hidden');
      });

      cancelShipperBtn.addEventListener('click', function() {
        shipperModal.classList.add('hidden');
        newShipperNameInput.value = '';
      });

      saveShipperBtn.addEventListener('click', function() {
        const shipperName = newShipperNameInput.value.trim();
        if (shipperName) {
          fetch('{{ route('api.shipper.store') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              shipper_name: shipperName
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const newOption = document.createElement('option');
              newOption.value = data.shipper.id;
              newOption.textContent = data.shipper.shipper_name;
              shipperSelect.appendChild(newOption);
              shipperSelect.value = data.shipper.id; // Select the newly added shipper
              shipperModal.classList.add('hidden');
              newShipperNameInput.value = '';
              alert('Shipper added successfully!');
            } else {
              alert('Error adding shipper: ' + (data.message || 'Unknown error'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the shipper.');
          });
        } else {
          alert('Shipper name cannot be empty.');
        }
      });

      addColorFieldsBtn.addEventListener('click', function() {
        const count = parseInt(colorCountInput.value);
        colorFieldsContainer.innerHTML = ''; // Clear existing fields

        if (count > 0) {
          for (let i = 0; i < count; i++) {
            const colorFieldDiv = document.createElement('div');
            colorFieldDiv.classList.add('flex', 'items-center', 'mb-2');

            const label = document.createElement('label');
            label.setAttribute('for', `color_${i}`);
            label.classList.add('block', 'text-gray-700', 'text-sm', 'font-bold', 'mr-2', 'w-24');
            label.textContent = `Color ${i + 1}:`;

            const input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('name', `colors[]`);
            input.setAttribute('id', `color_${i}`);
            input.classList.add('shadow', 'appearance-none', 'border', 'rounded', 'w-full', 'py-2', 'px-3', 'text-gray-700', 'leading-tight', 'focus:outline-none', 'focus:shadow-outline');
            input.setAttribute('placeholder', `Enter color ${i + 1}`);
            input.required = true;

            colorFieldDiv.appendChild(label);
            colorFieldDiv.appendChild(input);
            colorFieldsContainer.appendChild(colorFieldDiv);
          }
        }
      });

      // Initial generation of color fields if old('color_count') exists
      if (colorCountInput.value > 0) {
        addColorFieldsBtn.click();
      }
    });
  </script>
@endsection
