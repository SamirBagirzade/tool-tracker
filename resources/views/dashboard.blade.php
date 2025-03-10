@extends('admin.layout')
@section('content')
    <h2 class="text-3xl font-bold text-center mb-6 border-b-4 pb-4 bg-gray-800 text-white rounded-lg">Tool Status</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-2 mb-4 rounded text-center">{{ session('success') }}</div>
    @endif

    <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded text-center">
        <strong>Logged in as:</strong> {{ auth()->user()->name }} (ID: {{ auth()->user()->id }})
    </div>

    <!-- Filters Section -->
    <div class="w-full mb-6 flex flex-wrap gap-4 justify-center p-4 bg-gray-200 rounded-lg shadow">
        <div class="relative w-full sm:w-auto">
            <label for="toolFilter" class="block text-sm font-medium text-gray-700">Tool</label>
            <select id="toolFilter" class="p-4 border rounded-lg bg-white shadow text-black appearance-none w-full sm:w-auto" onchange="filterTools()">
                <option value="">All Tools</option>
                @foreach($tools as $tool)
                <option value="{{ $tool->name }}, #{{ $tool->serial_number }}">{{ $tool->name }}, #{{ $tool->serial_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative w-full sm:w-auto">
            <label for="employeeFilter" class="block text-sm font-medium text-gray-700">Employee</label>
            <select id="employeeFilter" class="p-4 border rounded-lg bg-white shadow text-black appearance-none w-full sm:w-auto" onchange="filterTools()">
                <option value="">All Employees</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->name }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative w-full sm:w-auto">
            <label for="locationFilter" class="block text-sm font-medium text-gray-700">Location</label>
            <select id="locationFilter" class="p-4 border rounded-lg bg-white shadow text-black appearance-none w-full sm:w-auto" onchange="filterTools()">
                <option value="">All Locations</option>
                @foreach($locations as $location)
                    <option value="{{ $location->name }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative w-full sm:w-auto">
            <label for="categoryFilter" class="block text-sm font-medium text-gray-700">Category</label>
            <select id="categoryFilter" class="p-4 border rounded-lg bg-white shadow text-black appearance-none w-full sm:w-auto" onchange="filterTools()">
                <option value="">All Categories</option>
                @foreach($tools->unique('category') as $tool)
                    <option value="{{ $tool->category }}">{{ $tool->category }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="relative overflow-x-auto w-full">
        <div class="w-full min-w-full">
            <table class="table-auto w-full border-collapse border border-gray-300 shadow-lg text-sm sm:text-base" id="toolTable">
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
                    <tr class="tool-entry {{ $loop->even ? 'bg-gray-100' : 'bg-gray-50' }} hover:bg-gray-200" data-tool-id="{{ $tool->id }}">

                            <td class="border px-4 sm:px-6 py-3 text-center">
                                @if(auth()->user() && auth()->user()->is_admin)
                                    <button onclick="openModal({{ $tool->id }})" class="bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto">Checkout</button>
                                @endif
                            </td>
                            <td class="border px-4 sm:px-6 py-3 tool-name">{{ $tool->name }}</td>
                            <td class="border px-4 sm:px-6 py-3 serial-number">{{ $tool->serial_number }}</td>
                            <td class="border px-4 sm:px-6 py-3 category">{{ $tool->category }}</td>
                            <td class="border px-4 sm:px-6 py-3 location">{{ $tool->location->name ?? 'Unassigned' }}</td>
                            <td class="border px-4 sm:px-6 py-3 employee">{{ $tool->employee->name ?? 'Unassigned' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript Filtering Logic -->
    <script>
        function filterTools() {
            let toolFilter2 = document.getElementById("toolFilter").value.toLowerCase();
            let toolFilter = toolFilter2.split(',')[0];
            var number = "";
            if(toolFilter2){
            let partAfterComma = toolFilter2.split(',')[1].trim(); // Extracts "#1231" and trims whitespace
             number = partAfterComma.replace('#', ''); // Removes the "#" symbol to get "1231"
            }
            else{
             number = "";
            }
            let employeeFilter = document.getElementById("employeeFilter").value.toLowerCase();
            let locationFilter = document.getElementById("locationFilter").value.toLowerCase();
            let categoryFilter = document.getElementById("categoryFilter").value.toLowerCase();

                document.querySelectorAll(".tool-entry").forEach(row => {
                let tool = row.querySelector(".tool-name").innerText.toLowerCase();
                let employee = row.querySelector(".employee").innerText.toLowerCase();
                let location = row.querySelector(".location").innerText.toLowerCase();
                let category = row.querySelector(".category").innerText.toLowerCase();
                let serial_number = row.querySelector(".serial-number").innerText.toLowerCase();

                let matchesTool = !toolFilter || tool.includes(toolFilter);
                let matchesSerial = !number || serial_number.includes(number)
                let matchesEmployee = !employeeFilter || employee.includes(employeeFilter);
                let matchesLocation = !locationFilter || location.includes(locationFilter);
                let matchesCategory = !categoryFilter || category.includes(categoryFilter);

                row.style.display = matchesSerial&&matchesTool && matchesEmployee && matchesLocation && matchesCategory ? "table-row" : "none";
            });
        }
    </script>
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
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("checkoutForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent page reload

        let form = event.target;
        let formData = new FormData(form);
        let actionUrl = form.action;

        fetch(actionUrl, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json" // Ensure Laravel returns JSON
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok"); // Handle non-200 responses
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateToolRow(data.tool); // Update the tool row in the table
                closeModal(); // Close modal
            } else {
                alert("❌ Error: " + (data.message || "Something went wrong."));
            }
        })
        .catch(error => console.error("Checkout error:", error));
    });
});

// Function to update the tool row dynamically
function updateToolRow(tool) {
    let row = document.querySelector(`tr[data-tool-id="${tool.id}"]`);
    
    if (row) {
        console.log("Updating row for tool ID:", tool.id); // Debugging log
        row.querySelector(".employee").innerText = tool.employee_name || "Unassigned";
        row.querySelector(".location").innerText = tool.location_name || "Unassigned";

        // Add an animation effect to highlight the update
        row.style.backgroundColor = "#bbf7d0"; // Equivalent to bg-green-200
        setTimeout(() => row.style.backgroundColor = "", 1000);
    } else {
        console.error("❌ Tool row not found for ID:", tool.id);
    }
}


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
