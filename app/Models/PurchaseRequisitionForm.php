<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequisitionForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_request',
        'date_needed',
        'status',
        'full_name',
        'request_by',
        'contact',
        'position',
        'department',
        'branch',
        'urgency',
        'request_type',
        'request_details',
        'next_department',
        'assign_employee',
    ];

    protected $casts = [
        'date_request' => 'date',
        'date_needed' => 'date',
    ];

    public function files()
    {
        return $this->belongsToMany(
            UploadedFile::class,
            'request_files', // pivot table
            'prf_id',        // foreign key on pivot for this model
            'file_id'        // foreign key on pivot for the other model
        )->withTimestamps();
    }

    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'request_type', 'id');
    }

    public function requestBy()
    {
        return $this->belongsTo(User::class, 'request_by', 'id');
    }

    public function positionName()
    {
        return $this->belongsTo(Position::class, 'position', 'id');
    }

    public function departmentName()
    {
        return $this->belongsTo(Department::class, 'department', 'id');
    }
    
    public function assignedEmployee()
    {
        return $this->belongsTo(User::class, 'assign_employee', 'id');
    }

    public function attachmentsByPRF()
    {
        return $this->belongsToMany(UploadedFile::class, 'request_files', 'prf_id', 'file_id');
    }

    public function workflowSteps()
    {
        return $this->hasMany(PRWorkFlowSteps::class, 'type_id', 'request_type');
    }

    public function tracker()
    {
        return $this->hasMany(RequisitionWorkflowTracker::class, 'requisition_id', 'id');
    }
}
