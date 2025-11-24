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
}
