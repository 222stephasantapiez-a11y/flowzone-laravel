<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowZone — Crear cuenta</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: var(--green-900);
            display: flex;
            min-height: 100vh;
        }

        /* ── Panel izquierdo ── */
        .auth-panel-left {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }

        .auth-panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=900&q=80') center/cover no-repeat;
            opacity: .3;
        }

        .auth-panel-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(27,67,50,.55) 0%, rgba(27,67,50,.88) 100%);
        }

        .auth-brand {
            position: relative;
            z-index: 2;
        }

        .auth-brand h1 {
            font-family: var(--font-display);
            font-size: 3rem;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
            margin-bottom: .5rem;
        }

        .auth-brand h1 span { color: var(--green-200); }

        .auth-brand p {
            color: rgba(255,255,255,.55);
            font-size: .95rem;
            font-weight: 300;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        /* ── Panel derecho ── */
        .auth-panel-right {
            width: 520px;
            flex-shrink: 0;
            background: var(--gray-50);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.5rem 3rem;
            overflow-y: auto;
        }

        .auth-heading { margin-bottom: 2rem; }

        .auth-eyebrow {
            font-size: .75rem;
            font-weight: 500;
            color: var(--green-700);
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: .4rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .auth-eyebrow::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 2px;
            background: var(--green-700);
        }

        .auth-heading h2 {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1.2;
        }

        .auth-heading h2 em {
            font-style: normal;
            color: var(--green-700);
        }

        /* Tabs de tipo */
        .auth-tabs {
            display: flex;
            gap: .5rem;
            margin-bottom: 1.5rem;
            background: var(--gray-200);
            border-radius: var(--radius-md);
            padding: 4px;
        }

        .auth-tab {
            flex: 1;
            padding: .65rem .5rem;
            border: none;
            border-radius: var(--radius-sm);
            background: transparent;
            color: var(--gray-600);
            font-family: var(--font-body);
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
        }

        .auth-tab.active {
            background: var(--white);
            color: var(--green-800);
            box-shadow: var(--shadow-sm);
        }

        /* Campos */
        .auth-field { margin-bottom: 1rem; }

        .auth-field label {
            display: block;
            font-size: .75rem;
            font-weight: 500;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .4rem;
        }

        .auth-field-wrap { position: relative; }

        .auth-field-wrap .auth-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: .95rem;
            pointer-events: none;
            color: var(--gray-400);
        }

        .auth-field input {
            width: 100%;
            padding: .8rem 1rem .8rem 2.6rem;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: .92rem;
            color: var(--gray-900);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .auth-field input:focus {
            border-color: var(--green-700);
            box-shadow: 0 0 0 3px rgba(64,145,108,.12);
        }

        .auth-field input::placeholder { color: var(--gray-400); }

        .auth-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .8rem;
        }

        /* Sección empresa */
        .auth-empresa-section {
            border-top: 1.5px solid var(--gray-200);
            padding-top: 1.2rem;
            margin-top: .5rem;
            display: none;
        }

        .auth-empresa-section.visible { display: block; }

        .auth-section-title {
            font-size: .75rem;
            font-weight: 500;
            color: var(--green-700);
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: 1rem;
        }

        /* Alertas */
        .auth-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid var(--danger);
            color: var(--danger);
            padding: .75rem 1rem;
            border-radius: var(--radius-md);
            font-size: .85rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .auth-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 3px solid var(--success);
            color: var(--success);
            padding: 1rem;
            border-radius: var(--radius-md);
            font-size: .9rem;
            margin-bottom: 1.2rem;
        }

        /* Botón */
        .auth-submit {
            width: 100%;
            padding: .95rem;
            background: var(--green-800);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            letter-spacing: .03em;
            margin-top: .8rem;
        }

        .auth-submit:hover {
            background: var(--green-700);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45,106,79,.3);
        }

        .auth-links {
            text-align: center;
            margin-top: 1.2rem;
            font-size: .85rem;
        }

        .auth-links a {
            color: var(--green-700);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            .auth-panel-left { display: none; }
            .auth-panel-right { width: 100%; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

{{-- Panel izquierdo --}}
<div class="auth-panel-left">
    <div class="auth-brand">
        <span style="font-size:2.5rem;display:block;margin-bottom:.8rem;">
            <i class="fa-solid fa-mountain-sun" style="color:var(--green-200);"></i>
        </span>
        <h1>Flow<span>Zone</span></h1>
        <p>Turismo · Ortega, Tolima</p>
    </div>
</div>

{{-- Panel derecho --}}
<div class="auth-panel-right">
    <div class="auth-heading">
        <div class="auth-eyebrow">Únete a nosotros</div>
        <h2>Crea tu <em>cuenta</em><br>gratis</h2>
    </div>

    @if($errors->any())
        <div class="auth-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success') === 'usuario')
        <div class="auth-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>¡Registro exitoso!</strong> Ya puedes <a href="{{ route('login') }}" style="color:var(--green-800);font-weight:600;">iniciar sesión</a>.
        </div>
    @elseif(session('success') === 'empresa')
        <div class="auth-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>¡Empresa registrada!</strong> Tu cuenta está pendiente de aprobación.
        </div>
    @endif

    <div class="auth-tabs">
        <button type="button" class="auth-tab active" onclick="setTipo(this,'usuario')">
            <i class="fa-solid fa-user fa-xs"></i> Visitante
        </button>
        <button type="button" class="auth-tab" onclick="setTipo(this,'empresa')">
            <i class="fa-solid fa-building fa-xs"></i> Empresa
        </button>
    </div>

    <form method="POST" action="{{ url('/registro') }}">
        @csrf
        <input type="hidden" name="rol" id="campo-rol" value="{{ old('rol', 'usuario') }}">

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Nombre completo *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-user"></i>
                    <input type="text" name="nombre" required maxlength="100"
                           placeholder="Tu nombre" value="{{ old('nombre') }}">
                </div>
            </div>
            <div class="auth-field">
                <label>Teléfono</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-phone"></i>
                    <input type="tel" name="telefono" maxlength="20"
                           placeholder="3201234567" value="{{ old('telefono') }}">
                </div>
            </div>
        </div>

        <div class="auth-field">
            <label>Correo electrónico *</label>
            <div class="auth-field-wrap">
                <i class="auth-icon fa-solid fa-envelope"></i>
                <input type="email" name="correo" required maxlength="150"
                       placeholder="tu@correo.com" value="{{ old('correo') }}">
            </div>
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Contraseña *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-lock"></i>
                    <input type="password" name="password" required minlength="6" placeholder="Mín. 6 chars">
                </div>
            </div>
            <div class="auth-field">
                <label>Confirmar *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-lock"></i>
                    <input type="password" name="password_confirmation" required minlength="6" placeholder="Repetir">
                </div>
            </div>
        </div>

        <div class="auth-empresa-section {{ old('rol') === 'empresa' ? 'visible' : '' }}" id="sec-empresa">
            <div class="auth-section-title">
                <i class="fa-solid fa-building fa-xs"></i> Datos de la empresa
            </div>
            <div class="auth-field">
                <label>Nombre de la empresa *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-building"></i>
                    <input type="text" name="empresa_nombre" maxlength="200"
                           placeholder="Nombre legal" value="{{ old('empresa_nombre') }}">
                </div>
            </div>
            <div class="auth-field">
                <label>Dirección</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-location-dot"></i>
                    <input type="text" name="empresa_direccion" maxlength="400"
                           placeholder="Dirección" value="{{ old('empresa_direccion') }}">
                </div>
            </div>
        </div>

        <button type="submit" class="auth-submit">
            <i class="fa-solid fa-user-plus fa-xs"></i> Crear cuenta
        </button>
    </form>

    <div class="auth-links">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
        &nbsp;·&nbsp; <a href="{{ route('home') }}">← Inicio</a>
    </div>
</div>

<script>
function setTipo(btn, tipo) {
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('campo-rol').value = tipo;
    document.getElementById('sec-empresa').classList.toggle('visible', tipo === 'empresa');
}

// Restaurar estado si hay old input
if (document.getElementById('campo-rol').value === 'empresa') {
    document.querySelectorAll('.auth-tab')[1].classList.add('active');
    document.querySelectorAll('.auth-tab')[0].classList.remove('active');
    document.getElementById('sec-empresa').classList.add('visible');
}
</script>
</body>
</html>
