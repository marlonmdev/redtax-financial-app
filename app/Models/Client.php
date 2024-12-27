<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use App\Models\Document;
use App\Models\ClientHistory;
use App\Models\ClientService;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use Searchable, Notifiable, HasFactory;

    public $timestamps = false;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'tax_identification_number' => $this->tax_identification_number,
            'customer_type' => $this->customer_type,
            'referred_by' => $this->referred_by,
        ];
    }

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'preferred_contact',
        'address',
        'city',
        'state',
        'zip_code',
        'tax_identification_number',
        'customer_type',
        'referred_by',
        'assigned_agent_id',
        'user_id'
    ];

    protected $casts = [
        'added_on' => 'datetime',
        'updated_on' => 'datetime',
    ];

    public function client_histories()
    {
        return $this->hasMany(ClientHistory::class, 'client_id');
    }

    public function client_selected_services()
    {
        return $this->hasMany(ClientService::class);
    }

    public function client_documents()
    {
        return $this->hasMany(Document::class);
    }

    public function client_tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
