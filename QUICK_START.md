# 🚀 Quick Start - GestiLimpieza

## ⚡ Inicio Rápido en 5 Minutos

### Paso 1: Preparar la BD (2 min)

En tu cliente MySQL (phpMyAdmin, MySQL Workbench, etc.):

```sql
CREATE DATABASE gestion_limpieza CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Paso 2: Instalar y Configurar (2 min)

```bash
cd C:\laragon\www\proyecto_personal_laravel\GestiLimpieza

# Instalar dependencias
composer install

# Generar clave de aplicación (si no la tiene)
php artisan key:generate
```

### Paso 3: Base de Datos (1 min)

```bash
# Ejecutar migraciones
php artisan migrate --force

# Ejecutar seeders para crear roles
php artisan db:seed --class=RolesSeeder
```

### Paso 4: Crear Usuario Admin

Opción A - Rápido (tinker):
```bash
php artisan tinker

# En la terminal de tinker:
Rol::all()  # Ver roles creados

Usuario::create([
    'correo' => 'admin@sena.edu.co',
    'nombres' => 'Administrador SENA',
    'password' => bcrypt('Admin123!'),
    'rol_id' => 1,
    'activo' => true,
    'primer_acceso' => false
])

exit
```

Opción B - SQL Directo:
```sql
INSERT INTO usuarios (correo, nombres, password, rol_id, activo, primer_acceso, created_at, updated_at) 
VALUES ('admin@sena.edu.co', 'Administrador SENA', '$2y$12$6Nqn4YnH8bHQ7Ux3E.xv7uI5Z7Z7Z7Z7Z7Z7Z7Z7Z7Z7Z7Z7Z7Z', 1, 1, 0, NOW(), NOW());
```

### Paso 5: Iniciar Servidor

```bash
php artisan serve
```

Abre en tu navegador:
```
http://localhost:8000/login
```

### ✅ Login por Defecto
- **Usuario:** admin@sena.edu.co
- **Contraseña:** Admin123!

---

## 📊 Primeros Pasos Como Admin

1. **Crear Programa**
   - Ve a: Admin → Programas
   - Haz clic en "Crear Programa"
   - Ejemplo: Nombre = "Sistemas" Descripción = "Programa de Sistemas"

2. **Crear Ficha**
   - Ve a: Admin → Fichas
   - Selecciona el programa
   - Ejemplo: Nombre = "Sistemas 001" Jornada = "Diurna"

3. **Crear Módulo**
   - Ve a: Admin → Módulos
   - Ejemplo: Nombre = "Aula 101" Ubicación = "Bloque A" Capacidad = "40"

4. **Asignar Módulo a Ficha**
   - Ve a: Admin → Módulos
   - Haz clic en "Asignar Modulo"
   - Selecciona Módulo, Ficha, Fecha Inicio (hoy), Día (0-6, donde 0=Domingo)
   - ✅ Automáticamente genera 104 turnos (2 años)

5. **Activar Vocero**
   - Ve a: Admin → Aprendices
   - Selecciona un aprendiz
   - Haz clic en "Activar Vocero"
   - ✅ Se crea usuario automáticamente: ap{id}@sena.edu.co

6. **Crear Grupos de Limpieza** (Como Vocero)
   - Cierra sesión
   - Login con vocero: ap{id}@sena.edu.co / {contraseña generada}
   - Ve a: Vocero → Grupos
   - Haz clic en "Crear Grupo"
   - Selecciona aprendices

7. **Subir Evidencias** (Como Vocero)
   - Ve a: Vocero → Subir Evidencia
   - Sube foto ANTES + foto DESPUÉS
   - ✅ El turno pasa a "Cumplido" automáticamente

---

## 🎯 Flujo Completo de Prueba

### Escenario: Limpieza del módulo Aula 101

```
1. ADMIN: Crear Programa "Sistemas"
2. ADMIN: Crear Ficha "Sistemas 001" → Programa "Sistemas"
3. ADMIN: Crear Módulo "Aula 101"
4. ADMIN: Asignar Módulo → Ficha (genera turnos)
5. SINCRONIZAR: Importar aprendices (manual en BD)
   INSERT INTO aprendices (ficha_id, id_sicefa, nombres, apellidos, documento, activo)
   VALUES (1, 'APREND001', 'Juan', 'Pérez', '12345678', 1);
