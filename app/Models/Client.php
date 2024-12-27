<?php

namespace App\Models;

use App\Models\ClientHistory;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use Searchable;
    use HasFactory;

    public $timestamps = false;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contact_details' => $this->contact_details,
            'tax_identification_number' => $this->tax_identification_number,
            'segment' => $this->segment
        ];
    }

    protected $fillable = [
        'name',
        'contact_details',
        'address',
        'tax_identification_number',
        'segment',
        'assigned_agent_id'
    ];

    public function client_histories()
    {
        $this->hasMany(ClientHistory::class, 'client_id');
    }
}
