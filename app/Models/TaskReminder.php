<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReminder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'reminder_time',
        'is_sent',
        'sent_at',
        'reminder_type',
        'message',
        'is_recurring',
        'recurrence_pattern',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'reminder_time' => 'datetime',
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    /**
     * Get the task that the reminder belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user that the reminder is for.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
