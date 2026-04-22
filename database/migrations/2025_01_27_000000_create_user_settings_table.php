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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Configurações de Interface
            $table->boolean('enable_sprints')->default(false);
            $table->boolean('enable_kanban')->default(true);
            
            // Configurações de Notificações
            $table->boolean('notifications_enabled')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('task_due_today')->default(true);
            $table->boolean('task_overdue')->default(true);
            $table->boolean('task_long_development')->default(true);
            $table->integer('notification_frequency')->default(60); // minutos
            
            // Configurações de Dashboard
            $table->boolean('show_projects_metric')->default(true);
            $table->boolean('show_completed_tasks')->default(true);
            $table->boolean('show_backlog_metric')->default(true);
            $table->boolean('show_overdue_tasks')->default(true);
            $table->integer('recent_activities_limit')->default(5);
            $table->integer('auto_refresh_interval')->default(30); // segundos
            
            // Configurações de Projeto
            $table->string('default_task_status', 50)->default('backlog');
            $table->integer('default_sprint_duration')->default(2); // semanas
            $table->json('working_days')->default('["monday","tuesday","wednesday","thursday","friday"]');
            $table->string('timezone', 50)->default('America/Sao_Paulo');
            $table->boolean('require_task_assignee')->default(false);
            $table->boolean('require_task_due_date')->default(false);
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
