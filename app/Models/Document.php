<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Document extends Model
{
    use Searchable, HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'document_name',
        'document_path',
        'document_size',
        'document_extension',
        'uploaded_by',
        'upload_date',
        'viewed',
        'downloaded',
        'access_level',
        'uploaded_by_agent_id'
    ];

    protected $casts = [
        'upload_date' => 'datetime',
    ];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'document_name' => $this->document_name,
            'document_size' => $this->document_size,
            'uploaded_by' => $this->uploaded_by,
            'upload_date' => $this->upload_date,
        ];
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
