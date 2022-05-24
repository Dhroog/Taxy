<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobapplication extends Model
{
    use HasFactory;

    protected $fillable = ['name','surname','age','phone','carmodel','carcolor','carnumber','status'];
}
