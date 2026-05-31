<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowZone — Ingresar</title>
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
            flex: 1.2;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }

        /* Carrusel slides */
        .carousel-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
        }
        .carousel-slide.active { opacity: 1; }

        .auth-panel-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(27,67,50,.45) 0%, rgba(27,67,50,.88) 100%);
            z-index: 1;
        }

        .auth-brand {
            position: relative;
            z-index: 2;
            margin-bottom: 2.5rem;
        }

        .auth-brand h1 {
            font-family: var(--font-display);
            font-size: 3.2rem;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
            margin-bottom: .5rem;
            letter-spacing: -1px;
        }

        .auth-brand h1 span { color: var(--green-200); }

        .auth-brand p {
            color: rgba(255,255,255,.55);
            font-size: .85rem;
            font-weight: 400;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .auth-pillars {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 1rem;
        }

        .auth-pillar {
            flex: 1;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius-md);
            padding: .9rem 1rem;
            backdrop-filter: blur(8px);
        }

        .auth-pillar-title {
            font-size: .7rem;
            font-weight: 600;
            color: var(--green-200);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .2rem;
        }

        .auth-pillar-desc {
            font-size: .8rem;
            color: rgba(255,255,255,.5);
        }

        /* ── Panel derecho ── */
        .auth-panel-right {
            width: 460px;
            flex-shrink: 0;
            background: var(--gray-50);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            overflow-y: auto;
        }

        .auth-heading { margin-bottom: 2rem; }

        .auth-eyebrow {
            font-size: .72rem;
            font-weight: 600;
            color: var(--green-700);
            text-transform: uppercase;
            letter-spacing: .14em;
            margin-bottom: .6rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .auth-eyebrow::before {
            content: '';
            display: inline-block;
            width: 18px;
            height: 2px;
            background: var(--green-700);
            border-radius: 1px;
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

        .auth-tabs {
            display: flex;
            gap: 3px;
            background: var(--gray-200);
            border-radius: var(--radius-md);
            padding: 3px;
            margin-bottom: 1.5rem;
        }

        .auth-tab {
            flex: 1;
            padding: .55rem .4rem;
            border: none;
            border-radius: var(--radius-sm);
            background: transparent;
            color: var(--gray-600);
            font-family: var(--font-body);
            font-size: .8rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .auth-tab.active {
            background: var(--white);
            color: var(--green-800);
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        .auth-role-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(45,106,79,.08);
            border: 1px solid rgba(45,106,79,.18);
            color: var(--green-700);
            border-radius: var(--radius-full);
            padding: .28rem .75rem;
            font-size: .75rem;
            font-weight: 500;
            margin-bottom: 1.2rem;
        }

        .auth-role-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green-600);
        }

        .auth-field { margin-bottom: 1.1rem; }

        .auth-field label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .45rem;
        }

        .auth-field-wrap { position: relative; }

        .auth-field-wrap .auth-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: .85rem;
            pointer-events: none;
        }

        .auth-field input {
            width: 100%;
            padding: .8rem 1rem .8rem 2.5rem;
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

        .auth-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid var(--danger);
            color: var(--danger);
            padding: .75rem 1rem;
            border-radius: var(--radius-md);
            font-size: .85rem;
            margin-bottom: 1.2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .auth-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 3px solid #16a34a;
            color: #15803d;
            padding: .75rem 1rem;
            border-radius: var(--radius-md);
            font-size: .85rem;
            margin-bottom: 1.2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .auth-submit {
            width: 100%;
            padding: .9rem 1rem;
            background: var(--green-800);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: .5rem;
            letter-spacing: .02em;
        }

        .auth-submit:hover {
            background: var(--green-700);
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(45,106,79,.3);
        }

        .auth-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.4rem;
            font-size: .82rem;
        }

        .auth-links a {
            color: var(--green-700);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }

        .auth-links a:hover { color: var(--green-800); text-decoration: underline; }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: .8rem;
            margin: 1.4rem 0;
            color: var(--gray-400);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .auth-quick {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .auth-quick-title {
            background: var(--gray-100);
            padding: .5rem 1rem;
            font-size: .7rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .auth-quick-row {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .65rem 1rem;
            cursor: pointer;
            border-top: 1px solid var(--gray-200);
            transition: background .15s;
            font-size: .82rem;
            color: var(--gray-900);
        }

        .auth-quick-row:hover { background: var(--gray-100); }

        .auth-quick-icon {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 700;
            flex-shrink: 0;
            color: var(--white);
        }

        .icon-usuario { background: var(--green-600); }
        .icon-empresa { background: #3b82f6; }
        .icon-admin   { background: #8b5cf6; }

        /* Botón volver */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--gray-500);
            font-size: .82rem;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 1.5rem;
            transition: color .2s;
        }
        .btn-back:hover { color: var(--green-700); }

        @media (max-width: 860px) {
            .auth-panel-left { display: none; }
            .auth-panel-right { width: 100%; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

{{-- Panel izquierdo con carrusel --}}
<div class="auth-panel-left">

    {{-- Slides del carrusel --}}
    @php
        $slides = \App\Models\HeroImage::where('activa', true)->where('seccion', 'hero')->orderBy('orden')->get();
        if ($slides->isEmpty()) {
            $slides = collect([
                (object)['url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200'],
                (object)['url' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1200'],
                (object)['url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200'],
            ]);
        }
    @endphp

    @foreach($slides as $i => $slide)
    @php $imgUrl = str_starts_with($slide->url, 'http') ? $slide->url : asset('storage/' . $slide->url); @endphp
    <div class="carousel-slide {{ $i === 0 ? 'active' : '' }}"
         style="background-image:url('{{ $imgUrl }}');"></div>
    @endforeach

    <div class="auth-brand">
        <h1>Flow<span>Zone</span></h1>
        <p>Turismo · Ortega, Tolima</p>
    </div>
    <div class="auth-pillars">
        <div class="auth-pillar">
            <div class="auth-pillar-title">Naturaleza</div>
            <div class="auth-pillar-desc">Cascadas y miradores</div>
        </div>
        <div class="auth-pillar">
            <div class="auth-pillar-title">Gastronomía</div>
            <div class="auth-pillar-desc">Sabores del Tolima</div>
        </div>
        <div class="auth-pillar">
            <div class="auth-pillar-title">Hospedaje</div>
            <div class="auth-pillar-desc">Hoteles y posadas</div>
        </div>
    </div>
</div>

{{-- Panel derecho --}}
<div class="auth-panel-right">

    {{-- Botón volver --}}
    <a href="{{ route('home') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left fa-xs"></i> Volver al inicio
    </a>

    <div class="auth-heading">
        <div class="auth-eyebrow">Bienvenido de vuelta</div>
        <h2>Ingresa a tu<br><em>cuenta</em></h2>
    </div>

    <div class="auth-tabs">
        <button type="button" class="auth-tab active" onclick="selRol(this,'usuario')" id="tab-usuario">Visitante</button>
        <button type="button" class="auth-tab" onclick="selRol(this,'empresa')" id="tab-empresa">Empresa</button>
        <button type="button" class="auth-tab" onclick="selRol(this,'admin')" id="tab-admin">Administrador</button>
    </div>

    <div class="auth-role-badge" id="rol-badge">Ingresando como Visitante</div>

    @if(session('success_reset'))
        <div class="auth-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success_reset') }}
        </div>
    @endif

    @if($errors->any())
        <div class="auth-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="contexto" id="campo-contexto" value="usuario">
        <div class="auth-field">
            <label for="campo-correo">Correo electrónico</label>
            <div class="auth-field-wrap">
                <i class="auth-icon fa-solid fa-envelope"></i>
                <input type="email" name="correo" id="campo-correo" required
                       autocomplete="email" placeholder="tu@correo.com"
                       value="{{ old('correo') }}">
            </div>
        </div>
        <div class="auth-field">
            <label for="campo-password">Contraseña</label>
            <div class="auth-field-wrap">
                <i class="auth-icon fa-solid fa-lock"></i>
                <input type="password" name="password" id="campo-password" required
                       autocomplete="current-password" placeholder="••••••••">
            </div>
        </div>
        <button type="submit" class="auth-submit btn-block">
            <i class="fa-solid fa-right-to-bracket"></i> Ingresar
        </button>
    </form>

    <div class="auth-links" style="justify-content:center;">
        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
    </div>
    <div style="margin-top:1.25rem;text-align:center;">
        <p style="font-size:.85rem;color:var(--gray-500);margin-bottom:.75rem;">¿Aún no tienes cuenta?</p>
        <a href="{{ route('registro') }}" class="btn btn-outline" style="width:100%;display:block;text-align:center;">
            <i class="fa-solid fa-user-plus fa-xs"></i> Crear cuenta gratis
        </a>
    </div>

    <div class="auth-divider">acceso rápido</div>

    <div class="auth-quick">
        <div class="auth-quick-title">Credenciales de prueba</div>
        <div class="auth-quick-row" onclick="llenar('juan@example.com','admin123','usuario')">
            <div class="auth-quick-icon icon-usuario">V</div>
            <div>
                <strong>Visitante</strong>
                <span style="color:var(--gray-400);font-size:.78rem;"> — juan@example.com / admin123</span>
            </div>
        </div>
        <div class="auth-quick-row" onclick="llenar('empresa@example.com','admin123','empresa')">
            <div class="auth-quick-icon icon-empresa">E</div>
            <div>
                <strong>Empresa</strong>
                <span style="color:var(--gray-400);font-size:.78rem;"> — empresa@example.com / admin123</span>
            </div>
        </div>
        <div class="auth-quick-row" onclick="llenar('admin@flowzone.com','admin123','admin')">
            <div class="auth-quick-icon icon-admin">A</div>
            <div>
                <strong>Administrador</strong>
                <span style="color:var(--gray-400);font-size:.78rem;"> — admin@flowzone.com / admin123</span>
            </div>
        </div>
    </div>
</div>

<script>
// ── Roles ──
const badges = {
    usuario: 'Ingresando como Visitante',
    empresa: 'Ingresando como Empresa',
    admin:   'Ingresando como Administrador',
};

function selRol(btn, rol) {
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('rol-badge').textContent = badges[rol];
    document.getElementById('campo-contexto').value  = rol;
}

function llenar(correo, pass, rol) {
    document.getElementById('campo-correo').value   = correo;
    document.getElementById('campo-password').value = pass;
    document.getElementById('campo-contexto').value = rol;
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + rol).classList.add('active');
    document.getElementById('rol-badge').textContent = badges[rol];
}

const correoActual = document.getElementById('campo-correo').value;
if (correoActual.includes('admin@')) {
    document.getElementById('tab-admin').classList.add('active');
    document.getElementById('tab-usuario').classList.remove('active');
    document.getElementById('rol-badge').textContent = badges['admin'];
    document.getElementById('campo-contexto').value  = 'admin';
} else if (correoActual.includes('empresa')) {
    document.getElementById('tab-empresa').classList.add('active');
    document.getElementById('tab-usuario').classList.remove('active');
    document.getElementById('rol-badge').textContent = badges['empresa'];
    document.getElementById('campo-contexto').value  = 'empresa';
}

// ── Carrusel ──
const slides = document.querySelectorAll('.carousel-slide');
let current  = 0;
let timer    = null;

function goToSlide(idx) {
    slides[current].classList.remove('active');
    current = (idx + slides.length) % slides.length;
    slides[current].classList.add('active');
}

function startTimer() {
    timer = setInterval(() => goToSlide(current + 1), 5000);
}

if (slides.length > 1) startTimer();
</script>
</body>
</html>