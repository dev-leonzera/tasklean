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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('email')->unique();
            $table->string('idioma')->default('pt-BR')->after('username');
            $table->string('timezone')->default('America/Sao_Paulo')->after('idioma');
            $table->string('date_format')->default('d/m/Y')->after('timezone');
            $table->json('push_settings')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'idioma', 'timezone', 'date_format', 'push_settings']);
        });
    }
};
