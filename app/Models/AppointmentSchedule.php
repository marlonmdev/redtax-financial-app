<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentSchedule extends Model
{
    use Searchable, HasFactory;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'break_start_time' => $this->break_start_time,
            'break_end_time' => $this->break_end_time,
        ];
    }

    protected $fillable = ['day', 'start_time', 'end_time', 'break_start_time', 'break_end_time'];
}
