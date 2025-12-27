<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->string('correo'); // Email del cliente (puede ser de clientes o clientes_en_proceso)
            $table->json('servicios'); // Array de servicios seleccionados
            $table->decimal('precio', 10, 2);
            $table->string('idioma', 2)->default('es'); // es, en, fr, zh
            $table->string('pdf_path')->nullable();
            $table->timestamp('enviada_at')->nullable();
            $table->string('estado')->default('enviado'); // enviado, firmado, cancelado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};

