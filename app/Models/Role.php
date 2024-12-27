<?php

namespace App\Models;

use App\Models\User;
use App\Models\Permission;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use Searchable;
    use HasFactory;

    public $timestamps = false;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'role_name' => $this->role_name,
            'description' => $this->description
        ];
    }

    protected $fillable = ['role_name', 'description'];

    //Pivot table: user_roles
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    //Pivot table: role_permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
