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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            
            // Empresa
            $table->string('nombre_empresa');
            $table->string('tipo_empresa')->nullable(); // servicios, comercio, manufactura, tecnologia, otro
            $table->string('industria')->nullable(); // turismo, gastronomia, retail, salud, educacion, tecnologia, otro
            $table->text('descripcion')->nullable();
            
            // Fechas
            $table->date('fecha_registro')->nullable();
            $table->date('fecha_creacion')->nullable();
            $table->date('fecha_cotizacion')->nullable();
            $table->date('fecha_factura')->nullable();
            $table->string('estado_cuenta')->nullable(); // activo, pendiente, suspendido, cancelado
            
            // Contacto
            $table->string('correo');
            $table->string('telefono')->nullable();
            $table->string('telefono_alternativo')->nullable();
            $table->string('whatsapp')->nullable();
            
            // Ubicación
            $table->string('pais')->nullable();
            $table->string('estado')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('direccion')->nullable();
            $table->string('codigo_postal')->nullable();
            
            // Sitio Web
            $table->string('nombre_sitio')->nullable();
            $table->string('url_sitio')->nullable();
            $table->string('hosting')->nullable();
            $table->date('dominio_expira')->nullable();
            
            // Redes Sociales (JSON para el repeater)
            $table->json('redes_sociales')->nullable();
            
            // Notas
            $table->text('notas')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
