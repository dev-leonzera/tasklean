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
        Schema::create('membros_projeto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('regra')->default('membro'); // ex: admin, editor, visualizador
            $table->timestamps();

            $table->unique(['projeto_id', 'user_id']);
        });

        Schema::create('membros_sprint', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sprint_id')->constrained('sprints')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['sprint_id', 'user_id']);
        });

        Schema::create('participantes_compromisso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compromisso_id')->constrained('compromissos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['compromisso_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participantes_compromisso');
        Schema::dropIfExists('membros_sprint');
        Schema::dropIfExists('membros_projeto');
    }
};
