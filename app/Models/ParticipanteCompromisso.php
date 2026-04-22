<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipanteCompromisso extends Model
{
    use HasFactory;
    protected $table = 'participantes_compromisso';
    protected $fillable = ['compromisso_id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function compromisso(): BelongsTo
    {
        return $this->belongsTo(Compromisso::class);
    }
}
