<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'contact_no',
        'department_id',
        'role_id',
        'position_id',
        'created_by',
        'approver_id',
        'created_at',
        'updated_at',
        'weak_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createdDepartments()
    {
        return $this->hasMany(Department::class, 'created_by', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdUsers()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdPositions()
    {
        return $this->hasMany(Position::class, 'created_by');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function requestBy()
    {
        return $this->hasMany(PurchaseRequisitionForm::class, 'request_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
