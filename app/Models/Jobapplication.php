<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jobapplication extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','surname','age','carmodel','carcolor','carnumber','status','image','image_car'];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (asset('/Images/Cars/'.$value)),
        );
    }
}
