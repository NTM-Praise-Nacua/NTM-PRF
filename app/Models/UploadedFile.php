<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploaded_by',
        'original_name',
        'stored_name',
        'path',
        'size',
        'type',
        'request_type_id'
    ];

    public function prfs()
    {
        return $this->belongsToMany(
            PurchaseRequisitionForm::class,
            'request_files',
            'file_id',
            'prf_id'
        )->withTimestamps();
    }
}
