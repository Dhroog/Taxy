<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rejectation extends Model
{
    use HasFactory;

    protected $fillable = ['driver_id','trip_id'];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function Rejection_reason(): BelongsToMany
    {
        return $this->belongsToMany(Rejection_reason::class,'rejectation_trips','rejectation_id','rejection_reason_id');
    }
}
