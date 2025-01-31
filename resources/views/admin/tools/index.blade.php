<!-- resources/views/admin/tools/index.blade.php -->
@extends('admin.layout')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Manage Tools</h2>
    <a href="{{ route('tools.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Add New Tool</a>
    <table class="table-auto w-full mt-4 border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Serial Number</th>
                <th class="border px-4 py-2">Category</th>
                <th class="border px-4 py-2">Image</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tools as $tool)
                <tr>
                    <td class="border px-4 py-2">{{ $tool->name }}</td>
                    <td class="border px-4 py-2">{{ $tool->serial_number }}</td>
                    <td class="border px-4 py-2">{{ $tool->category }}</td>
                    <td class="border px-4 py-2">
                        @if($tool->image)
                            <img src="{{ asset('storage/' . $tool->image) }}" alt="Tool Image" class="w-16 h-16 object-cover">
                        @else
                            No Image
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('tools.edit', $tool) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                        <form action="{{ route('tools.destroy', $tool) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
