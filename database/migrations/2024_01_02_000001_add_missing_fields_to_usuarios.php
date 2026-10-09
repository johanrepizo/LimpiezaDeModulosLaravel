<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('apellidos', 255)->nullable()->after('nombres');
            $table->string('documento', 20)->nullable()->after('apellidos');
            $table->string('celular', 20)->nullable()->after('documento');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['apellidos', 'documento', 'celular']);
        });
    }
};
