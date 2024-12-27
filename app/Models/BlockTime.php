<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlockTime extends Model
{
    use Searchable, HasFactory;

    public function toSearchableArray()
    {
        $array = [
            'id' => $this->id,
            'block_date' => $this->block_date,
            'block_start_time' => $this->block_start_time,
            'block_end_time' => $this->block_end_time,
            'created_at' => $this->created_at,
        ];

        return $array;
    }

    protected $fillable = ['block_date', 'block_start_time', 'block_end_time'];
}
