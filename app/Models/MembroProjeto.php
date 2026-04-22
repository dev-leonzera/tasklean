<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembroProjeto extends Model
{
    use HasFactory;
    protected $table = 'membros_projeto';
    protected $fillable = ['projeto_id', 'user_id', 'regra'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }
}
