<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->date('fecha_limite_evidencia')->nullable()->after('fecha_fin');
        });
    }

    public function down(): void
    {
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->dropColumn('fecha_limite_evidencia');
        });
    }
};
