<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = ['balance_id','amount','description'];

    public function balance(): BelongsTo
    {
        return $this->belongsTo(Balance::class);
    }

}
