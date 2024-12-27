<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\AuditLog;
use Laravel\Scout\Searchable;
use Kayandra\Hashidable\Hashidable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Searchable, HasFactory, Notifiable;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    protected $fillable = [
        'avatar',
        'name',
        'email',
        'password',
        'active',
        'has_access',
        'agreed_to_terms',
        'agreed_on',
        'role_id',
        'access_granted_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Define a method to check if the user has a specific permission
    public function hasPermission($permissionName)
    {
        return $this->role && $this->role->permissions()->where('permission_name', $permissionName)->exists();
    }

    public function audit_logs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    public function login_histories()
    {
        return $this->hasMany(LoginHistory::class, 'user_id');
    }
}
