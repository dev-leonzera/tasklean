<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'idioma',
        'timezone',
        'date_format',
        'push_settings',
        'onboarding_completed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'push_settings' => 'array',
            'onboarding_completed_at' => 'datetime',
        ];
    }

    /**
     * Relacionamentos
     */
    public function settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class, 'user_id');
    }

    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class, 'user_id');
    }

    public function projetosResponsavel(): HasMany
    {
        return $this->hasMany(Projeto::class, 'responsavel_id');
    }

    public function tarefasResponsavel(): HasMany
    {
        return $this->hasMany(Tarefa::class, 'responsavel_id');
    }

    public function membrosProjeto(): HasMany
    {
        return $this->hasMany(MembroProjeto::class);
    }

    public function membrosSprint(): HasMany
    {
        return $this->hasMany(MembroSprint::class);
    }

    public function participantesCompromisso(): HasMany
    {
        return $this->hasMany(ParticipanteCompromisso::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioTarefa::class);
    }

    /**
     * Helpers de Role (Times)
     */

    public function times(): BelongsToMany
    {
        return $this->belongsToMany(Time::class, 'membros_time')
            ->withPivot('regra')
            ->withTimestamps();
    }

    public function isTeamOwner(): bool
    {
        // É dono se possuir um time ou se tiver a regra 'owner' em algum time
        return Time::where('owner_id', $this->id)->exists() ||
               $this->times()->wherePivot('regra', 'owner')->exists();
    }

    public function isTeamAdmin(): bool
    {
        return $this->times()->wherePivot('regra', 'admin')->exists();
    }

    public function getHighestRole(): string
    {
        if ($this->isTeamOwner()) {
            return 'owner';
        }

        if ($this->isTeamAdmin()) {
            return 'admin';
        }

        return 'member';
    }

    /**
     * Obtém ou cria as configurações do usuário
     *
     * @return UserSettings
     */
    public function getSettings(): UserSettings
    {
        return $this->settings ?? UserSettings::getForUser($this->id);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Boot do modelo para criar configurações automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            UserSettings::getForUser($user->id);
        });
    }
}
