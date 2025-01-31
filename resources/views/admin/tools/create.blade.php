@extends('admin.layout')
@section('content')
    <h2 class="text-2xl font-bold mb-4">Add Tool</h2>
    <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label class="block">Name:</label>
        <input type="text" name="name" class="border p-2 w-full" required>
        <label class="block mt-2">Serial Number:</label>
        <input type="text" name="serial_number" class="border p-2 w-full" required>
        <label class="block mt-2">Category:</label>
        <input type="text" name="category" class="border p-2 w-full" required>
        <label class="block mt-2">Image:</label>
        <input type="file" name="image" class="border p-2 w-full">
        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Save</button>
    </form>
@endsection
