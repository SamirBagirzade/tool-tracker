<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CheckoutLog extends Model
{
    use HasFactory;

    protected $fillable = ['tool_id', 'employee_id', 'admin_id','location_id', 'checked_out_at'];

    protected $dates = ['checked_out_at']; // Ensures Laravel treats it as a date

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
	public function admin()
{
    return $this->belongsTo(User::class, 'admin_id')->withDefault([
        'name' => 'Unknown Admin'
    ]);
}
}
