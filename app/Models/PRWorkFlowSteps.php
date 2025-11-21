<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRWorkFlowSteps extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'ordering',
        'created_by',
    ];

    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'type_id');
    }
}
