<?php

namespace App\Models;

use App\Models\Service;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use Searchable, HasFactory;

    public function toSearchableArray()
    {
        $this->load('service');

        $array = [
            'id' => $this->id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'referred_by' => $this->referred_by,
            'status' => $this->status
        ];

        // Add service-related fields to the searchable array
        if ($this->service) {
            $array['service'] = $this->service->service;
        }

        return $array;
    }

    protected $casts = [
        'date' => 'date',
    ];

    protected $fillable = ['service_id', 'date', 'start_time', 'end_time', 'name', 'email', 'phone', 'location', 'details', 'referred_by', 'status'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
