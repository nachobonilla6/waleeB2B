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
        Schema::create('n8n_errors', function (Blueprint $table) {
            $table->id();
            
            // Execution info
            $table->string('execution_id')->nullable();
            $table->string('execution_url')->nullable();
            $table->string('retry_of')->nullable();
            $table->string('mode')->nullable(); // manual, trigger, etc.
            
            // Error info
            $table->text('error_message')->nullable();
            $table->longText('error_stack')->nullable();
            $table->string('last_node_executed')->nullable();
            
            // Workflow info
            $table->string('workflow_id')->nullable();
            $table->string('workflow_name')->nullable();
            
            // Status
            $table->enum('status', ['new', 'reviewed', 'resolved', 'ignored'])->default('new');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('n8n_errors');
    }
};
