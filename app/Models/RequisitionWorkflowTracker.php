<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionWorkflowTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'department_id',
        'employee_id',
        'submitted_at',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
