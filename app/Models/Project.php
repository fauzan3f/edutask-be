<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'deadline',
        'progress',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'date',
        'progress' => 'integer',
    ];

    /**
     * Set the progress attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setProgressAttribute($value)
    {
        $this->attributes['progress'] = (int) $value;
    }

    /**
     * Get the user that created the project.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * The members that belong to the project.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the project manager.
     */
    public function manager()
    {
        return $this->members()->wherePivot('role', 'manager')->first();
    }

    /**
     * Get the presentation schedules for the project.
     */
    public function presentationSchedules()
    {
        return $this->hasMany(PresentationSchedule::class);
    }

    /**
     * Get the files for the project.
     */
    public function files()
    {
        return $this->morphMany(FileUpload::class, 'fileable');
    }

    /**
     * Get project completion percentage.
     */
    public function getCompletionPercentageAttribute()
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->tasks()->where('status', 'done')->count();
        
        return round(($completedTasks / $totalTasks) * 100);
    }
}
