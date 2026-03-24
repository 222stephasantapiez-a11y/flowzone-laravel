<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowZone — Ingresar</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --verde:      #1e5c2d;
            --verde-med:  #2d7a3e;
            --verde-claro:#4a9d5f;
            --crema:      #f7f5f0;
            --oscuro:     #111a14;
            --gris:       #6b7a6e;
            --gris-lt:    #c8d4cb;
            --borde:      #dde5df;
        }

        html, body { height: 100%; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--oscuro);
            display: flex;
            min-height: 100vh;
        }

        /* ── Panel izquierdo ── */
        .panel-izq {
            flex: 1.2;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }

        .panel-izq::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&q=80') center/cover no-repeat;
        }

        .panel-izq::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(10, 28, 14, 0.3) 0%,
                rgba(10, 28, 14, 0.7) 55%,
                rgba(10, 28, 14, 0.95) 100%
            );
        }

        .marca {
            position: relative;
            z-index: 2;
            margin-bottom: 2.5rem;
        }

        .marca-logo {
            display: inline-block;
            width: 48px;
            height: 48px;
            background: var(--verde-med);
            border-radius: 12px;
            margin-bottom: 1.2rem;
        }

        .marca h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.2rem;
            font-weight: 900;
            color: #fff;
            line-height: 1;
            margin-bottom: .5rem;
            letter-spacing: -1px;
        }

        .marca h1 span { color: #a8d5b5; }

        .marca p {
            color: rgba(255,255,255,.55);
            font-size: .85rem;
            font-weight: 400;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .pilares {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 1rem;
        }

        .pilar {
            flex: 1;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px;
            padding: .9rem 1rem;
            backdrop-filter: blur(8px);
        }

        .pilar-titulo {
            font-size: .7rem;
            font-weight: 600;
            color: #a8d5b5;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .2rem;
        }

        .pilar-desc {
            font-size: .8rem;
            color: rgba(255,255,255,.5);
        }

        /* ── Panel derecho ── */
        .panel-der {
            width: 460px;
            flex-shrink: 0;
            background: var(--crema);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3rem;
            overflow-y: auto;
        }

        .form-encabezado { margin-bottom: 2rem; }

        .etiqueta-top {
            font-size: .72rem;
            font-weight: 600;
            color: var(--verde-med);
            text-transform: uppercase;
            letter-spacing: .14em;
            margin-bottom: .6rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .etiqueta-top::before {
            content: '';
            display: inline-block;
            width: 18px;
            height: 2px;
            background: var(--verde-med);
            border-radius: 1px;
        }

        .form-encabezado h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--oscuro);
            line-height: 1.2;
        }

        .form-encabezado h2 em {
            font-style: normal;
            color: var(--verde-med);
        }

        /* Tabs de rol */
        .rol-tabs {
            display: flex;
            gap: 3px;
            background: #e8ede9;
            border-radius: 9px;
            padding: 3px;
            margin-bottom: 1.5rem;
        }

        .rol-tab {
            flex: 1;
            padding: .55rem .4rem;
            border: none;
            border-radius: 7px;
            background: transparent;
            color: var(--gris);
            font-family: 'Inter', sans-serif;
            font-size: .8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
        }

        .rol-tab.activo {
            background: #fff;
            color: var(--verde);
            font-weight: 600;
            box-shadow: 0 1px 6px rgba(0,0,0,.1);
        }

        /* Badge de rol activo */
        .rol-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(45,122,62,.08);
            border: 1px solid rgba(45,122,62,.18);
            color: var(--verde-med);
            border-radius: 20px;
            padding: .28rem .75rem;
            font-size: .75rem;
            font-weight: 500;
            margin-bottom: 1.2rem;
        }

        .rol-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--verde-claro);
        }

        /* Campos */
        .campo { margin-bottom: 1.1rem; }

        .campo label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: var(--gris);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .45rem;
        }

        .campo-input { position: relative; }

        .campo-input .icono-campo {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gris-lt);
            font-size: .85rem;
            pointer-events: none;
            font-style: normal;
        }

        .campo input {
            width: 100%;
            padding: .8rem 1rem .8rem 2.5rem;
            background: #fff;
            border: 1.5px solid var(--borde);
            border-radius: 9px;
            font-family: 'Inter', sans-serif;
            font-size: .92rem;
            color: var(--oscuro);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .campo input:focus {
            border-color: var(--verde-med);
            box-shadow: 0 0 0 3px rgba(45,122,62,.1);
        }

        .campo input::placeholder { color: #b8c4ba; }

        /* Alerta */
        .alerta-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid #ef4444;
            color: #c0392b;
            padding: .75rem 1rem;
            border-radius: 8px;
            font-size: .85rem;
            margin-bottom: 1.2rem;
            font-weight: 500;
        }

        /* Botón principal */
        .btn-ingresar {
            width: 100%;
            padding: .9rem 1rem;
            background: var(--verde-med);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-family: 'Inter', sans-serif;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            margin-top: .5rem;
            letter-spacing: .02em;
        }

        .btn-ingresar:hover {
            background: var(--verde-claro);
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(45,122,62,.3);
        }

        .btn-ingresar:active { transform: translateY(0); }

        /* Links */
        .enlaces {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.4rem;
            font-size: .82rem;
        }

        .enlaces a {
            color: var(--verde-med);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }

        .enlaces a:hover { color: var(--verde); text-decoration: underline; }

        /* Divisor */
        .divisor {
            display: flex;
            align-items: center;
            gap: .8rem;
            margin: 1.4rem 0;
            color: var(--gris-lt);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .divisor::before, .divisor::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--borde);
        }

        /* Acceso rápido */
        .acceso-rapido {
            border: 1px solid var(--borde);
            border-radius: 10px;
            overflow: hidden;
        }

        .acceso-rapido-titulo {
            background: #eef2ef;
            padding: .5rem 1rem;
            font-size: .7rem;
            font-weight: 600;
            color: var(--gris);
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .hint-row {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .65rem 1rem;
            cursor: pointer;
            border-top: 1px solid var(--borde);
            transition: background .15s;
            font-size: .82rem;
            color: var(--oscuro);
        }

        .hint-row:hover { background: #f0f5f1; }

        .hint-row-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 700;
            flex-shrink: 0;
            color: #fff;
        }

        .icon-usuario { background: var(--verde-claro); }
        .icon-empresa { background: #3b82f6; }
        .icon-admin   { background: #8b5cf6; }

        .hint-row strong { color: var(--oscuro); font-weight: 600; }
        .hint-row span.cred { color: var(--gris); font-size: .78rem; }

        @media (max-width: 860px) {
            .panel-izq { display: none; }
            .panel-der { width: 100%; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

{{-- Panel izquierdo --}}
<div class="panel-izq">
    <div class="marca">
        <div class="marca-logo"></div>
        <h1>Flow<span>Zone</span></h1>
        <p>Turismo · Ortega, Tolima</p>
    </div>
    <div class="pilares">
        <div class="pilar">
            <div class="pilar-titulo">Naturaleza</div>
            <div class="pilar-desc">Cascadas y miradores</div>
        </div>
        <div class="pilar">
            <div class="pilar-titulo">Gastronomía</div>
            <div class="pilar-desc">Sabores del Tolima</div>
        </div>
        <div class="pilar">
            <div class="pilar-titulo">Hospedaje</div>
            <div class="pilar-desc">Hoteles y posadas</div>
        </div>
    </div>
</div>

{{-- Panel derecho --}}
<div class="panel-der">
    <div class="form-encabezado">
        <div class="etiqueta-top">Bienvenido de vuelta</div>
        <h2>Ingresa a tu<br><em>cuenta</em></h2>
    </div>

    {{-- Tabs de rol --}}
    <div class="rol-tabs">
        <button type="button" class="rol-tab activo" onclick="selRol(this,'usuario')" id="tab-usuario">
            Visitante
        </button>
        <button type="button" class="rol-tab" onclick="selRol(this,'empresa')" id="tab-empresa">
            Empresa
        </button>
        <button type="button" class="rol-tab" onclick="selRol(this,'admin')" id="tab-admin">
            Administrador
        </button>
    </div>

    <div class="rol-badge" id="rol-badge">Ingresando como Visitante</div>

    @if($errors->any())
        <div class="alerta-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="campo">
            <label>Correo electrónico</label>
            <div class="campo-input">
                <i class="icono-campo">@</i>
                <input type="email" name="correo" id="campo-correo" required
                       autocomplete="email" placeholder="tu@correo.com"
                       value="{{ old('correo') }}">
            </div>
        </div>
        <div class="campo">
            <label>Contraseña</label>
            <div class="campo-input">
                <i class="icono-campo">*</i>
                <input type="password" name="password" id="campo-password" required
                       autocomplete="current-password" placeholder="••••••••">
            </div>
        </div>
        <button type="submit" class="btn-ingresar">Ingresar</button>
    </form>

    <div class="enlaces">
        <a href="{{ route('registro') }}">No tienes cuenta? Regístrate</a>
        <a href="{{ route('home') }}">Volver al inicio</a>
    </div>

    <div class="divisor">acceso rápido</div>

    <div class="acceso-rapido">
        <div class="acceso-rapido-titulo">Credenciales de prueba</div>
        <div class="hint-row" onclick="llenar('juan@example.com','admin123','usuario')">
            <div class="hint-row-icon icon-usuario">V</div>
            <div>
                <strong>Visitante</strong>
                <span class="cred"> — juan@example.com / admin123</span>
            </div>
        </div>
        <div class="hint-row" onclick="llenar('empresa@example.com','admin123','empresa')">
            <div class="hint-row-icon icon-empresa">E</div>
            <div>
                <strong>Empresa</strong>
                <span class="cred"> — empresa@example.com / admin123</span>
            </div>
        </div>
        <div class="hint-row" onclick="llenar('admin@flowzone.com','admin123','admin')">
            <div class="hint-row-icon icon-admin">A</div>
            <div>
                <strong>Administrador</strong>
                <span class="cred"> — admin@flowzone.com / admin123</span>
            </div>
        </div>
    </div>
</div>

<script>
const badges = {
    usuario: 'Ingresando como Visitante',
    empresa: 'Ingresando como Empresa',
    admin:   'Ingresando como Administrador',
};

function selRol(btn, rol) {
    document.querySelectorAll('.rol-tab').forEach(t => t.classList.remove('activo'));
    btn.classList.add('activo');
    document.getElementById('rol-badge').textContent = badges[rol];
}

function llenar(correo, pass, rol) {
    document.getElementById('campo-correo').value   = correo;
    document.getElementById('campo-password').value = pass;
    document.querySelectorAll('.rol-tab').forEach(t => t.classList.remove('activo'));
    document.getElementById('tab-' + rol).classList.add('activo');
    document.getElementById('rol-badge').textContent = badges[rol];
}

const correoActual = document.getElementById('campo-correo').value;
if (correoActual.includes('admin@'))    { document.getElementById('tab-admin').classList.add('activo');   document.getElementById('tab-usuario').classList.remove('activo'); document.getElementById('rol-badge').textContent = badges['admin']; }
else if (correoActual.includes('empresa')) { document.getElementById('tab-empresa').classList.add('activo'); document.getElementById('tab-usuario').classList.remove('activo'); document.getElementById('rol-badge').textContent = badges['empresa']; }
</script>
</body>
</html>
