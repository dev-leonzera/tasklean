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
        Schema::create('convite_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_id')->constrained('times')->onDelete('cascade');
            $table->string('token')->unique();
            $table->string('email')->nullable();
            $table->string('regra')->default('membro');
            $table->integer('max_usos')->default(1);
            $table->integer('usos')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convite_times');
    }
};
