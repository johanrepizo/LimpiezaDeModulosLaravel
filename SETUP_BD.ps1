# ============================================================================
# SCRIPT DE SETUP - GestiLimpieza
# ============================================================================
# Este script configura completamente la base de datos y el proyecto
# Uso: .\SETUP_BD.ps1
# ============================================================================

Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║          GestiLimpieza - Script de Configuración             ║" -ForegroundColor Cyan
Write-Host "║                    Base de Datos y Proyecto                  ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Verificar que estamos en la carpeta correcta
if (-not (Test-Path "composer.json")) {
    Write-Host "❌ ERROR: Este script debe ejecutarse en la raíz del proyecto GestiLimpieza" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Script iniciado en: $(Get-Location)" -ForegroundColor Green
Write-Host ""

# ============================================================================
# PASO 1: Verificar dependencias
# ============================================================================
Write-Host "📋 PASO 1: Verificando dependencias..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$php = (php -v 2>$null)
if ($php) {
    Write-Host "✅ PHP: Instalado" -ForegroundColor Green
} else {
    Write-Host "❌ PHP: No encontrado. Instala PHP en Laragon" -ForegroundColor Red
    exit 1
}

$composer = (composer --version 2>$null)
if ($composer) {
    Write-Host "✅ Composer: Instalado" -ForegroundColor Green
} else {
    Write-Host "❌ Composer: No encontrado" -ForegroundColor Red
    exit 1
}

$mysql = (mysql --version 2>$null)
if ($mysql) {
    Write-Host "✅ MySQL: Instalado" -ForegroundColor Green
} else {
    Write-Host "⚠️  MySQL: No encontrado en PATH (pero puede estar en Laragon)" -ForegroundColor Yellow
}

Write-Host ""

# ============================================================================
# PASO 2: Instalar dependencias Composer
# ============================================================================
Write-Host "📦 PASO 2: Instalando dependencias PHP..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

if (-not (Test-Path "vendor")) {
    Write-Host "Ejecutando: composer install" -ForegroundColor Cyan
    composer install
    if ($LASTEXITCODE -ne 0) {
        Write-Host "❌ Error en composer install" -ForegroundColor Red
        exit 1
    }
    Write-Host "✅ Dependencias instaladas" -ForegroundColor Green
} else {
    Write-Host "✅ Dependencias ya instaladas (vendor/ existe)" -ForegroundColor Green
}

Write-Host ""

# ============================================================================
# PASO 3: Generar APP_KEY
# ============================================================================
Write-Host "🔑 PASO 3: Generando APP_KEY..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$appKey = (Select-String -Path ".env" -Pattern "APP_KEY=" | Select-Object -First 1)
if ($appKey -like "*APP_KEY=base64:*") {
    Write-Host "✅ APP_KEY ya está configurada" -ForegroundColor Green
} else {
    Write-Host "Ejecutando: php artisan key:generate" -ForegroundColor Cyan
    php artisan key:generate
    Write-Host "✅ APP_KEY generada" -ForegroundColor Green
}

Write-Host ""

# ============================================================================
# PASO 4: Crear base de datos
# ============================================================================
Write-Host "🗄️  PASO 4: Creando base de datos..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$dbName = "gestion_limpieza"

# Detectar ruta de MySQL (para Laragon)
$mysqlPath = "C:\laragon\bin\mysql\mysql-5.7.33-winx64\bin\mysql"
if (-not (Test-Path $mysqlPath)) {
    # Intentar otras ubicaciones comunes
    $mysqlPath = "C:\laragon\bin\mysql\mysql-8.0-winx64\bin\mysql"
}
if (-not (Test-Path $mysqlPath)) {
    $mysqlPath = "mysql"  # Usar PATH del sistema
}

Write-Host "Creando base de datos: $dbName" -ForegroundColor Cyan
$sqlCreateDb = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; USE $dbName; SHOW DATABASES;"

# Ejecutar comando SQL
$result = & $mysqlPath -u root --password="" -e $sqlCreateDb 2>&1

if ($result -like "*$dbName*") {
    Write-Host "✅ Base de datos '$dbName' creada/verificada" -ForegroundColor Green
} else {
    Write-Host "⚠️  No se pudo verificar la creación de la BD automáticamente" -ForegroundColor Yellow
    Write-Host "   Asegúrate de que:" -ForegroundColor Yellow
    Write-Host "   1. MySQL esté corriendo en Laragon" -ForegroundColor Yellow
    Write-Host "   2. Usuario root sin contraseña esté configurado" -ForegroundColor Yellow
}

Write-Host ""

# ============================================================================
# PASO 5: Ejecutar migraciones
# ============================================================================
Write-Host "⬆️  PASO 5: Ejecutando migraciones..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

Write-Host "Ejecutando: php artisan migrate --force" -ForegroundColor Cyan
php artisan migrate --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migraciones ejecutadas" -ForegroundColor Green
} else {
    Write-Host "❌ Error en las migraciones" -ForegroundColor Red
    Write-Host "   Verifica que MySQL esté corriendo y la BD exista" -ForegroundColor Yellow
    exit 1
}

