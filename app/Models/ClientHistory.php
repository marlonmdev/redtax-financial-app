<?php

namespace App\Models;

use App\Models\Client;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientHistory extends Model
{
    use Searchable;
    use HasFactory;

    public $timestamps = false;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'activity' => $this->activity,
            'date' => $this->date
        ];
    }

    protected $fillable = ['client_id', 'activity', 'date'];

    public function client()
    {
        return $this->BelongsTo(Client::class, 'client_id');
    }
}
