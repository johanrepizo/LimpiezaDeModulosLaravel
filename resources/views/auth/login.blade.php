<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Iniciar Sesión - GestiLimpieza</title>
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
        body {
            background: radial-gradient(circle at top right, #14b51c 0%, #109116 50%, #0a5c0e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 420px;
            padding: 40px 36px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-icon-box {
            width: 80px;
            height: 80px;
            background: #ffffff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            margin-bottom: 16px;
            overflow: hidden;
            border: 2px solid rgba(16, 145, 22, 0.15);
        }

        .brand-icon-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .login-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.4px;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 13.5px;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 10px;
            padding: 11px 16px;
            font-size: 14px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #14b51c;
            box-shadow: 0 0 0 3px rgba(16, 145, 22, 0.18);
        }

        .btn-submit-login {
            background: #109116;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            font-size: 14.5px;
            width: 100%;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(16, 145, 22, 0.3);
            transition: all 0.2s ease;
        }

        .btn-submit-login:hover {
            background: #0d7712;
            box-shadow: 0 6px 18px rgba(16, 145, 22, 0.4);
            transform: translateY(-1px);
        }

        .form-check-input:checked {
            background-color: #109116;
            border-color: #109116;
        }

        /* ── Toggle contraseña ─── */
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .form-control {
            padding-right: 46px;
        }
        .btn-toggle-pass {
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 42px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: color 0.2s;
            border-radius: 0 10px 10px 0;
        }
        .btn-toggle-pass:hover { color: #109116; }

        .btn-back-home {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .btn-back-home:hover {
            color: #109116;
            background: #f0fdf4;
            border-color: #bbf7d0;
            transform: translateX(-2px);
            box-shadow: 0 2px 8px rgba(16, 145, 22, 0.12);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <a href="/inicio" style="text-decoration: none; color: inherit; display: block;" class="text-center" title="Ir a la página de inicio">
            <div class="brand-icon-box">
                <img src="{{ asset('images/logo.png') }}" alt="Guardianes Ambientales">
            </div>
            <h1 class="login-title">GestiLimpieza</h1>
            <p class="login-subtitle">Sistema de Control y Asignación de Limpieza SENA</p>
        </a>

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 mb-3" style="border-radius: 10px; font-size: 13px;">
                @foreach($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger py-2 px-3 mb-3" style="border-radius: 10px; font-size: 13px;">
                <i class="fas fa-times-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 mb-3" style="border-radius: 10px; font-size: 13px;">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" required value="{{ old('correo') }}" placeholder="ejemplo@sena.edu.co">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Ingresa tu contraseña" autocomplete="current-password">
                    <button type="button" class="btn-toggle-pass" onclick="togglePass('password','ico-pass-login')" tabindex="-1" aria-label="Mostrar contraseña">
                        <i class="fas fa-eye" id="ico-pass-login"></i>
                    </button>
                </div>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                <label class="form-check-label" for="remember" style="font-size: 13px; color: #64748b;">Recordar mi sesión</label>
            </div>

            <button type="submit" class="btn btn-submit-login">
                <i class="fas fa-sign-in-alt"></i> Ingresar al Sistema
            </button>
        </form>

        <div class="text-center mt-4 pt-2">
            <a href="/inicio" class="btn-back-home" id="btnVolverInicio">
                <i class="fas fa-arrow-left"></i> Volver a la página de inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Si el usuario intenta retroceder con el navegador en la pantalla de login
        if (window.history && window.history.forward) {
            window.history.forward();
        }
    </script>
</body>
</html>
