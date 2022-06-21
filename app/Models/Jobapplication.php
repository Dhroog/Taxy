<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jobapplication extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','surname','age','phone','carmodel','carcolor','carnumber','status','image'];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (asset('/Images/Cars/'.$value)),
        );
    }
}
