<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sprint extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'start_date',
        'due_date',
        'status',
        'priority',
        'user_id', 

    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class); // Assuming User is the User model's class name
    }
}
