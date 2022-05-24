<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','surname','available','lat','long'];
    //protected $table = 'driver';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function UpdateInfo()
    {
        return $this->hasOne(Updatedriverinfoapplication::class);
    }
}
