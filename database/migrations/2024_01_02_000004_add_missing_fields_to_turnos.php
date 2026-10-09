<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->timestamp('fecha_apertura')->nullable()->after('fecha_turno');
            $table->timestamp('fecha_cierre')->nullable()->after('fecha_apertura');
        });
    }

    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropColumn(['fecha_apertura', 'fecha_cierre']);
        });
    }
};
