<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evidencias', function (Blueprint $table) {
            $table->foreignId('grupo_id')->nullable()->after('turno_id')->constrained('grupos')->onDelete('set null');
            $table->foreignId('vocero_id')->nullable()->after('grupo_id')->constrained('voceros')->onDelete('set null');
            $table->string('nombre_archivo', 255)->nullable()->after('tipo');
            $table->timestamp('fecha_subida')->useCurrent()->after('observaciones');
            $table->boolean('cumplimiento')->default(true)->after('fecha_subida');
        });
    }

    public function down(): void
    {
        Schema::table('evidencias', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['grupo_id', 'vocero_id']);
            $table->dropColumn(['grupo_id', 'vocero_id', 'nombre_archivo', 'fecha_subida', 'cumplimiento']);
        });
    }
};
