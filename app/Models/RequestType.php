<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function prWorkflowSteps()
    {
        return $this->hasOne(PRWorkFlowSteps::class, 'type_id', 'id');
    }

    public function requestType()
    {
        return $this->hasMany(PurchaseRequisitionForm::class, 'request_type', 'id');
    }
}
