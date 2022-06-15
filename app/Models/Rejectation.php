<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rejectation extends Model
{
    use HasFactory;

    protected $fillable = ['driver_id','trip_id'];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
