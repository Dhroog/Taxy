<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =
        [
        's_location',
        'e_location',
        's_lat',
        's_long',
        'e_lat',
        'e_long',
        'distance',
        'duration',
        's_date',
        'e_date',
        'cost',
        'accepted',
        'canceled',
        'confirmed',
        'started',
        'ended'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function Cancellation_reason(): BelongsToMany
    {
        return $this->belongsToMany(Cancellation_reason::class,'cancellation_trips','trip_id','cancellation_reason_id');
    }

}