6. ADMIN: Activar Vocero (aprendiz Juan)
7. VOCERO: Login con ap{id}@sena.edu.co
8. VOCERO: Crear Grupo con otros aprendices
9. VOCERO: Subir evidencias (ANTES + DESPUÉS)
10. ADMIN: Ver evidencias en Dashboard
11. ✅ Turno completado, grupo rotado al siguiente
```

---

## 🔍 Verificar que Funciona

### 1. Ver Base de Datos
```bash
# Conexión MySQL
mysql -u root -p

# Ver datos creados
USE gestion_limpieza;
SELECT * FROM programas;
SELECT * FROM fichas;
SELECT * FROM usuarios;
SELECT * FROM turnos LIMIT 10;
SELECT * FROM evidencias;
```

### 2. Ver Logs
```bash
# Log de aplicación
tail -f storage/logs/laravel.log
```

### 3. Verificar Rutas
```bash
php artisan route:list
```

### 4. Verificar Modelos
```bash
php artisan tinker

Programa::count()
Ficha::count()
Usuario::count()
Turno::count()
```

---

## ⚠️ Troubleshooting Rápido

### Error: "Connection refused"
```
Solución: Asegúrate que MySQL esté corriendo
Windows: Inicia MySQL desde Laragon o services
Linux: sudo systemctl start mysql
```

### Error: "Database gestion_limpieza not found"
```
Solución: 
1. Crear BD: CREATE DATABASE gestion_limpieza;
2. Ejecutar: php artisan migrate --force
```

### Error: "Class not found"
```
Solución:
composer dump-autoload
```

### Error: "Storage link missing"
```
Solución:
php artisan storage:link
```

### Error: "Token mismatch (CSRF)"
```
Solución: Limpiar cookies y cache del navegador
```

---

## 📱 Acceder a Diferentes Secciones

| Rol | Acceso | URL |
|-----|--------|-----|
| Admin | Dashboard | http://localhost:8000/admin |
| Admin | Programas | http://localhost:8000/admin/programas |
| Admin | Fichas | http://localhost:8000/admin/fichas |
| Admin | Módulos | http://localhost:8000/admin/modulos |
| Admin | Voceros | http://localhost:8000/admin/voceros |
| Admin | Grupos | http://localhost:8000/admin/grupos |
| Admin | Evidencias | http://localhost:8000/admin/evidencias |
| Admin | Notificaciones | http://localhost:8000/admin/notificaciones |
| Vocero | Dashboard | http://localhost:8000/vocero |
| Vocero | Mis Grupos | http://localhost:8000/vocero/grupos |
| Vocero | Subir Evidencia | http://localhost:8000/vocero/subir-evidencia |
| Vocero | Mis Evidencias | http://localhost:8000/vocero/evidencias |

---

## 🎓 Conceptos Clave

### Turno
- Se genera automáticamente al asignar módulo a ficha
- 104 turnos = 2 años (104 semanas)
- Estados: Pendiente → Abierto (cuando es HOY) → Cumplido/Incumplido

### Grupo
- Creado por vocero
- Contiene N aprendices
- Asignado a un turno
- Rota automáticamente al siguiente turno libre tras subir evidencias

### Evidencia
- 2 fotos: ANTES + DESPUÉS
- Almacenadas en: storage/app/public/uploads/evidencias/
- Genera snapshot de integrantes del grupo

### Vocero
- Aprendiz líder designado por admin
- Máximo 2 por ficha
- Gestiona grupos de limpieza
- Usuario automático: ap{id}@sena.edu.co

---

## 📞 Ayuda

- **Documentación Completa:** Ver `INSTALACION.md`
- **Resumen del Proyecto:** Ver `Proyecto GestiLimpieza - Resumen de Creación`
- **Especificación Técnica:** Ver `especificacion-laravel.md`

---

**¡Listo! Ya puedes empezar a usar GestiLimpieza 🎉**
