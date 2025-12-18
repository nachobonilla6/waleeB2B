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
        Schema::create('emails_recibidos', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->nullable()->unique();
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->string('subject');
            $table->longText('body');
            $table->longText('body_html')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            
            $table->index('from_email');
            $table->index('is_read');
            $table->index('received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails_recibidos');
    }
};

