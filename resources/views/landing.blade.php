<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GestiLimpieza — Sistema de Gestión de Limpieza SENA SICEFA</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-green:     #109116;
            --brand-green-lt:  #14b51c;
            --brand-dark:      #0a5c0e;
            --brand-dark2:     #084a0b;
            --brand-mid:       #109116;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(180deg, #109116 0%, #0a5c0e 100%);
            color: #ffffff;
            margin: 0;
            overflow-x: hidden;
        }

        /* ── NAVBAR ─────────────────────────────── */
        .landing-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(10, 92, 14, 0.94);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding: 14px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-brand-icon {
            width: 40px; height: 40px;
            background: #ffffff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            padding: 2px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            flex-shrink: 0;
        }
        .nav-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .nav-brand-text {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
        }
        .nav-brand-text span {
            background: linear-gradient(135deg, #ffffff 0%, #fef08a 40%, #facc15 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 1px 6px rgba(250, 204, 21, 0.4));
        }
        .btn-nav-login {
            background: #ffffff;
            color: #0a5c0e;
            border: none;
            border-radius: 8px;
            padding: 9px 22px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            display: flex; align-items: center; gap: 7px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .btn-nav-login:hover {
            background: #f8fafc;
            color: #1e550c;
            transform: translateY(-1px);
        }

        /* ── HERO ────────────────────────────────── */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 20px 80px;
            background:
                radial-gradient(ellipse 80% 60% at 50% 0%, rgba(255, 255, 255, 0.18) 0%, transparent 70%),
                radial-gradient(circle at 80% 80%, rgba(20, 181, 28, 0.25) 0%, transparent 50%),
                linear-gradient(135deg, #109116 0%, #0a5c0e 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            border-radius: 100px;
            padding: 6px 16px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 28px;
            letter-spacing: 0.3px;
        }
        .hero-badge i { font-size: 11px; }

        .hero-title {
            font-size: clamp(42px, 7vw, 80px);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 24px;
            filter: drop-shadow(0 6px 20px rgba(0, 0, 0, 0.4));
        }

        .title-fade {
            background: linear-gradient(180deg, #ffffff 25%, rgba(255, 255, 255, 0.70) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.25));
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, #ffffff 0%, #fef08a 25%, #facc15 65%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            filter: drop-shadow(0 3px 14px rgba(250, 204, 21, 0.55));
        }

        .hero-subtitle {
            font-size: 17px;
            color: rgba(255, 255, 255, 0.92);
            max-width: 580px;
            margin: 0 auto 44px;
            line-height: 1.7;
            font-weight: 400;
        }

        .hero-cta-group {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-cta-primary {
            background: #ffffff;
            color: #0a5c0e;
            border: none;
            border-radius: 12px;
            padding: 15px 36px;
            font-size: 15.5px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.22);
            transition: all 0.25s;
        }
        .btn-cta-primary:hover {
            background: #f8fafc;
            color: #1e550c;
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.28);
        }
        .btn-cta-secondary {
            background: rgba(255,255,255,0.15);
            color: #ffffff;
            border: 1.5px solid rgba(255,255,255,0.35);
            border-radius: 12px;
            padding: 15px 32px;
            font-size: 15.5px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 10px;
            transition: all 0.25s;
        }
        .btn-cta-secondary:hover {
            background: rgba(255,255,255,0.25);
            color: #ffffff;
            border-color: #ffffff;
        }

        /* ── STATS BAR ───────────────────────────── */
        .stats-bar {
            background: #084a0b;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            padding: 32px 20px;
        }
        .stat-item { text-align: center; }
        .stat-number {
            font-size: 32px;
            font-weight: 900;
            color: #ffffff;
            line-height: 1;
            letter-spacing: -1px;
        }
        .stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 4px;
            font-weight: 500;
        }
        .stat-divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.2);
            align-self: stretch;
            margin: 0 10px;
        }

        /* ── SECTIONS ────────────────────────────── */
        .section {
            padding: 96px 20px;
        }
        .section-dark { background: #0a5c0e; }
        .section-mid  { background: #084a0b; }

        .section-eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #facc15;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 16px;
            line-height: 1.15;
            background: linear-gradient(180deg, #ffffff 30%, rgba(255, 255, 255, 0.78) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 3px 10px rgba(0, 0, 0, 0.3));
        }
        .section-desc {
            font-size: 15.5px;
            color: rgba(255, 255, 255, 0.88);
            max-width: 560px;
            line-height: 1.7;
        }

        /* ── FEATURE CARDS ───────────────────────── */
        .feature-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 18px;
            padding: 32px 28px;
            height: 100%;
            transition: all 0.3s;
        }
        .feature-card:hover {
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        }
        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            color: #fff;
            margin-bottom: 20px;
        }
        .feature-title {
            font-size: 17px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .feature-desc {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.65;
        }

        /* ── ROLE CARDS ──────────────────────────── */
        .role-card {
            border-radius: 20px;
            padding: 40px 36px;
            height: 100%;
        }
        .role-card-admin {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.14) 0%, rgba(8, 74, 11, 0.95) 100%);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
        }
        .role-card-vocero {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.14) 0%, rgba(8, 74, 11, 0.95) 100%);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .role-badge-admin {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }
        .role-badge-vocero {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }
        .role-title {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 14px;
            letter-spacing: -0.4px;
        }
        .role-desc {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .role-perms {
            list-style: none;
            padding: 0; margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .role-perms li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: #ffffff;
        }
        .role-perms li i {
            font-size: 12px;
            width: 20px; height: 20px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* ── HOW IT WORKS ────────────────────────── */
        .step-card {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .step-number {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: #ffffff;
            color: #109116;
            font-size: 18px;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .step-title {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .step-desc {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.65;
        }

        /* ── CTA FINAL ───────────────────────────── */
        .cta-section {
            background:
                radial-gradient(ellipse 70% 80% at 50% 100%, rgba(255, 255, 255, 0.15) 0%, transparent 70%),
                #084a0b;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            padding: 96px 20px;
            text-align: center;
        }
        .cta-title {
            font-size: clamp(30px, 5vw, 52px);
            font-weight: 900;
            letter-spacing: -1.5px;
            margin-bottom: 18px;
            background: linear-gradient(180deg, #ffffff 30%, rgba(255, 255, 255, 0.78) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 14px rgba(0, 0, 0, 0.35));
        }
        .cta-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.88);
            max-width: 480px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        /* ── FOOTER ──────────────────────────────── */
        .landing-footer {
            background: #063808;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            padding: 28px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
        }
        .footer-text span { color: #ffffff; }

        /* ── UTILITIES ───────────────────────────── */
        .icon-bg-green  { background: #ffffff; color: #109116 !important; }
        .icon-bg-blue   { background: linear-gradient(135deg, #60a5fa, #3b82f6); }
        .icon-bg-purple { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
        .icon-bg-orange { background: linear-gradient(135deg, #fb923c, #ea580c); }
        .icon-bg-cyan   { background: linear-gradient(135deg, #22d3ee, #0891b2); }
        .icon-bg-pink   { background: linear-gradient(135deg, #f472b6, #db2777); }

        .check-green { background: rgba(255, 255, 255, 0.2); color: #ffffff; }
        .check-blue  { background: rgba(59,130,246,0.15); color: #60a5fa; }
    </style>
</head>
<body>

    <!-- ──────────────── NAVBAR ──────────────── -->
    <nav class="landing-nav">
        <a href="/inicio" class="nav-brand">
            <div class="nav-brand-icon">
                <img src="{{ asset('images/logo.png') }}" alt="Guardianes Ambientales">
            </div>
            <span class="nav-brand-text">Gesti<span>Limpieza</span></span>
        </a>
        <div class="d-flex align-items-center gap-2">
            @auth
                <a href="{{ Auth::user()->rol_id == 1 ? '/admin' : '/vocero' }}" class="btn-nav-login">
                    <i class="fas fa-gauge me-1"></i> Ir a mi Panel ({{ Auth::user()->rol_id == 1 ? 'Admin' : 'Vocero' }})
                </a>
                <form method="POST" action="/logout" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; padding: 7px 12px; font-weight: 600;" title="Cerrar sesión">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            @else
                <a href="/login" class="btn-nav-login">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            @endauth
        </div>
    </nav>

    <!-- ──────────────── HERO ──────────────── -->
    <section class="hero-section">
        <div style="position: relative; z-index: 1; max-width: 760px;">
            <div class="hero-badge">
                <i class="fas fa-circle-dot"></i>
                Sistema oficial SENA SICEFA — Gestión de Limpieza
            </div>
            <h1 class="hero-title">
                <span class="title-fade">El orden comienza</span><br>
                <span class="title-fade">con </span><span class="highlight">GestiLimpieza</span>
            </h1>
            <p class="hero-subtitle">
                Plataforma digital para gestionar turnos de limpieza, asignar aprendices,
                registrar evidencias fotográficas y mantener el control total de la higiene
                en las instalaciones del SENA.
            </p>
            <div class="hero-cta-group">
                @auth
                    <a href="{{ Auth::user()->rol_id == 1 ? '/admin' : '/vocero' }}" class="btn-cta-primary">
                        <i class="fas fa-gauge"></i> Ir a mi Panel
                    </a>
                @else
                    <a href="/login" class="btn-cta-primary">
                        <i class="fas fa-sign-in-alt"></i> Ingresar al Sistema
                    </a>
                @endauth
                <a href="#caracteristicas" class="btn-cta-secondary">
                    <i class="fas fa-arrow-down"></i> Ver más
                </a>
            </div>
        </div>
    </section>

    <!-- ──────────────── STATS ──────────────── -->
    <div class="stats-bar">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Digitalizado</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">2</div>
                        <div class="stat-label">Roles Activos</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">SENA</div>
                        <div class="stat-label">Centro de Formación</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">SICEFA</div>
                        <div class="stat-label">Sede Oficial</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ──────────────── CARACTERÍSTICAS ──────────────── -->
    <section class="section section-dark" id="caracteristicas">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-eyebrow">Funcionalidades Principales</div>
                <h2 class="section-title">Todo lo que necesitas para el control de limpieza</h2>
                <p class="section-desc mx-auto">
                    GestiLimpieza automatiza el proceso completo: desde la conformación de grupos hasta el reporte y verificación fotográfica.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon icon-bg-green"><i class="fas fa-users-rectangle"></i></div>
                        <div class="feature-title">Gestión de Grupos</div>
                        <div class="feature-desc">Crea y administra grupos de aprendices organizados por ficha técnica con capacidades máximas por módulo asignado.</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon icon-bg-blue"><i class="fas fa-camera"></i></div>
                        <div class="feature-title">Evidencias Fotográficas</div>
                        <div class="feature-desc">Registro visual obligatorio del antes y después de cada turno de limpieza para certificar el estado real del ambiente.</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon icon-bg-purple"><i class="fas fa-calendar-check"></i></div>
                        <div class="feature-title">Calendario Visual</div>
                        <div class="feature-desc">Semáforo interactivo por fecha: verde para entregado, rojo para vencido y gris para limpiezas futuras en espera.</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon icon-bg-orange"><i class="fas fa-cubes"></i></div>
                        <div class="feature-title">Control de Módulos</div>
                        <div class="feature-desc">Asignación estructurada de áreas y módulos del centro con especificación de capacidad máxima de aprendices.</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon icon-bg-cyan"><i class="fas fa-bell"></i></div>
                        <div class="feature-title">Notificaciones al Instante</div>
                        <div class="feature-desc">Alertas automáticas en tiempo real sobre subida de evidencias, asignación de turnos y fechas límite de cumplimiento.</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon icon-bg-pink"><i class="fas fa-chart-line"></i></div>
                        <div class="feature-title">Reportes Trimestrales</div>
                        <div class="feature-desc">Generación de informes de cumplimiento trimestral y exportación en CSV para trazabilidad institucional de la sede.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ──────────────── ROLES ──────────────── -->
    <section class="section section-mid">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-eyebrow">Roles del Sistema</div>
                <h2 class="section-title">Diseñado para Administradores y Voceros</h2>
                <p class="section-desc mx-auto">
                    Cada usuario accede a un panel optimizado para sus funciones y responsabilidades dentro del centro de formación.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Administrador -->
                <div class="col-lg-5 col-md-6">
                    <div class="role-card role-card-admin">
                        <div class="role-badge role-badge-admin">
                            <i class="fas fa-shield-halved"></i> Rol Administrador
                        </div>
                        <div class="role-title">Coordinación Total</div>
                        <div class="role-desc">
                            Supervisión general del centro de formación, configuración de fichas, programas, módulos y aprobación de turnos de limpieza.
                        </div>
                        <ul class="role-perms">
                            <li><i class="fas fa-check check-green"></i> Configuración de programas y fichas</li>
                            <li><i class="fas fa-check check-green"></i> Asignación y gestión de módulos de limpieza</li>
                            <li><i class="fas fa-check check-green"></i> Creación y reactivación de cuentas de vocero</li>
                            <li><i class="fas fa-check check-green"></i> Revisión y descarga de evidencias de fichas</li>
                            <li><i class="fas fa-check check-green"></i> Generación de reportes trimestrales y CSV</li>
                        </ul>
                    </div>
                </div>

                <!-- Vocero -->
                <div class="col-lg-5 col-md-6">
                    <div class="role-card role-card-vocero">
                        <div class="role-badge role-badge-vocero">
                            <i class="fas fa-bullhorn"></i> Rol Vocero de Ficha
                        </div>
                        <div class="role-title">Liderazgo de Ficha</div>
                        <div class="role-desc">
                            Responsable de coordinar a los aprendices de su ficha, organizar los grupos de trabajo y certificar la entrega de limpieza.
                        </div>
                        <ul class="role-perms">
                            <li><i class="fas fa-check check-green"></i> Creación de grupos de aprendices para la ficha</li>
                            <li><i class="fas fa-check check-green"></i> Registro fotográfico del turno (Antes y Después)</li>
                            <li><i class="fas fa-check check-green"></i> Consulta de calendario de fechas asignadas</li>
                            <li><i class="fas fa-check check-green"></i> Visualización de notificaciones del sistema</li>
                            <li><i class="fas fa-check check-green"></i> Galería histórica de evidencias subidas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ──────────────── PASO A PASO ──────────────── -->
    <section class="section section-dark">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-eyebrow">Flujo de Trabajo</div>
                <h2 class="section-title">¿Cómo funciona el proceso de limpieza?</h2>
                <p class="section-desc mx-auto">
                    Un proceso claro en 3 sencillos pasos para asegurar la higiene constante en los módulos del SENA SICEFA.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <div>
                                <div class="step-title">Asignación de Módulos</div>
                                <div class="step-desc">El administrador vincula a cada ficha con sus módulos respectivos respetando los días y capacidades autorizadas.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="step-card">
                            <div class="step-number">2</div>
                            <div>
                                <div class="step-title">Conformación de Grupos</div>
                                <div class="step-desc">El vocero organiza a sus compañeros de ficha en grupos de limpieza equilibrados y los asigna al turno correspondiente.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="step-card">
                            <div class="step-number">3</div>
                            <div>
                                <div class="step-title">Subida de Evidencias</div>
                                <div class="step-desc">Se suben las fotografías antes y después del aseo para validar el cumplimiento directamente en el calendario.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ──────────────── CTA FINAL ──────────────── -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">¿Listo para comenzar?</h2>
            <p class="cta-subtitle">
                Accede a tu cuenta institucional con tus credenciales de Administrador o Vocero de ficha.
            </p>
            @auth
                <a href="{{ Auth::user()->rol_id == 1 ? '/admin' : '/vocero' }}" class="btn-cta-primary">
                    <i class="fas fa-gauge"></i> Entrar a mi Panel
                </a>
            @else
                <a href="/login" class="btn-cta-primary">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión Ahora
                </a>
            @endauth
        </div>
    </section>

    <!-- ──────────────── FOOTER ──────────────── -->
    <footer class="landing-footer">
        <div class="d-flex align-items-center gap-2">
            <div style="width: 32px; height: 32px; background: #ffffff; border-radius: 50%; padding: 2px; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                <img src="{{ asset('images/logo.png') }}" alt="Guardianes Ambientales" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <span class="fw-bold text-white">GestiLimpieza</span>
            <span class="footer-text ms-2">• Guardianes Ambientales de La Angostura</span>
        </div>
        <div class="footer-text">
            © {{ date('Y') }} SENA — Centro de Formación La Angostura. Todos los derechos reservados.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
