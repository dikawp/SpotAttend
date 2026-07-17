<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = [
        'user_id',
        'full_name',
        'nik',
        'phone_number',
        'bank',
        'emergency',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'marital_status',
        'address',
        'hire_date',
        'department_id',
        'position_id',
        'photo',
        'schedule_start_time',
        'schedule_end_time',
        'annual_leave_days',
        'contract_type',
        'office_location_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function officeLocation()
    {
        return $this->belongsTo(OfficeLocation::class);
    }
}
