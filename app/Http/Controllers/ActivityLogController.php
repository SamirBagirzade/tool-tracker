<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckoutLog;
use App\Models\Tool;
use App\Models\Employee;
use App\Models\User;
use App\Models\Location;

class ActivityLogController extends Controller
{
public function index()
{
    $logs = CheckoutLog::with(['tool', 'employee', 'location', 'admin'])->latest()->get();
    $tools = Tool::all();
    $employees = Employee::all();
    $admins = User::where('is_admin', 1)->get(); // Get only admin users
	$locations = Location::all();

    return view('activity-log', compact('logs', 'tools', 'employees', 'locations'));
}

}
