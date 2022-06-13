<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Updatedriverinfoapplication extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','surname','age','carnumber','carcolor','carmodel','image_car','image_driver'];

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Driver::class);
    }
}
