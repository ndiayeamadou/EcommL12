<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'postal_code',
        'address',
        'city',
        'country'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}