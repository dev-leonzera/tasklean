<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compromissos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informações básicas
            $table->string('titulo');
            $table->text('descricao')->nullable();
            
            // Datas e horários
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->time('hora_inicio');
            $table->time('hora_fim')->nullable();
            
            // Localização e tipo
            $table->string('local')->nullable();
            $table->enum('tipo', [
                'reuniao',
                'evento', 
                'tarefa',
                'lembrete',
                'compromisso_pessoal',
                'outro'
            ])->default('compromisso_pessoal');
            
            // Status e prioridade
            $table->enum('status', [
                'agendado',
                'em_andamento',
                'concluido',
                'cancelado',
                'adiado'
            ])->default('agendado');
            
            $table->enum('prioridade', [
                'baixa',
                'media',
                'alta',
                'urgente'
            ])->default('media');
            
            // Lembrete e observações
            $table->datetime('lembrete')->nullable();
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['user_id', 'data_inicio']);
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'tipo']);
            $table->index(['user_id', 'prioridade']);
            $table->index('lembrete');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compromissos');
    }
};
