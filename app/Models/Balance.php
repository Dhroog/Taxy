<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = ['driver_id','amount'];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function reward(): HasMany
    {
        return $this->hasMany(Reward::class);
    }
}
