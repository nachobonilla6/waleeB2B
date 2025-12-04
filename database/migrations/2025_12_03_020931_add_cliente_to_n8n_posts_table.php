<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('n8n_posts', function (Blueprint $table) {
            $table->string('cliente')->default('websolutions')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('n8n_posts', function (Blueprint $table) {
            $table->dropColumn('cliente');
        });
    }
};
