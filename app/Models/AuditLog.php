<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use Searchable, HasFactory;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'activity' => $this->activity
        ];
    }

    protected $fillable = [
        'user_id',
        'name',
        'activity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
