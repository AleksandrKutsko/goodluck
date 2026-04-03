<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentSystem extends Model
{
    protected $fillable = [
        'code',
        'name',
        'keys',
        'active',
    ];

    protected $hidden = [
        'keys',
    ];

    public function scopeActive($query): void
    {
        $query->where('active', true);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
