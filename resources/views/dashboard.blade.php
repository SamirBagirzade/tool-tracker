<!-- resources/views/dashboard.blade.php -->
@extends('admin.layout')
@section('content')
    <h2 class="text-3xl font-bold text-center mb-6 border-b-4 pb-4 bg-gray-800 text-white rounded-lg">Tool Status</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-2 mb-4 rounded text-center">{{ session('success') }}</div>
    @endif

    <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded text-center">
        <strong>Logged in as:</strong> {{ auth()->user()->name }} (ID: {{ auth()->user()->id }})
    </div>

    <div class="relative overflow-x-auto w-full">
        <div class="w-full min-w-full">
            <table class="table-auto w-full border-collapse border border-gray-300 shadow-lg text-sm sm:text-base">
                <thead class="sticky top-0 bg-gray-800 text-white font-bold">
                    <tr>
                        <th class="border px-4 sm:px-6 py-3 w-32">Action</th>
                        <th class="border px-4 sm:px-6 py-3">Tool</th>
                        <th class="border px-4 sm:px-6 py-3">Serial Number</th>
                        <th class="border px-4 sm:px-6 py-3">Category</th>
                        <th class="border px-4 sm:px-6 py-3">Location</th>
                        <th class="border px-4 sm:px-6 py-3">Employee</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tools as $tool)
                        <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-gray-50' }} hover:bg-gray-200">
                            <td class="border px-4 sm:px-6 py-3 text-center">
                                @if(auth()->user() && auth()->user()->is_admin)
                                    <button onclick="openModal({{ $tool->id }})" class="bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto">Checkout</button>
                                @endif
                            </td>
                            <td class="border px-4 sm:px-6 py-3">{{ $tool->name }}</td>
                            <td class="border px-4 sm:px-6 py-3">{{ $tool->serial_number }}</td>
                            <td class="border px-4 sm:px-6 py-3">{{ $tool->category }}</td>
                            <td class="border px-4 sm:px-6 py-3">{{ $tool->location->name ?? 'Unassigned' }}</td>
                            <td class="border px-4 sm:px-6 py-3">{{ $tool->employee->name ?? 'Unassigned' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Checkout Modal -->
    @if(auth()->user() && auth()->user()->is_admin)
        <div id="checkoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded shadow-lg w-full sm:w-1/3">
                <h3 class="text-xl font-bold mb-4">Checkout Tool</h3>
                <form id="checkoutForm" method="POST">
                    @csrf
                    <input type="hidden" name="tool_id" id="tool_id">
                    
                    <label class="block text-gray-700">Select Location:</label>
                    <div class="flex gap-2">
                        <select name="location_id" id="locationSelect" class="border p-2 w-full mb-2 rounded-lg" required>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openLocationModal()" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-700">+</button>
                    </div>
                    
                    <label class="block text-gray-700">Select Employee:</label>
                    <select name="employee_id" class="border p-2 w-full mb-2 rounded-lg" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700">Confirm Checkout</button>
                        <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 ml-2">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Add Location Modal -->
    <div id="addLocationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-full sm:w-1/3">
            <h3 class="text-xl font-bold mb-4">Add New Location</h3>
            <form id="addLocationForm" method="POST" action="/locations" onsubmit="addLocation(event)">
                @csrf
                <label class="block text-gray-700">Location Name:</label>
                <input type="text" name="name" id="locationName" class="border p-2 w-full mb-2 rounded-lg" required>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700">Add</button>
                    <button type="button" onclick="closeLocationModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700 ml-2">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal JavaScript -->
    <script>
    function openModal(toolId) {
        let form = document.getElementById('checkoutForm');
        form.action = '/checkout/' + toolId;
        document.getElementById('tool_id').value = toolId;

        let modal = document.getElementById('checkoutModal');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }

    function closeModal() {
         let modal = document.getElementById('checkoutModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    function openLocationModal() {
        document.getElementById('addLocationModal').classList.remove('hidden');
    }

    function closeLocationModal() {
        document.getElementById('addLocationModal').classList.add('hidden');
    }

    function addLocation(event) {
        event.preventDefault();

        let name = document.getElementById('locationName').value;
        let select = document.getElementById('locationSelect');
        
        fetch('/locations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let option = new Option(data.location.name, data.location.id);
                select.add(option);
                select.value = data.location.id;
                closeLocationModal();
            } else {
                alert('Error adding location');
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
@endsection
