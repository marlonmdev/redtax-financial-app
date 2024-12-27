<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Message extends Model
{
    use HasFactory, Searchable;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'customer_type' => $this->customer_type,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'subject' => $this->subject
        ];
    }

    protected $fillable = ['customer_type', 'name', 'preferred_contact', 'phone', 'email', 'subject', 'message', 'viewed'];
}
