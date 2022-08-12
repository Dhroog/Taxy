<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['user_id','title','body'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_have_notifications');
    }
}
