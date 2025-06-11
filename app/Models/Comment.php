<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'user_id',
        'task_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
    ];

    /**
     * Get the user that created the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task that owns the comment.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
