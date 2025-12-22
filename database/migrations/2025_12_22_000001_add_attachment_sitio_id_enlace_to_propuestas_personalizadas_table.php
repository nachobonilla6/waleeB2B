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
        Schema::table('propuestas_personalizadas', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('ai_prompt');
            $table->unsignedBigInteger('sitio_id')->nullable()->after('attachment');
            $table->string('enlace')->nullable()->after('sitio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propuestas_personalizadas', function (Blueprint $table) {
            $table->dropColumn(['attachment', 'sitio_id', 'enlace']);
        });
    }
};

