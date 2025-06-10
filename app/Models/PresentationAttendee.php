<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentationAttendee extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'presentation_attendees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'presentation_schedule_id',
        'user_id',
        'is_attending',
        'response_notes',
        'notification_sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_attending' => 'boolean',
        'notification_sent_at' => 'datetime',
    ];

    /**
     * Get the presentation schedule that this attendee belongs to.
     */
    public function presentationSchedule()
    {
        return $this->belongsTo(PresentationSchedule::class);
    }

    /**
     * Get the user who is the attendee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
