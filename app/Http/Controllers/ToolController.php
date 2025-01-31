<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Location;
use App\Models\Employee;
use Illuminate\Http\Request;

class ToolController extends Controller
{
public function index()
{
    $tools = Tool::with('location', 'employee')->get();
    $employees = Employee::all(); // Ensure employees are fetched
    $locations = Location::all(); // Fetch locations if needed

    return view('admin.tools.index', compact('tools', 'employees', 'locations'));
}


    public function create()
    {
        $locations = Location::all();
        $employees = Employee::all();
        return view('admin.tools.create', compact('locations', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:tools',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        Tool::create($data);
        return redirect()->route('tools.index')->with('success', 'Tool added successfully!');
    }

    public function edit(Tool $tool)
    {
        $locations = Location::all();
        $employees = Employee::all();
        return view('admin.tools.edit', compact('tool', 'locations', 'employees'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:tools,serial_number,' . $tool->id,
            'category' => 'required|string',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $tool->update($data);
        return redirect()->route('tools.index')->with('success', 'Tool updated successfully!');
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();
        return redirect()->route('tools.index')->with('success', 'Tool deleted successfully!');
    }
}
