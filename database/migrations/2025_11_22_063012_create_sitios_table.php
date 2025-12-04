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
        Schema::create('sitios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('en_linea')->default(false);
            $table->string('enlace');
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
        
        // Create table for tags
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        
        // Pivot table for sitio-tag relationship
        Schema::create('sitio_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sitio_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitio_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('sitios');
    }
};
