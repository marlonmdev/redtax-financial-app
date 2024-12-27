<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Permission extends Model
{
    use Searchable;
    use HasFactory;

    public $timestamps = false;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'permission_name' => $this->permission_name,
            'description' => $this->description
        ];
    }

    protected $fillable = ['permission_name', 'description'];

    //Pivot table: role_permissions
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
