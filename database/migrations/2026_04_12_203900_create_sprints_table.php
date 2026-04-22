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
        Schema::create('sprints', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('status')->default('ativa');
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');
            $table->foreignId('projeto_id')->constrained('projetos')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('tarefas', function (Blueprint $table) {
            $table->foreignId('sprint_id')->nullable()->after('projeto_id')->constrained('sprints')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sprint_id');
        });
        Schema::dropIfExists('sprints');
    }
};
