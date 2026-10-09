<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->foreignId('asignacion_id')->nullable()->after('usuario_id')->constrained('asignaciones')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('notificaciones_asignacion_id_foreign');
            $table->dropColumn('asignacion_id');
        });
    }
};
