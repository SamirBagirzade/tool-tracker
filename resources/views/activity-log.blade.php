<!-- resources/views/activity-log.blade.php -->
@extends('admin.layout')
@section('content')
    <h2 class="text-3xl font-bold text-center mb-6 border-b-4 pb-4 bg-gray-800 text-white rounded-lg">Activity Log</h2>

    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Search & Filter -->
    <div class="w-full mb-6 flex flex-wrap gap-4 justify-center p-4 bg-gray-200 rounded-lg shadow">
        <div class="relative w-full sm:w-auto">
            <label for="toolFilter" class="block text-sm font-medium text-gray-700">Tool</label>
            <select id="toolFilter" class="p-4 border rounded-lg bg-white shadow text-black appearance-none w-full sm:w-auto" onchange="filterLogs()">
                <option value="">All Tools</option>
                @foreach($tools as $tool)
                    <option value="{{ $tool->name }}, #{{ $tool->serial_number }}">{{ $tool->name }}, #{{ $tool->serial_number }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="relative w-full sm:w-auto">
            <label for="employeeFilter" class="block text-sm font-medium text-gray-700">Employee</label>
            <select id="employeeFilter" class="p-4 border rounded-lg bg-white shadow text-black appearance-none w-full sm:w-auto" onchange="filterLogs()">
                <option value="">All Employees</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->name }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="relative w-full sm:w-auto">
            <label for="locationFilter" class="block text-sm font-medium text-gray-700">Location</label>
            <select id="locationFilter" class="p-4 border rounded-lg bg-white shadow text-black appearance-none w-full sm:w-auto" onchange="filterLogs()">
                <option value="">All Locations</option>
                @foreach($locations as $location)
                    <option value="{{ $location->name }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="relative w-full sm:w-auto">
            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="startDate" class="p-4 border rounded-lg bg-white shadow text-black w-full sm:w-auto" onchange="filterLogs()">
        </div>
        
        <div class="relative w-full sm:w-auto">
            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" id="endDate" class="p-4 border rounded-lg bg-white shadow text-black w-full sm:w-auto" onchange="filterLogs()">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4 border-collapse border border-gray-300 shadow-lg text-sm sm:text-base" id="logTable">
            <thead class="sticky top-0 bg-gray-800 text-white font-bold">
                <tr>
                    <th class="border px-4 sm:px-6 py-3">Tool</th>
                    <th class="border px-4 sm:px-6 py-3">Serial Number</th>
                    <th class="border px-4 sm:px-6 py-3">Checked Out To</th>
                    <th class="border px-4 sm:px-6 py-3">Employee</th>
                    <th class="border px-4 sm:px-6 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $index => $log)
                    <tr class="log-entry {{ $index % 2 == 0 ? 'bg-gray-100' : 'bg-gray-50' }} hover:bg-gray-200">
                        <td class="border px-4 sm:px-6 py-3 tool-name">{{ $log->tool->name }}</td>
                        <td class="border px-4 sm:px-6 py-3 serial-number">{{ $log->tool->serial_number }}</td>
                        <td class="border px-4 sm:px-6 py-3 location">{{ $log->location->name }}</td>
                        <td class="border px-4 sm:px-6 py-3 employee">{{ $log->employee->name }}</td>
                        <td class="border px-4 sm:px-6 py-3 log-date">{{ \Carbon\Carbon::parse($log->checked_out_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-center mt-6 space-x-4">
        <button onclick="prevPage()" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-700">Previous</button>
        <span id="pageNumber" class="mx-4 text-lg font-semibold">1</span>
        <button onclick="nextPage()" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-700">Next</button>
    </div>

    <script>
        function filterLogs() {

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
            let startDate = document.getElementById("startDate").value;
            let endDate = document.getElementById("endDate").value;


            document.querySelectorAll(".log-entry").forEach(row => {
        
            let tool_name = row.querySelector(".tool-name").innerText.toLowerCase();
            let serial_number = row.querySelector(".serial-number").innerText.toLowerCase();
            let employee = row.querySelector(".employee").innerText.toLowerCase();
            let location = row.querySelector(".location").innerText.toLowerCase();
            let date = row.querySelector(".log-date").innerText.toLowerCase();
            
            let matchesTool = !toolFilter || tool_name.includes(toolFilter);
            let matchesSerial = !number || serial_number.includes(number)
            let matchesEmployee = !employeeFilter || employee.includes(employeeFilter);
            let matchesLocation = !locationFilter || location.includes(locationFilter);
            let matchesDate = (!startDate || date >= startDate) && (!endDate || date <= endDate);
            row.style.display = matchesSerial&&matchesTool&&matchesEmployee&&matchesDate&& matchesLocation  ? "table-row" : "none";
            });
        }
    </script>
@endsection
