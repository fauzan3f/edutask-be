<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'changed_by',
        'from_status',
        'to_status',
        'comment',
    ];

    /**
     * Get the task that the status change belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who changed the status.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
