<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                $totalTasks = $this->tasks()->count();
                
                if ($totalTasks === 0) {
                    return 0;
                }
                
                $completedTasks = $this->tasks()->where('is_completed', true)->count();
                return round(($completedTasks / $totalTasks) * 100);
            }
        );
    }
}
