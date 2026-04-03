<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $fillable = [
        'code',
        'short_name',
        'name',
        'name_latin',
        'min_limit',
        'max_limit',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'bank_code', 'code');
    }
}
