<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use Searchable;
    use HasFactory;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'hierarchy_level' => $this->hierarchy_level,
        ];
    }
    protected $fillable = [
        'name',
        'email',
        'phone',
        'hierarchy_level',
        'supervisor_id'
    ];
}
