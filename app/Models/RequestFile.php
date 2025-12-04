<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'prf_id',
        'file_id',
        'workflow_step_id'
    ];
}
