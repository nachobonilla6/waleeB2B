<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('n8n_bots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('workflow_id'); // ID del workflow en n8n
            $table->string('trigger_type')->default('manual'); // webhook o manual
            $table->string('webhook_url')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('n8n_bots');
    }
};
