<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'external_id',
        'uuid',
        'payment_system_id',
        'transaction_type_id',
        'transaction_status_code',
        'transaction_sub_status_code',
        'bank_code',
        'amount',
        'currency',
        'payment_link',
    ];

    public function paymentSystem(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class);
    }

    public function transactionStatus(): BelongsTo
    {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_code', 'code');
    }

    public function transactionSubStatus(): BelongsTo
    {
        return $this->belongsTo(TransactionSubStatus::class, 'transaction_sub_status_code', 'code');
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_code' , 'code');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }
}
