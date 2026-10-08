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
        // Tabla: roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });

        // Tabla: usuarios
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 255)->unique();
            $table->string('nombres', 255);
            $table->string('password');
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->boolean('primer_acceso')->default(true);
            $table->integer('intentos_fallidos')->default(0);
            $table->timestamp('fecha_bloqueo')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });

        // Tabla: programas
        Schema::create('programas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });

        // Tabla: fichas
        Schema::create('fichas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->onDelete('cascade');
            $table->string('nombre', 255);
            $table->enum('jornada', ['Diurna', 'Nocturna'])->default('Diurna');
            $table->boolean('activo')->default(true);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();

            $table->unique(['programa_id', 'nombre']);
        });

        // Tabla: aprendices
        Schema::create('aprendices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_id')->constrained('fichas')->onDelete('cascade');
            $table->string('id_sicefa', 100)->unique();
            $table->string('nombres', 255);
            $table->string('apellidos', 255);
            $table->string('documento', 20);
            $table->string('correo', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_sincronizacion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });

        // Tabla: voceros
        Schema::create('voceros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aprendiz_id')->constrained('aprendices')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('ficha_id')->constrained('fichas')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_activacion')->useCurrent();
            $table->timestamp('fecha_desactivacion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();

            $table->unique(['usuario_id']);
        });

        // Tabla: modulos
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255)->unique();
            $table->string('ubicacion', 255);
            $table->integer('capacidad')->default(0);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });

        // Tabla: asignaciones
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->foreignId('ficha_id')->constrained('fichas')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->integer('dia_semana')->comment('0=Domingo, 6=Sábado');
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();

            // Índices
            $table->index(['modulo_id', 'ficha_id']);
            $table->index(['activo']);
        });

        // Tabla: turnos (sin FK a grupos inicialmente)
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignacion_id')->constrained('asignaciones')->onDelete('cascade');
            $table->date('fecha_turno');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->enum('estado', ['Pendiente', 'Abierto', 'Cumplido', 'Incumplido'])->default('Pendiente');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();

            // Índices
            $table->index(['asignacion_id']);
            $table->index(['fecha_turno']);
            $table->index(['estado']);
            $table->index(['grupo_id']);
        });

        // Tabla: grupos
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocero_id')->constrained('voceros')->onDelete('cascade');
            $table->foreignId('asignacion_id')->constrained('asignaciones')->onDelete('cascade');
            $table->string('nombre', 255);
            $table->date('fecha_limpieza')->nullable();
            $table->integer('numero_grupo')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();

            $table->index(['vocero_id']);
            $table->index(['asignacion_id']);
        });

        // Ahora agregar la FK a grupos después de que exista la tabla
        Schema::table('turnos', function (Blueprint $table) {
            $table->foreign('grupo_id')
                  ->references('id')
                  ->on('grupos')
                  ->onDelete('set null');
        });

        // Tabla: grupo_integrantes
        Schema::create('grupo_integrantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('aprendiz_id')->constrained('aprendices')->onDelete('cascade');
            $table->timestamp('fecha_creacion')->useCurrent();

            $table->unique(['grupo_id', 'aprendiz_id']);
        });

        // Tabla: evidencias
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
            $table->enum('tipo', ['antes', 'despues']);
            $table->string('ruta_archivo', 500);
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();

            $table->index(['turno_id']);
            $table->index(['tipo']);
        });

        // Tabla: evidencia_integrantes
        Schema::create('evidencia_integrantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evidencia_id')->constrained('evidencias')->onDelete('cascade');
            $table->foreignId('aprendiz_id')->constrained('aprendices')->onDelete('cascade');
            $table->string('nombre_snapshot', 255);
            $table->timestamp('fecha_creacion')->useCurrent();
        });

        // Tabla: historial_grupos
        Schema::create('historial_grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->text('descripcion');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->enum('tipo', ['Creación', 'Edición', 'Eliminación']);
            $table->timestamp('fecha_creacion')->useCurrent();
        });

        // Tabla: notificaciones
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->string('tipo', 100)->default('info');
            $table->json('datos_json')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_lectura')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();

            $table->index(['usuario_id']);
            $table->index(['leida']);
        });

        // Tabla: log_sincronizacion
        Schema::create('log_sincronizacion', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 100);
            $table->enum('estado', ['éxito', 'error'])->default('éxito');
            $table->text('mensajes')->nullable();
            $table->timestamp('fecha_ejecucion')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();

            $table->index(['estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_sincronizacion');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('historial_grupos');
        Schema::dropIfExists('evidencia_integrantes');
        Schema::dropIfExists('evidencias');
        Schema::dropIfExists('grupo_integrantes');
        Schema::dropIfExists('grupos');
        Schema::dropIfExists('turnos');
        Schema::dropIfExists('asignaciones');
        Schema::dropIfExists('modulos');
        Schema::dropIfExists('voceros');
        Schema::dropIfExists('aprendices');
        Schema::dropIfExists('fichas');
        Schema::dropIfExists('programas');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
    }
};
