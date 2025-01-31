
@extends('admin.layout')
@section('content')
    <h2 class="text-2xl font-bold mb-4">Edit Employee</h2>
    <form action="{{ route('employees.update', $employee) }}" method="POST">
        @csrf @method('PUT')
        <label class="block">Name:</label>
        <input type="text" name="name" value="{{ $employee->name }}" class="border p-2 w-full" required>
        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </form>
@endsection
