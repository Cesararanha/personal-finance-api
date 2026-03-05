<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingTransaction extends Model
{
    protected $table = 'savings_transactions';

    protected $fillable = [
        'savings_id',
        'user_id',
        'type',
        'amount',
        'description',
        'date',
    ];

    protected $casts = [
        'amount' => 'float',
        'date' => 'date',
    ];

    public function savingBox(): BelongsTo
    {
        return $this->belongsTo(Saving::class, 'savings_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
