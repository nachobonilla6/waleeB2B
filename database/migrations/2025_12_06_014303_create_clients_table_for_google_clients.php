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
        // Solo crear la tabla si no existe
        if (!Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table) {
            $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                $table->text('address')->nullable();
                $table->string('telefono_1')->nullable();
                $table->string('telefono_2')->nullable();
                $table->text('feedback')->nullable();
                $table->text('propuesta')->nullable();
                $table->string('proposed_site')->nullable();
                $table->boolean('propuesta_enviada')->default(false);
            $table->timestamps();
        });
        } else {
            // Si la tabla existe, agregar las columnas que faltan
            Schema::table('clients', function (Blueprint $table) {
                if (!Schema::hasColumn('clients', 'propuesta_enviada')) {
                    $table->boolean('propuesta_enviada')->default(false)->after('proposed_site');
                }
                if (!Schema::hasColumn('clients', 'address')) {
                    $table->text('address')->nullable()->after('website');
                }
                if (!Schema::hasColumn('clients', 'telefono_1')) {
                    $table->string('telefono_1')->nullable()->after('address');
                }
                if (!Schema::hasColumn('clients', 'telefono_2')) {
                    $table->string('telefono_2')->nullable()->after('telefono_1');
                }
                if (!Schema::hasColumn('clients', 'feedback')) {
                    $table->text('feedback')->nullable()->after('telefono_2');
                }
                if (!Schema::hasColumn('clients', 'propuesta')) {
                    $table->text('propuesta')->nullable()->after('feedback');
                }
                if (!Schema::hasColumn('clients', 'proposed_site')) {
                    $table->string('proposed_site')->nullable()->after('propuesta');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
