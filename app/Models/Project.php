<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Project extends Model
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
    
    public function projectManager()
    {
        return $this->belongsTo(User::class, 'user_id'); // 'user_id' is the foreign key
    }
    protected $fillable = [
        'name' ,
        'start_date' ,
        'due_date' ,
        'status' ,
        'user_id' ,

    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public $timestamps = true;

}
