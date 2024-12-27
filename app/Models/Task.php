<?php

namespace App\Models;

use App\Models\Note;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use Searchable, HasFactory;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'assigned_by' => $this->assigned_by,
            'assigned_to' => $this->assigned_to
        ];
    }

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_by',
        'assigned_to',
        'client_id',
        'document_id',
        'assigned_agent_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
