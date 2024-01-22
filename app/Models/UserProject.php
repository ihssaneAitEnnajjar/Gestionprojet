<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Your other model code here...

    // For example, if your Project model has relationships with other models,
    // you can define them here:

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ?: null;
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value ?: null;
    }
}
