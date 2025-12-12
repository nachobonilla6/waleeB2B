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
        Schema::create('workflow_runs', function (Blueprint $table) {
            $table->id();
            $table->uuid('job_id')->unique();
            $table->string('status')->default('pending'); // pending, running, completed, failed
            $table->string('step')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->json('result')->nullable();
            $table->json('data')->nullable(); // Datos adicionales del workflow
            $table->string('workflow_name')->nullable(); // Nombre del workflow
            $table->text('error_message')->nullable(); // Mensaje de error si falla
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_runs');
    }
};
