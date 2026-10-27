<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'GestiLimpieza - SENA')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            /* SENA Dark Forest Green Theme */
            --bg-sidebar: linear-gradient(180deg, #109116 0%, #0a5c0e 100%);
            --bg-sidebar-dark: #0a5c0e;
            --bg-topbar: #0a5c0e;
            --bg-body: #f2f5f2;
            --brand-green: #109116;
            --brand-green-hover: #0d7712;
            --brand-green-dark: #084d0b;
            --brand-green-subtle: rgba(16, 145, 22, 0.14);
            --brand-green-border: rgba(16, 145, 22, 0.28);
            --sidebar-text-muted: rgba(255, 255, 255, 0.72);
            --sidebar-link: rgba(255, 255, 255, 0.92);
            --sidebar-link-hover: #ffffff;
            --card-border: #e3e8e2;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            z-index: 1020;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.04);
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .sidebar-brand-wrapper {
            padding: 22px 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            width: 44px;
            height: 44px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .sidebar-brand-info {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
        }

        .sidebar-brand-title {
            color: #ffffff;
            font-size: 16.5px;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        .sidebar-brand-subtitle {
            color: #facc15;
            font-size: 11px;
            font-weight: 700;
            opacity: 0.95;
            letter-spacing: 0.3px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 10px 0 30px;
            margin: 0;
            flex: 1;
        }

        .sidebar-category {
            padding: 20px 20px 6px;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: var(--sidebar-text-muted);
            text-transform: uppercase;
        }

        .sidebar-item {
            margin: 2px 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9.5px 14px;
            border-radius: 9px;
            color: var(--sidebar-link);
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.18s ease-in-out;
            border: 1px solid transparent;
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 14.5px;
            opacity: 0.9;
            transition: transform 0.18s ease;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-link.active i {
            color: #ffffff;
        }

        /* ===== MAIN CONTENT AREA ===== */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--bg-body);
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            height: 68px;
            background: var(--bg-topbar);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 1010;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .top-navbar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Bell action button */
        .btn-bell-action {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            position: relative;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .btn-bell-action:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        .btn-bell-action .indicator-dot {
            width: 8px;
            height: 8px;
            background: #14b51c;
            border-radius: 50%;
            position: absolute;
            top: 8px;
            right: 9px;
            border: 1.5px solid var(--bg-topbar);
        }

        /* User badge */
        .top-user-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .top-user-info {
            display: flex;
            flex-direction: column;
            text-align: right;
            line-height: 1.25;
        }

        .top-user-name {
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 700;
        }

        .top-user-role {
            color: #8da88c;
            font-size: 11.5px;
            font-weight: 500;
        }

        .top-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #109116;
            font-size: 15px;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }

        /* Logout button */
        .btn-logout-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border: 1.2px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            background: rgba(239, 68, 68, 0.9);
            padding: 5.5px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-logout-pill:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #ffffff;
        }

        /* ===== CONTENT CANVAS ===== */
        .content-container {
            padding: 30px 36px 48px;
            flex: 1;
        }

        /* Page Title Header */
        .page-header-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 2px;
            letter-spacing: -0.3px;
        }

        .page-header-icon {
            color: #109116;
            font-size: 22px;
        }

        .page-header-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 24px;
        }

        /* ===== DESIGN SYSTEM UTILITIES ===== */
        .card-custom {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--card-border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 6px 18px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .card-custom-header {
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-custom-title {
            font-size: 15.5px;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 0;
        }

        .card-custom-body {
            padding: 24px;
        }

        /* Modern Outline and Solid Green Buttons */
        .btn-brand-outline {
            border: 1.5px solid #109116;
            color: #109116;
            background: #ffffff;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            padding: 5.5px 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.18s ease;
        }

        .btn-brand-outline:hover {
            background: #109116;
            color: #ffffff;
            border-color: #109116;
        }

        .btn-brand-solid {
            background: #109116;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 7px 18px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.18s ease;
            box-shadow: 0 2px 8px rgba(16, 145, 22, 0.25);
        }

        .btn-brand-solid:hover {
            background: #0d7712;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 145, 22, 0.35);
        }

        /* Clean Tables */
        .table-clean {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-clean th {
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            background: #fafcfa;
            border-bottom: 1px solid #edf2ed;
        }

        .table-clean td {
            padding: 14px 20px;
            font-size: 13.5px;
            color: #334155;
            vertical-align: middle;
            border-bottom: 1px solid #f3f6f3;
        }

        .table-clean tbody tr:last-child td {
            border-bottom: none;
        }

        .table-clean tbody tr:hover td {
            background-color: #f9fbf9;
        }

        /* Modern Alerts Styling */
        .alert {
            border-radius: 12px;
            border: 1px solid transparent;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            font-size: 13.5px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #084d0b;
        }
        .alert-danger {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }
        .alert-warning {
            background-color: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }
        .alert-info {
            background-color: #f0f9ff;
            border-color: #bae6fd;
            color: #075985;
        }
        .alert-dismissible .btn-close {
            padding: 1.15rem;
        }

        /* SweetAlert2 SENA Theme */
        .swal2-container {
            backdrop-filter: blur(3px);
            z-index: 99999 !important;
        }
        .swal2-popup.sena-swal-popup {
            border-radius: 18px !important;
            padding: 24px 22px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            box-shadow: 0 20px 40px -10px rgba(7, 34, 8, 0.22) !important;
            border: 1px solid #e2e8f0 !important;
        }
        .sena-swal-popup .swal2-title {
            font-size: 19px !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            letter-spacing: -0.3px !important;
            margin-bottom: 6px !important;
        }
        .sena-swal-popup .swal2-actions {
            gap: 10px !important;
            margin-top: 18px !important;
            width: 100% !important;
            justify-content: center !important;
        }
        .sena-swal-confirm-btn {
            border-radius: 8px !important;
            padding: 9px 20px !important;
            font-weight: 700 !important;
            font-size: 13.5px !important;
            border: none !important;
            transition: all 0.2s ease !important;
        }
        .sena-swal-confirm-btn:hover {
            transform: translateY(-1px) !important;
            opacity: 0.95;
        }
        .sena-swal-cancel-btn {
            border-radius: 8px !important;
            padding: 9px 18px !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
            background-color: #f1f5f9 !important;
            color: #64748b !important;
            border: 1px solid #e2e8f0 !important;
            transition: all 0.2s ease !important;
        }
        .sena-swal-cancel-btn:hover {
            background-color: #e2e8f0 !important;
            color: #334155 !important;
        }
        .swal2-popup.sena-toast-popup {
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12) !important;
            border: 1px solid #e2e8f0 !important;
            z-index: 99999 !important;
        }
        .swal2-popup.sena-toast-popup .swal2-title {
            font-size: 13.5px !important;
            font-weight: 600 !important;
            color: #0f172a !important;
        }

        /* Bootstrap default overrides to maintain SENA palette across all views */
        .card {
            border-radius: 16px;
            border: 1px solid var(--card-border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 14px rgba(0, 0, 0, 0.02);
            background: #ffffff;
            margin-bottom: 24px;
        }
        .card-header {
            background-color: #fafcfa;
            border-bottom: 1px solid #edf2ed;
            padding: 16px 22px;
            font-weight: 700;
            color: var(--text-dark);
        }
        .table {
            --bs-table-bg: transparent;
            margin-bottom: 0;
        }
        .table thead th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            background: #fafcfa;
            border-bottom: 1px solid #edf2ed;
            padding: 12px 20px;
        }
        .table tbody td {
            padding: 14px 20px;
            vertical-align: middle;
            font-size: 13.5px;
            color: #334155;
            border-bottom: 1px solid #f3f6f3;
        }
        .btn-primary {
            background-color: #109116;
            border-color: #109116;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #0d7712;
            border-color: #0d7712;
        }
        .btn-outline-primary {
            color: #109116;
            border-color: #109116;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-outline-primary:hover {
            background-color: #109116;
            border-color: #109116;
            color: #ffffff;
        }
        
        .text-success {
            color: #109116 !important;
        }
        .bg-success {
            background-color: #109116 !important;
        }
        .btn-success:hover, .btn-success:focus, .btn-success:active {
            background-color: #0d7712 !important;
            border-color: #0d7712 !important;
            color: #ffffff !important;
        }
        .btn-success {
            background-color: #109116;
            border-color: #109116;
            border-radius: 8px;
            font-weight: 600;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e2e8f0;
            font-size: 13.5px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #14b51c;
            box-shadow: 0 0 0 3px rgba(16, 145, 22, 0.18);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .content-container {
                padding: 20px 16px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    @if(Auth::check())
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <a href="{{ Auth::user()->rol_id == 1 ? '/admin' : '/vocero' }}" class="sidebar-brand-wrapper">
            <div class="sidebar-logo-icon">
                <img src="{{ asset('images/logo.png') }}" alt="Guardianes Ambientales">
            </div>
            <div class="sidebar-brand-info">
                <span class="sidebar-brand-title">GestiLimpieza</span>
                <span class="sidebar-brand-subtitle">Guardianes Ambientales</span>
            </div>
        </a>

        <ul class="sidebar-nav">
            @if(Auth::user()->rol_id == 1)
                <!-- ================= ADMIN NAVIGATION ================= -->
                <li class="sidebar-category">Principal</li>
                <li class="sidebar-item">
                    <a href="/admin" class="sidebar-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="fas fa-border-all"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-category">Gestión Académica</li>
                <li class="sidebar-item">
                    <a href="/admin/programas" class="sidebar-link {{ request()->is('admin/programas*') || request()->is('admin/fichas*') || request()->is('admin/programa*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        <span>Programas y Fichas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/admin/aprendices" class="sidebar-link {{ request()->is('admin/aprendices*') || request()->is('admin/voceros*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>Aprendices y Voceros</span>
                    </a>
                </li>

                <li class="sidebar-category">Limpieza</li>
                <li class="sidebar-item">
                    <a href="/admin/modulos" class="sidebar-link {{ request()->is('admin/modulos*') ? 'active' : '' }}">
                        <i class="fas fa-door-open"></i>
                        <span>Módulos y Asignaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/admin/evidencias" class="sidebar-link {{ request()->is('admin/evidencias*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i>
                        <span>Evidencias</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/admin/grupos" class="sidebar-link {{ request()->is('admin/grupos*') ? 'active' : '' }}">
                        <i class="fas fa-users-gear"></i>
                        <span>Grupos</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/admin/reportes" class="sidebar-link {{ request()->is('admin/reportes*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Reportes</span>
                    </a>
                </li>

                <li class="sidebar-category">Sistema</li>
                <li class="sidebar-item">
                    <a href="/admin/notificaciones" class="sidebar-link {{ request()->is('admin/notificaciones*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notificaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/admin/sincronizacion" class="sidebar-link {{ request()->is('admin/sincronizacion*') ? 'active' : '' }}">
                        <i class="fas fa-sync-alt"></i>
                        <span>Sincronización SICEFA</span>
                    </a>
                </li>
            @else
                <!-- ================= VOCERO NAVIGATION ================= -->
                <li class="sidebar-category">Principal</li>
                <li class="sidebar-item">
                    <a href="/vocero" class="sidebar-link {{ request()->is('vocero') ? 'active' : '' }}">
                        <i class="fas fa-border-all"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-category">Gestión Académica</li>
                <li class="sidebar-item">
                    <a href="/vocero/grupos" class="sidebar-link {{ request()->is('vocero/grupos*') ? 'active' : '' }}">
                        <i class="fas fa-users-gear"></i>
                        <span>Grupos de Limpieza</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/vocero/aprendices" class="sidebar-link {{ request()->is('vocero/aprendices*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>Aprendices de Ficha</span>
                    </a>
                </li>

                <li class="sidebar-category">Limpieza</li>
                <li class="sidebar-item">
                    <a href="/vocero/subir-evidencia" class="sidebar-link {{ request()->is('vocero/subir-evidencia*') ? 'active' : '' }}">
                        <i class="fas fa-camera"></i>
                        <span>Subir Evidencias</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/vocero/evidencias" class="sidebar-link {{ request()->is('vocero/evidencias*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i>
                        <span>Mis Evidencias</span>
                    </a>
                </li>

                <li class="sidebar-category">Sistema</li>
                <li class="sidebar-item">
                    <a href="/vocero/notificaciones" class="sidebar-link {{ request()->is('vocero/notificaciones*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notificaciones</span>
                    </a>
                </li>
            @endif

            <li class="sidebar-category">Sitio</li>
            <li class="sidebar-item">
                <a href="/inicio" class="sidebar-link" title="Ver página informativa del proyecto">
                    <i class="fas fa-globe"></i>
                    <span>Página de Inicio</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="main-wrapper">
        <!-- TOP NAVBAR -->
        <header class="top-navbar">
            <div class="top-navbar-actions">
                <!-- Notifications Bell -->
                <a href="{{ Auth::user()->rol_id == 1 ? '/admin/notificaciones' : '/vocero/notificaciones' }}" class="btn-bell-action" title="Notificaciones">
                    <i class="fas fa-bell"></i>
                    @php
                        $unreadNotifs = Auth::user()->notificaciones()->where('leida', false)->count();
                    @endphp
                    @if($unreadNotifs > 0)
                        <span class="indicator-dot"></span>
                    @endif
                </a>

                <!-- User profile badge -->
                <div class="top-user-badge">
                    <div class="top-user-info">
                        <span class="top-user-name">{{ Auth::user()->nombres }} {{ Auth::user()->apellidos ?? '' }}</span>
                        <span class="top-user-role">{{ Auth::user()->rol_id == 1 ? 'Administrador' : 'Vocero' }}</span>
                    </div>
                    <div class="top-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="/logout" class="m-0 p-0 d-inline">
                    @csrf
                    <button type="submit" class="btn-logout-pill">
                        <i class="fas fa-power-off"></i>
                        <span>Salir</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- CONTENT CONTAINER -->
        <main class="content-container">
            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Por favor verifica los siguientes errores:</strong>
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @else
        @yield('content')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 Toasts for Flash Messages
        const ToastSena = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'sena-toast-popup'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        @if(session('success'))
            ToastSena.fire({
                icon: 'success',
                title: @json(session('success'))
            });
        @endif

        @if(session('error'))
            ToastSena.fire({
                icon: 'error',
                title: @json(session('error'))
            });
        @endif

        @if(session('warning'))
            ToastSena.fire({
                icon: 'warning',
                title: @json(session('warning'))
            });
        @endif

        @if(session('info'))
            ToastSena.fire({
                icon: 'info',
                title: @json(session('info'))
            });
        @endif

        // Global Confirmation Interceptor for forms with data-confirm
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form && form.hasAttribute('data-confirm')) {
                e.preventDefault();
                const msg = form.getAttribute('data-confirm');
                const title = form.getAttribute('data-confirm-title') || '¿Confirmar Acción?';
                const icon = form.getAttribute('data-confirm-icon') || 'question';
                const confirmBtn = form.getAttribute('data-confirm-btn') || 'Sí, continuar';
                const confirmColor = form.getAttribute('data-confirm-color') || '#109116';

                Swal.fire({
                    title: title,
                    html: `<p style="font-size: 14.5px; color: #475569; margin: 0; line-height: 1.55;">${msg}</p>`,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: confirmBtn,
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        popup: 'sena-swal-popup',
                        confirmButton: 'sena-swal-confirm-btn',
                        cancelButton: 'sena-swal-cancel-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.removeAttribute('data-confirm');
                        form.submit();
                    }
                });
            }
        });

        // Prevenir que el navegador muestre páginas congeladas en caché (bfcache) al retroceder tras cerrar sesión
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                document.body.style.display = 'none';
                window.location.reload();
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
