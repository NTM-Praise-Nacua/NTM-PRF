<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'shortcut',
        'created_by',
        'approver',
        'isActive'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'id', 'department_id');
    }

    public function departmentApprover()
    {
        return $this->belongsTo(User::class, 'approver');
    }
}
