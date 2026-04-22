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
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('status', ['backlog', 'pendente', 'em desenvolvimento', 'concluida'])->default('backlog');
            $table->timestamp('data_criacao')->useCurrent();
            $table->timestamp('data_vencimento')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index('status');
            $table->index('data_vencimento');
            $table->index('user_id');
            $table->index('responsavel_id');
            $table->index('projeto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
