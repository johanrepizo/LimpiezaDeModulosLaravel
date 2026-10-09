<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_sincronizacion', function (Blueprint $table) {
            // Agregar timestamps si no existen
            if (!Schema::hasColumn('log_sincronizacion', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('fecha_ejecucion');
            }
            if (!Schema::hasColumn('log_sincronizacion', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('log_sincronizacion', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
};
