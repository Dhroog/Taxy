<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'delved'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}
