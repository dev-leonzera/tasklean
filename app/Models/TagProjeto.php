<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagProjeto extends Model
{
    use HasFactory;
    protected $table = 'tags_projeto';
    protected $fillable = ['nome', 'cor', 'projeto_id'];

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }
}
