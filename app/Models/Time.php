<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'logo_path',
        'owner_id',
    ];

    /**
     * O dono do time.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Membros do time.
     */
    public function membros(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membros_time')
            ->withPivot('regra')
            ->withTimestamps();
    }

    /**
     * Projetos do time.
     */
    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class);
    }
}
