<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'roles',
            'usuarios',
            'programas',
            'fichas',
            'aprendices',
            'voceros',
            'modulos',
            'asignaciones',
            'turnos',
            'grupos',
            'evidencias',
            'historial_grupos'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table_blueprint) use ($table) {
                    if (!Schema::hasColumn($table, 'created_at')) {
                        $table_blueprint->timestamp('created_at')->nullable();
                    }
                    if (!Schema::hasColumn($table, 'updated_at')) {
                        $table_blueprint->timestamp('updated_at')->nullable();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        // No se reversa por seguridad
    }
};
