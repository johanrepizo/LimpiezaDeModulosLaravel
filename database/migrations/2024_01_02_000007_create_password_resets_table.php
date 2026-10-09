<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('token', 255)->unique();
            $table->timestamp('expira_en');
            $table->boolean('usado')->default(false);
            $table->timestamp('fecha_uso')->nullable();
            $table->timestamps();

            $table->index(['usuario_id']);
            $table->index(['token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
