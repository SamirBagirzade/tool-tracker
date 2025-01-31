
@extends('admin.layout')
@section('content')
    <h2 class="text-2xl font-bold mb-4">Manage Locations</h2>
    <a href="{{ route('locations.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Add New Location</a>
    <table class="table-auto w-full mt-4 border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($locations as $location)
                <tr>
                    <td class="border px-4 py-2">{{ $location->name }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('locations.edit', $location) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                        <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection