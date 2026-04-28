<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConviteTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_id',
        'token',
        'email',
        'regra',
        'max_usos',
        'usos',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function time()
    {
        return $this->belongsTo(Time::class);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isFull()
    {
        return $this->usos >= $this->max_usos;
    }

    public function isValid()
    {
        return !$this->isExpired() && !$this->isFull();
    }
}
