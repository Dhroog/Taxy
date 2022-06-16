<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','surname','available','lat','long'];
    //protected $table = 'driver';

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function UpdateInfo(): HasMany
    {
        return $this->hasMany(Updatedriverinfoapplication::class);
    }

    public function car(): HasOne
    {
        return $this->hasOne(Car::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function rejection(): HasMany
    {
        return $this->hasMany(Rejectation::class);
    }

    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }
}
