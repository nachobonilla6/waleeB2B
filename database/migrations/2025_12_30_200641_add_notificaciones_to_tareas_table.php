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
        Schema::table('tareas', function (Blueprint $table) {
            $table->boolean('notificacion_habilitada')->default(false)->after('estado');
            $table->enum('notificacion_tipo', ['relativa', 'especifica'])->nullable()->after('notificacion_habilitada');
            $table->integer('notificacion_minutos_antes')->nullable()->after('notificacion_tipo')->comment('Minutos antes de la tarea (para tipo relativa, default 60 = 1 hora)');
            $table->dateTime('notificacion_fecha_hora')->nullable()->after('notificacion_minutos_antes')->comment('Fecha y hora específica para la notificación (para tipo especifica)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn([
                'notificacion_habilitada',
                'notificacion_tipo',
                'notificacion_minutos_antes',
                'notificacion_fecha_hora',
            ]);
        });
    }
};
