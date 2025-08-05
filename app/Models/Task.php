<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 
        'description', 
        'author_id', 
        'assignee_id', 
        'status',
        'type',
        'target_url',
        'start_date',
        'end_date',
        'frequency'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