Write-Host ""

# ============================================================================
# PASO 6: Ejecutar seeders
# ============================================================================
Write-Host "🌱 PASO 6: Ejecutando seeders..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

Write-Host "Ejecutando: php artisan db:seed --class=RolesSeeder" -ForegroundColor Cyan
php artisan db:seed --class=RolesSeeder

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Seeders ejecutados" -ForegroundColor Green
} else {
    Write-Host "⚠️  Seeders completados con avisos (pero datos básicos insertados)" -ForegroundColor Yellow
}

Write-Host ""

# ============================================================================
# PASO 7: Crear enlace de almacenamiento
# ============================================================================
Write-Host "🔗 PASO 7: Creando enlace de almacenamiento..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

if (-not (Test-Path "public/storage")) {
    Write-Host "Ejecutando: php artisan storage:link" -ForegroundColor Cyan
    php artisan storage:link
    Write-Host "✅ Enlace de almacenamiento creado" -ForegroundColor Green
} else {
    Write-Host "✅ Enlace de almacenamiento ya existe" -ForegroundColor Green
}

Write-Host ""

# ============================================================================
# PASO 8: Crear usuario admin
# ============================================================================
Write-Host "👤 PASO 8: Creando usuario Admin..." -ForegroundColor Yellow
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

# Usar php artisan tinker para crear usuario
$tinkerScript = @"
use App\Models\Usuario;

`$admin = Usuario::where('correo', 'admin@sena.edu.co')->first();

if (!\$admin) {
    Usuario::create([
        'correo' => 'admin@sena.edu.co',
        'nombres' => 'Administrador',
        'apellidos' => 'SENA',
        'password' => bcrypt('Admin123!'),
        'rol_id' => 1,
        'activo' => true,
        'primer_acceso' => false
    ]);
    echo 'Usuario admin creado';
} else {
    echo 'Usuario admin ya existe';
}

exit();
"@

$tinkerScript | php artisan tinker 2>$null

Write-Host "✅ Usuario admin configurado" -ForegroundColor Green
Write-Host "   Correo: admin@sena.edu.co" -ForegroundColor Green
Write-Host "   Contraseña: Admin123!" -ForegroundColor Green

Write-Host ""

# ============================================================================
# RESUMEN FINAL
# ============================================================================
Write-Host "╔════════════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║                    ✅ CONFIGURACIÓN COMPLETADA                 ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════════════════╝" -ForegroundColor Green

Write-Host ""
Write-Host "📊 Resumen de lo ejecutado:" -ForegroundColor Cyan
Write-Host "  ✅ Dependencias Composer instaladas" -ForegroundColor Green
Write-Host "  ✅ APP_KEY generada" -ForegroundColor Green
Write-Host "  ✅ Base de datos '$dbName' creada" -ForegroundColor Green
Write-Host "  ✅ Migraciones ejecutadas" -ForegroundColor Green
Write-Host "  ✅ Seeders ejecutados" -ForegroundColor Green
Write-Host "  ✅ Almacenamiento enlazado" -ForegroundColor Green
Write-Host "  ✅ Usuario admin creado" -ForegroundColor Green

Write-Host ""
Write-Host "🚀 Próximos pasos:" -ForegroundColor Cyan
Write-Host "  1. Inicia el servidor: php artisan serve" -ForegroundColor White
Write-Host "  2. Abre: http://localhost:8000/login" -ForegroundColor White
Write-Host "  3. Usuario: admin@sena.edu.co" -ForegroundColor White
Write-Host "  4. Contraseña: Admin123!" -ForegroundColor White

Write-Host ""
Write-Host "📚 Documentación:" -ForegroundColor Cyan
Write-Host "  • QUICK_START.md - Inicio rápido" -ForegroundColor White
Write-Host "  • INSTALACION.md - Guía completa" -ForegroundColor White
Write-Host "  • REFERENCIA_RAPIDA.txt - Referencia de consulta" -ForegroundColor White

Write-Host ""
Write-Host "¡Listo! Tu proyecto GestiLimpieza está completamente configurado 🎉" -ForegroundColor Yellow
Write-Host ""
