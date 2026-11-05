# GestiLimpieza - Guía de Instalación

## Requisitos

- PHP 8.1+
- Composer
- MySQL 5.7+
- Node.js (para compilar assets si aplica)

## Pasos de Instalación

### 1. Clonar o descargar el proyecto

```bash
cd c:\laragon\www\proyecto_personal_laravel\GestiLimpieza
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Configurar el archivo .env

El archivo `.env` ya está configurado, pero verifica:

```env
APP_NAME="GestiLimpieza"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=gestion_limpieza
DB_USERNAME=root
DB_PASSWORD=
```

**Importante:** Crea la base de datos MySQL antes de ejecutar las migraciones:

```sql
CREATE DATABASE gestion_limpieza CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Generar clave de aplicación

```bash
php artisan key:generate
```

### 5. Ejecutar migraciones

```bash
php artisan migrate --force
```

### 6. Ejecutar seeders

```bash
php artisan db:seed --class=RolesSeeder
```

### 7. Crear usuario Admin inicial (opcional - manual)

Accede a la BD y ejecuta:

```sql
INSERT INTO roles (nombre, descripcion, fecha_creacion) VALUES 
('Admin', 'Administrador del sistema', NOW()),
('Vocero', 'Aprendiz líder con permisos de gestión de grupos', NOW());

INSERT INTO usuarios (correo, nombres, password, rol_id, activo, primer_acceso, fecha_creacion) VALUES 
('admin@sena.edu.co', 'Administrador', '$2y$12$...', 1, 1, 0, NOW());
```

Para generar el hash de contraseña en PHP:
```php
php -r "echo bcrypt('Admin123!');"
```

### 8. Crear directorio de almacenamiento

```bash
mkdir -p storage/app/public/uploads/evidencias
php artisan storage:link
```

### 9. Iniciar el servidor

```bash
php artisan serve
```

O en Laragon:
- Clic derecho en Laragon → Start All
- Accede a: `http://gestionlimpieza.local` (si está configurado) o `http://localhost:8000`

## Credenciales por Defecto

### Admin
- **Correo:** admin@sena.edu.co
- **Contraseña:** Admin123!

## Estructura del Proyecto

```
GestiLimpieza/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores (Auth, Admin, Vocero)
│   │   ├── Middleware/      # Middleware de autenticación y roles
│   │   └── Requests/        # Form Requests para validaciones
│   ├── Models/              # Modelos Eloquent
│   └── Services/            # Servicios de lógica de negocio
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Seeders iniciales
├── resources/
│   ├── views/               # Vistas Blade
│   │   ├── auth/            # Vistas de autenticación
│   │   ├── admin/           # Vistas de admin
│   │   ├── vocero/          # Vistas de vocero
│   │   └── layouts/         # Layouts base
├── routes/
│   └── web.php              # Rutas principales
└── storage/
    └── app/public/          # Almacenamiento de archivos
```

## Características Principales

### 1. **Gestión de Programas y Fichas**
- Admin puede crear programas y fichas
- Asociar aprendices a fichas
- Gestión de jornadas (Diurna/Nocturna)

### 2. **Gestión de Módulos**
- Admin crea módulos (aulas, laboratorios, etc.)
- Asignación automática a fichas
- Generación automática de 104 turnos (2 años)

### 3. **Voceros y Grupos de Limpieza**
- Admin activa voceros (máx 2 por ficha)
- Voceros crean grupos de limpieza
- Asignación automática de turnos

### 4. **Evidencias Fotográficas**
- Voceros suben fotos ANTES y DESPUÉS
- Validaciones de archivo (JPG, PNG, max 10 MB)
- Snapshot automático de integrantes
- Turno avanza automáticamente al siguiente disponible

### 5. **Sistema de Notificaciones**
- Notificaciones de asignaciones
- Alertas de incumplimiento
- Credenciales por email simuladas

### 6. **Seguridad**
- Autenticación con sesiones
- Hash de contraseña con Bcrypt
- Bloqueo temporal tras 3 intentos fallidos
- Validaciones de contraseña robustas
- Middleware de roles (Admin/Vocero)

## Rutas Principales

### Autenticación
- `GET  /login` - Formulario de login
- `POST /login` - Enviar login
- `POST /logout` - Cerrar sesión
- `GET  /cambiar-password` - Cambiar contraseña

### Admin
- `GET  /admin` - Dashboard
- `GET  /admin/programas` - Gestionar programas
- `GET  /admin/fichas` - Gestionar fichas
- `GET  /admin/modulos` - Gestionar módulos
- `GET  /admin/aprendices` - Ver aprendices
- `GET  /admin/voceros` - Gestionar voceros
- `GET  /admin/grupos` - Ver grupos
- `GET  /admin/evidencias` - Ver evidencias
- `GET  /admin/notificaciones` - Notificaciones
- `GET  /admin/sincronizacion` - Logs de sincronización

### Vocero
- `GET  /vocero` - Dashboard
- `GET  /vocero/grupos` - Mis grupos
- `GET  /vocero/subir-evidencia` - Subir evidencias
- `GET  /vocero/evidencias` - Mis evidencias
- `GET  /vocero/aprendices` - Aprendices
- `GET  /vocero/notificaciones` - Notificaciones

## Trabajo Pendiente

- [ ] Integración con SICEFA (API/BD)
- [ ] Envío real de correos
- [ ] Rate limiting y HTTPS
- [ ] Auditoría de accesos
- [ ] Tests unitarios
- [ ] Paginación completa en todas las tablas
- [ ] Exportación a Excel/PDF
- [ ] Gráficos de cumplimiento

## Troubleshooting

### Error de BD
```
SQLSTATE[HY000] [2002] No se puede establecer conexión
```
**Solución:** Asegúrate de que MySQL está corriendo y la BD existe.

### Error de Middleware
```
Class not found
```
**Solución:** Ejecuta `composer dump-autoload`

### Error de almacenamiento
```
League\Flysystem\FileNotFoundException
```
**Solución:** Ejecuta `php artisan storage:link`

## Contacto

Para soporte, contacta al equipo de desarrollo del SENA.
