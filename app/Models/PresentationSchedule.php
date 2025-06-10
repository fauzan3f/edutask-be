<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentationSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'scheduled_at',
        'duration_minutes',
        'location',
        'created_by',
        'status',
        'notes',
        'meeting_link',
        'reminder_sent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'reminder_sent' => 'boolean',
    ];

    /**
     * Get the project that the presentation belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who created the presentation schedule.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The attendees for this presentation.
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'presentation_attendees')
                    ->withPivot(['is_attending', 'response_notes'])
                    ->withTimestamps();
    }
}
