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
        Schema::create('n8n_posts', function (Blueprint $table) {
            $table->id();
            
            $table->string('titulo');
            $table->text('texto')->nullable();
            $table->string('imagen')->nullable();
            $table->json('hashtags')->nullable();
            $table->string('footer')->nullable();
            
            // Estado de la publicación
            $table->enum('status', ['pending', 'published', 'scheduled', 'rejected'])->default('pending');
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('n8n_posts');
    }
};
