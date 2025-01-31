<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Location;
use App\Models\Employee;
use App\Models\CheckoutLog;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index()
    {
        $tools = Tool::with('location', 'employee')->get();
        $locations = Location::all();
        $employees = Employee::all();

        return view('dashboard', compact('tools', 'locations', 'employees'));
    }

    public function checkout(Request $request, Tool $tool)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'employee_id' => 'required|exists:employees,id',
        ]);
		Log::info('Checkout Request by:', ['user' => auth()->user()]);

		if (!auth()->check()) {
			return redirect()->route('dashboard')->with('error', 'Authentication required.');
		}
        // Update tool's location and employee
        $tool->update([
            'location_id' => $request->location_id,
            'employee_id' => $request->employee_id,
        ]);

        // Log the checkout action
        CheckoutLog::create([
            'tool_id' => $tool->id,
            'location_id' => $request->location_id,
            'employee_id' => $request->employee_id,
			'admin_id' => auth()->id() ?? null, // Ensure admin_id is set
            'checked_out_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Tool checked out successfully!');
    }
}
