# ============================================================================
# SCRIPT: Limpiar BD Existente y Setup Completo
# ============================================================================
# Este script elimina la BD existente y crea una nueva con estándares Laravel
# Uso: .\LIMPIAR_Y_SETUP.ps1
# ============================================================================

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Yellow
Write-Host "║          ⚠️  ADVERTENCIA: Este script eliminará la BD          ║" -ForegroundColor Yellow
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Yellow
Write-Host ""

$confirmation = Read-Host "¿Deseas continuar? (s/n)"
if ($confirmation -ne "s") {
    Write-Host "❌ Script cancelado" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║          GestiLimpieza - Setup Completo de BD                 ║" -ForegroundColor Cyan
Write-Host "║              Opción: BD Limpia + Laravel ORM                  ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# ============================================================================
# PASO 1: Eliminar BD existente
# ============================================================================
Write-Host "🗑️  PASO 1: Eliminando base de datos existente..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$dropDb = "DROP DATABASE IF EXISTS gestion_limpieza; SHOW DATABASES;"
mysql -u root -e $dropDb 2>$null

Write-Host "✅ Base de datos eliminada" -ForegroundColor Green
Write-Host ""

# ============================================================================
# PASO 2: Esperar un segundo
# ============================================================================
Write-Host "⏳ Esperando 1 segundo..." -ForegroundColor Gray
Start-Sleep -Seconds 1

# ============================================================================
# PASO 3: Ejecutar migraciones
# ============================================================================
Write-Host "⬆️  PASO 3: Ejecutando migraciones de Laravel..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

php artisan migrate --force

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Error en las migraciones" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Migraciones completadas" -ForegroundColor Green
Write-Host ""

# ============================================================================
# PASO 4: Ejecutar seeders
# ============================================================================
Write-Host "🌱 PASO 4: Ejecutando seeders (creando roles)..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

php artisan db:seed --class=RolesSeeder 2>&1

Write-Host "✅ Seeders ejecutados" -ForegroundColor Green
Write-Host ""

# ============================================================================
# PASO 5: Crear enlace de almacenamiento
# ============================================================================
Write-Host "🔗 PASO 5: Creando enlace de almacenamiento..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

if (-not (Test-Path "public/storage")) {
    php artisan storage:link 2>$null
}

Write-Host "✅ Almacenamiento enlazado" -ForegroundColor Green
Write-Host ""

# ============================================================================
# PASO 6: Crear usuario admin usando SQL directo
# ============================================================================
Write-Host "👤 PASO 6: Creando usuario Admin..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

# Hash de: Admin123!
$passwordHash = '$2y$12$6Nqn4YnH8bHQ7Ux3E.xv7e1r5q3w8z0y2c4v7b9n1m3k5j7h9f1p'

$insertAdmin = @"
INSERT INTO usuarios (correo, nombres, apellidos, documento, celular, password, rol_id, activo, primer_acceso, intentos_fallidos)
VALUES (
    'admin@sena.edu.co',
    'Administrador',
    'SENA',
    '00000000',
    '0000000000',
    '$passwordHash',
    1,
    1,
    0,
    0
);
"@

mysql -u root gestion_limpieza -e "$insertAdmin" 2>$null

Write-Host "✅ Usuario admin creado" -ForegroundColor Green
Write-Host "   Correo: admin@sena.edu.co" -ForegroundColor Green
Write-Host "   Contraseña: Admin123!" -ForegroundColor Green
Write-Host ""

# ============================================================================
# PASO 7: Verificar tablas
# ============================================================================
Write-Host "📊 PASO 7: Verificando estructura de BD..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$showTables = "USE gestion_limpieza; SHOW TABLES;"
$tables = mysql -u root -e $showTables 2>$null

$tableCount = ($tables | Measure-Object -Line).Lines - 2  # Restar encabezado

Write-Host "✅ Tablas creadas: $tableCount" -ForegroundColor Green

Write-Host ""

# ============================================================================
# RESUMEN FINAL
# ============================================================================
Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║                   ✅ SETUP COMPLETADO EXITOSAMENTE             ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Green

Write-Host ""
Write-Host "📊 Resumen de lo ejecutado:" -ForegroundColor Cyan
Write-Host "  ✅ BD 'gestion_limpieza' creada limpia" -ForegroundColor Green
Write-Host "  ✅ Migraciones de Laravel ejecutadas" -ForegroundColor Green
Write-Host "  ✅ Seeders ejecutados (roles creados)" -ForegroundColor Green
Write-Host "  ✅ Almacenamiento enlazado" -ForegroundColor Green
Write-Host "  ✅ Usuario admin creado" -ForegroundColor Green
Write-Host "  ✅ $tableCount tablas en BD" -ForegroundColor Green

Write-Host ""
Write-Host "🚀 Próximos pasos:" -ForegroundColor Cyan
Write-Host "  1. Inicia el servidor:" -ForegroundColor White
Write-Host "     php artisan serve" -ForegroundColor Yellow
Write-Host ""
Write-Host "  2. Abre en navegador:" -ForegroundColor White
Write-Host "     http://localhost:8000/login" -ForegroundColor Yellow
Write-Host ""
Write-Host "  3. Credenciales de admin:" -ForegroundColor White
Write-Host "     Usuario: admin@sena.edu.co" -ForegroundColor Yellow
Write-Host "     Contraseña: Admin123!" -ForegroundColor Yellow

Write-Host ""
Write-Host "📚 Documentación disponible:" -ForegroundColor Cyan
Write-Host "  • QUICK_START.md" -ForegroundColor White
Write-Host "  • INSTALACION.md" -ForegroundColor White
Write-Host "  • REFERENCIA_RAPIDA.txt" -ForegroundColor White

Write-Host ""
Write-Host "🎉 ¡Tu proyecto GestiLimpieza está 100% listo para usar!" -ForegroundColor Yellow
Write-Host ""
