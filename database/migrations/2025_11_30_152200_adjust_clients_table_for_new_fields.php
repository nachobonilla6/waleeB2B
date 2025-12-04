<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Agregar nuevos campos
            $table->text('address')->nullable()->after('website');
            $table->string('telefono_1')->nullable()->after('address');
            $table->string('telefono_2')->nullable()->after('telefono_1');
            $table->text('feedback')->nullable()->after('telefono_2');
            $table->text('propuesta')->nullable()->after('feedback');
        });
        
        // Migrar datos existentes a los nuevos campos
        DB::statement('UPDATE clients SET telefono_1 = phone WHERE phone IS NOT NULL');
        DB::statement('UPDATE clients SET feedback = message WHERE message IS NOT NULL');
        DB::statement('UPDATE clients SET propuesta = proposed_site WHERE proposed_site IS NOT NULL');
        
        // Eliminar columnas antiguas
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['phone', 'message', 'proposed_site']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Restaurar columnas antiguas
            $table->string('phone')->nullable()->after('telefono_1');
            $table->text('message')->nullable()->after('feedback');
            $table->string('proposed_site')->nullable()->after('propuesta');
        });
        
        // Migrar datos de vuelta
        DB::statement('UPDATE clients SET phone = telefono_1 WHERE telefono_1 IS NOT NULL');
        DB::statement('UPDATE clients SET message = feedback WHERE feedback IS NOT NULL');
        DB::statement('UPDATE clients SET proposed_site = propuesta WHERE propuesta IS NOT NULL');
        
        // Eliminar campos nuevos
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['address', 'telefono_1', 'telefono_2', 'feedback', 'propuesta']);
        });
    }
};
