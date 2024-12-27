<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'message_by', 'message_to', 'note'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'message_by');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'message_to');
    }
}
