@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<style>
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
        border-radius: 0 0.375rem 0.375rem 0;
    }
    .btn-toggle-pass:hover { color: #109116; }
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-key"></i> Cambiar Contraseña</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/cambiar-password">
                        @csrf

                        {{-- Contraseña actual --}}
                        <div class="mb-3">
                            <label for="password_actual" class="form-label">Contraseña Actual</label>
                            <div class="password-wrapper">
                                <input type="password" id="password_actual" name="password_actual"
                                    class="form-control @error('password_actual') is-invalid @enderror" required>
                                <button type="button" class="btn-toggle-pass"
                                    onclick="togglePass('password_actual','ico-pass-actual')"
                                    tabindex="-1" aria-label="Mostrar contraseña actual">
                                    <i class="fas fa-eye" id="ico-pass-actual"></i>
                                </button>
                            </div>
                            @error('password_actual')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nueva contraseña --}}
                        <div class="mb-3">
                            <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                            <div class="password-wrapper">
                                <input type="password" id="password_nueva" name="password_nueva"
                                    class="form-control @error('password_nueva') is-invalid @enderror" required>
                                <button type="button" class="btn-toggle-pass"
                                    onclick="togglePass('password_nueva','ico-pass-nueva')"
                                    tabindex="-1" aria-label="Mostrar nueva contraseña">
                                    <i class="fas fa-eye" id="ico-pass-nueva"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                Requisitos:
                                <ul class="mb-0 mt-2">
                                    <li>Mínimo 8 caracteres</li>
                                    <li>Al menos una mayúscula</li>
                                    <li>Al menos un número</li>
                                    <li>Al menos un carácter especial (!@#$%^&*)</li>
                                </ul>
                            </small>
                            @error('password_nueva')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirmar contraseña --}}
                        <div class="mb-3">
                            <label for="password_nueva_confirmation" class="form-label">Confirmar Contraseña</label>
                            <div class="password-wrapper">
                                <input type="password" id="password_nueva_confirmation" name="password_nueva_confirmation"
                                    class="form-control" required>
                                <button type="button" class="btn-toggle-pass"
                                    onclick="togglePass('password_nueva_confirmation','ico-pass-confirm')"
                                    tabindex="-1" aria-label="Confirmar contraseña">
                                    <i class="fas fa-eye" id="ico-pass-confirm"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
</script>
@endsection
