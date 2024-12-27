<?php

namespace App\Models;

use App\Models\Timeslot;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use Searchable, HasFactory;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'service' => $this->service,
            'duration' => $this->duration,
        ];
    }

    protected $fillable = ['service', 'duration'];
}
