<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembroTime extends Model
{
    use HasFactory;

    protected $table = 'membros_time';

    protected $fillable = [
        'time_id',
        'user_id',
        'regra',
    ];

    public function time(): BelongsTo
    {
        return $this->belongsTo(Time::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
