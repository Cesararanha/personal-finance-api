<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyIncome extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'recived_at',
    ];

    protected $casts = [
        'recived_at' => 'date',
        'amount' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
