<?php

namespace App\Models;

use App\Models\Client;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessRequest extends Model
{
    use Searchable, HasFactory;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->names,
            'email' => $this->email,
            'requested_at' => $this->requested_at,
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'user_id',
        'requested_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
