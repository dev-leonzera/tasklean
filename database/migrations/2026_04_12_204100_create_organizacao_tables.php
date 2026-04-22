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
        Schema::create('tags_projeto', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cor')->default('#3B82F6');
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('comentarios_tarefa', function (Blueprint $table) {
            $table->id();
            $table->text('conteudo');
            $table->foreignId('tarefa_id')->constrained('tarefas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios_tarefa');
        Schema::dropIfExists('tags_projeto');
    }
};
