<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionSubStatus extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'transaction_sub_status_code', 'code');
    }
}
