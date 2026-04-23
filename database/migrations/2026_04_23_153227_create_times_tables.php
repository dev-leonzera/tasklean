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
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('logo_path')->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('owner_id');
        });

        Schema::create('membros_time', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_id')->constrained('times')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('regra')->default('membro'); // proprietario, admin, membro
            $table->timestamps();

            $table->unique(['time_id', 'user_id']);
            $table->index('time_id');
            $table->index('user_id');
        });

        Schema::table('projetos', function (Blueprint $table) {
            $table->foreignId('time_id')->nullable()->after('responsavel_id')->constrained('times')->onDelete('set null');
            $table->index('time_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->dropForeign(['time_id']);
            $table->dropColumn('time_id');
        });
        Schema::dropIfExists('membros_time');
        Schema::dropIfExists('times');
    }
};
