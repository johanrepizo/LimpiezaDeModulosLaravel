-- ============================================================================
-- GESTIILIMPIEZA - SCRIPT COMPLETO DE CREACIÓN DE BD
-- ============================================================================
-- Versión: 1.0
-- Fecha: Octubre 8, 2026
-- Base de Datos: gestion_limpieza
-- Motor: MySQL 5.7+
-- ============================================================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS gestion_limpieza CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_limpieza;

-- ============================================================================
-- TABLA: roles
-- ============================================================================
CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion LONGTEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: usuarios
-- ============================================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(255) NOT NULL UNIQUE,
    nombres VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255),
    documento VARCHAR(20),
    celular VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    rol_id BIGINT UNSIGNED NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    primer_acceso TINYINT(1) DEFAULT 1,
    intentos_fallidos INT DEFAULT 0,
    fecha_bloqueo TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_usuarios_rol_id FOREIGN KEY (rol_id) 
        REFERENCES roles(id) ON DELETE CASCADE,
    
    INDEX idx_correo (correo),
    INDEX idx_rol_id (rol_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: programas
-- ============================================================================
CREATE TABLE IF NOT EXISTS programas (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    descripcion LONGTEXT,
    nivel VARCHAR(100),
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    INDEX idx_nombre (nombre),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: fichas
-- ============================================================================
CREATE TABLE IF NOT EXISTS fichas (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    programa_id BIGINT UNSIGNED NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    numero_ficha VARCHAR(100),
    jornada ENUM('Diurna','Nocturna') DEFAULT 'Diurna',
    num_aprendices INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    fecha_inicio DATE,
    fecha_finalizacion DATE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_fichas_programa_id FOREIGN KEY (programa_id) 
        REFERENCES programas(id) ON DELETE CASCADE,
    
    UNIQUE KEY uk_programa_nombre (programa_id, nombre),
    INDEX idx_programa_id (programa_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: aprendices
-- ============================================================================
CREATE TABLE IF NOT EXISTS aprendices (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ficha_id BIGINT UNSIGNED NOT NULL,
    id_sicefa VARCHAR(100) NOT NULL UNIQUE,
    nombres VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    documento VARCHAR(30),
    correo VARCHAR(255),
    telefono VARCHAR(20),
    activo TINYINT(1) DEFAULT 1,
    fecha_sincronizacion TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_aprendices_ficha_id FOREIGN KEY (ficha_id) 
        REFERENCES fichas(id) ON DELETE CASCADE,
    
    INDEX idx_ficha_id (ficha_id),
    INDEX idx_id_sicefa (id_sicefa),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: voceros
-- ============================================================================
CREATE TABLE IF NOT EXISTS voceros (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    aprendiz_id BIGINT UNSIGNED NOT NULL,
    usuario_id BIGINT UNSIGNED NOT NULL,
    ficha_id BIGINT UNSIGNED NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_activacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_desactivacion TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_voceros_aprendiz_id FOREIGN KEY (aprendiz_id) 
        REFERENCES aprendices(id) ON DELETE CASCADE,
    CONSTRAINT fk_voceros_usuario_id FOREIGN KEY (usuario_id) 
        REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_voceros_ficha_id FOREIGN KEY (ficha_id) 
        REFERENCES fichas(id) ON DELETE CASCADE,
    
    UNIQUE KEY uk_usuario_id (usuario_id),
    INDEX idx_aprendiz_id (aprendiz_id),
    INDEX idx_ficha_id (ficha_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: modulos
-- ============================================================================
CREATE TABLE IF NOT EXISTS modulos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    ubicacion VARCHAR(255) NOT NULL,
    capacidad INT DEFAULT 0,
    descripcion LONGTEXT,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    INDEX idx_nombre (nombre),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: asignaciones
-- ============================================================================
CREATE TABLE IF NOT EXISTS asignaciones (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    modulo_id BIGINT UNSIGNED NOT NULL,
    ficha_id BIGINT UNSIGNED NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    fecha_limite_evidencia DATE,
    dia_semana INT NOT NULL COMMENT '0=Domingo, 6=Sábado',
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_asignaciones_modulo_id FOREIGN KEY (modulo_id) 
        REFERENCES modulos(id) ON DELETE CASCADE,
    CONSTRAINT fk_asignaciones_ficha_id FOREIGN KEY (ficha_id) 
        REFERENCES fichas(id) ON DELETE CASCADE,
    
    INDEX idx_modulo_id (modulo_id),
    INDEX idx_ficha_id (ficha_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: grupos (definida antes de turnos para FK)
-- ============================================================================
CREATE TABLE IF NOT EXISTS grupos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    vocero_id BIGINT UNSIGNED NOT NULL,
    asignacion_id BIGINT UNSIGNED NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    fecha_limpieza DATE,
    numero_grupo INT DEFAULT 1,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_grupos_vocero_id FOREIGN KEY (vocero_id) 
        REFERENCES voceros(id) ON DELETE CASCADE,
    CONSTRAINT fk_grupos_asignacion_id FOREIGN KEY (asignacion_id) 
        REFERENCES asignaciones(id) ON DELETE CASCADE,
    
    INDEX idx_vocero_id (vocero_id),
    INDEX idx_asignacion_id (asignacion_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: turnos
-- ============================================================================
CREATE TABLE IF NOT EXISTS turnos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    asignacion_id BIGINT UNSIGNED NOT NULL,
    fecha_turno DATE NOT NULL,
    grupo_id BIGINT UNSIGNED,
    fecha_apertura TIMESTAMP NULL,
    fecha_cierre TIMESTAMP NULL,
    estado ENUM('Pendiente','Abierto','Cumplido','Incumplido') DEFAULT 'Pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_turnos_asignacion_id FOREIGN KEY (asignacion_id) 
        REFERENCES asignaciones(id) ON DELETE CASCADE,
    CONSTRAINT fk_turnos_grupo_id FOREIGN KEY (grupo_id) 
        REFERENCES grupos(id) ON DELETE SET NULL,
    
    INDEX idx_asignacion_id (asignacion_id),
    INDEX idx_fecha_turno (fecha_turno),
    INDEX idx_estado (estado),
    INDEX idx_grupo_id (grupo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: grupo_integrantes
-- ============================================================================
CREATE TABLE IF NOT EXISTS grupo_integrantes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    grupo_id BIGINT UNSIGNED NOT NULL,
    aprendiz_id BIGINT UNSIGNED NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_grupo_integrantes_grupo_id FOREIGN KEY (grupo_id) 
        REFERENCES grupos(id) ON DELETE CASCADE,
    CONSTRAINT fk_grupo_integrantes_aprendiz_id FOREIGN KEY (aprendiz_id) 
        REFERENCES aprendices(id) ON DELETE CASCADE,
    
    UNIQUE KEY uk_grupo_aprendiz (grupo_id, aprendiz_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: evidencias
-- ============================================================================
CREATE TABLE IF NOT EXISTS evidencias (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    turno_id BIGINT UNSIGNED NOT NULL,
    grupo_id BIGINT UNSIGNED,
    vocero_id BIGINT UNSIGNED,
    tipo ENUM('antes','despues'),
    nombre_archivo VARCHAR(255),
    ruta_archivo VARCHAR(500) NOT NULL,
    observaciones LONGTEXT,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cumplimiento TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_evidencias_turno_id FOREIGN KEY (turno_id) 
        REFERENCES turnos(id) ON DELETE CASCADE,
    CONSTRAINT fk_evidencias_grupo_id FOREIGN KEY (grupo_id) 
        REFERENCES grupos(id) ON DELETE SET NULL,
    CONSTRAINT fk_evidencias_vocero_id FOREIGN KEY (vocero_id) 
        REFERENCES voceros(id) ON DELETE SET NULL,
    
    INDEX idx_turno_id (turno_id),
    INDEX idx_tipo (tipo),
    INDEX idx_grupo_id (grupo_id),
    INDEX idx_vocero_id (vocero_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: evidencia_integrantes
-- ============================================================================
CREATE TABLE IF NOT EXISTS evidencia_integrantes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    evidencia_id BIGINT UNSIGNED NOT NULL,
    aprendiz_id BIGINT UNSIGNED NOT NULL,
    nombre_snapshot VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_evidencia_integrantes_evidencia_id FOREIGN KEY (evidencia_id) 
        REFERENCES evidencias(id) ON DELETE CASCADE,
    CONSTRAINT fk_evidencia_integrantes_aprendiz_id FOREIGN KEY (aprendiz_id) 
        REFERENCES aprendices(id) ON DELETE CASCADE,
    
    INDEX idx_evidencia_id (evidencia_id),
    INDEX idx_aprendiz_id (aprendiz_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: historial_grupos
-- ============================================================================
CREATE TABLE IF NOT EXISTS historial_grupos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    grupo_id BIGINT UNSIGNED NOT NULL,
    descripcion LONGTEXT NOT NULL,
    usuario_id BIGINT UNSIGNED NOT NULL,
    tipo ENUM('Creación','Edición','Eliminación'),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_historial_grupos_grupo_id FOREIGN KEY (grupo_id) 
        REFERENCES grupos(id) ON DELETE CASCADE,
    CONSTRAINT fk_historial_grupos_usuario_id FOREIGN KEY (usuario_id) 
        REFERENCES usuarios(id) ON DELETE CASCADE,
    
    INDEX idx_grupo_id (grupo_id),
    INDEX idx_usuario_id (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: notificaciones
-- ============================================================================
CREATE TABLE IF NOT EXISTS notificaciones (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    asignacion_id BIGINT UNSIGNED,
    titulo VARCHAR(255) NOT NULL,
    descripcion LONGTEXT NOT NULL,
    tipo VARCHAR(100) DEFAULT 'info',
    datos_json JSON,
    leida TINYINT(1) DEFAULT 0,
    fecha_lectura TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_notificaciones_usuario_id FOREIGN KEY (usuario_id) 
        REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_notificaciones_asignacion_id FOREIGN KEY (asignacion_id) 
        REFERENCES asignaciones(id) ON DELETE SET NULL,
    
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_leida (leida),
    INDEX idx_asignacion_id (asignacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: log_sincronizacion
-- ============================================================================
CREATE TABLE IF NOT EXISTS log_sincronizacion (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(100) NOT NULL,
    estado ENUM('éxito','error') DEFAULT 'éxito',
    mensajes LONGTEXT,
    fecha_ejecucion TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    INDEX idx_estado (estado),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLA: password_resets
-- ============================================================================
CREATE TABLE IF NOT EXISTS password_resets (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expira_en TIMESTAMP NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    fecha_uso TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    
    CONSTRAINT fk_password_resets_usuario_id FOREIGN KEY (usuario_id) 
        REFERENCES usuarios(id) ON DELETE CASCADE,
    
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_token (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- INSERTS INICIALES
-- ============================================================================

-- Insertar roles
INSERT INTO roles (nombre, descripcion, fecha_creacion) VALUES 
('Admin', 'Administrador del sistema', NOW()),
('Vocero', 'Aprendiz líder con permisos de gestión de grupos', NOW());

-- ============================================================================
-- FIN DEL SCRIPT
-- ============================================================================
-- Base de datos lista para usar
-- Todas las tablas creadas con FK, índices y constraints
-- Próximo paso: php artisan migrate --force
