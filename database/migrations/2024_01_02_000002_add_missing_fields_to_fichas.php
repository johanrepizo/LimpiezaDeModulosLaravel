<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fichas', function (Blueprint $table) {
            $table->string('numero_ficha', 100)->nullable()->after('programa_id');
            $table->integer('num_aprendices')->default(0)->after('jornada');
        });
    }

    public function down(): void
    {
        Schema::table('fichas', function (Blueprint $table) {
            $table->dropColumn(['numero_ficha', 'num_aprendices']);
        });
    }
};
