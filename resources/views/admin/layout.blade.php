<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Ensure Tailwind is loaded -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="p-4 bg-blue-600 text-white">
        <a href="{{ route('dashboard') }}" class="px-4">Dashboard</a>
        <a href="{{ route('locations.index') }}" class="px-4">Locations</a>
        <a href="{{ route('employees.index') }}" class="px-4">Employees</a>
        <a href="{{ route('tools.index') }}" class="px-4">Tools</a>
        <a href="{{ route('activity.log') }}" class="px-4">Activity Log</a>
<form method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <button type="submit" class="px-4 bg-red-500 text-white rounded">Logout</button>
</form>
    </nav>
    <div class="container mx-auto p-6">
        @yield('content')
    </div>
</body>
</html>