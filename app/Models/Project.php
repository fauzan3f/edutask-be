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
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'manager_id',
        'start_date',
        'end_date',
        'status',
        'priority',
        'code',
        'is_archived',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_archived' => 'boolean',
    ];

    /**
     * Get the user that created the project.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the manager of the project.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * The users that belong to the project.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
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
