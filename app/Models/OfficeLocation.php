<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius',
        'is_default',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function getTotalAssignedEmployeesAttribute()
    {
        return Employee::where('office_location_id', $this->id)
            ->orWhere(function ($query) {
                $query->whereNull('office_location_id')
                    ->whereHas('department', function ($q) {
                        $q->where('office_location_id', $this->id);
                    });
            })->count();
    }
}
