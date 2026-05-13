<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowZone — Recuperar contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        body { font-family: var(--font-body); background: var(--green-900); display: flex; min-height: 100vh; }
        .auth-panel-left { flex: 1.2; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; padding: 3rem; }
        .auth-panel-left::before { content: ''; position: absolute; inset: 0; background: url('https://i.pinimg.com/736x/88/56/0e/88560eacc1be906f0925fc8dfc234d06.jpg') center/cover no-repeat; }
        .auth-panel-left::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(27,67,50,.55) 0%, rgba(27,67,50,.88) 100%); }
        .auth-brand { position: relative; z-index: 2; margin-bottom: 2.5rem; }
        .auth-brand h1 { font-family: var(--font-display); font-size: 3.2rem; font-weight: 900; color: var(--white); line-height: 1; margin-bottom: .5rem; letter-spacing: -1px; }
        .auth-brand h1 span { color: var(--green-200); }
        .auth-brand p { color: rgba(255,255,255,.55); font-size: .85rem; letter-spacing: .12em; text-transform: uppercase; }
        .auth-pillars { position: relative; z-index: 2; display: flex; gap: 1rem; }
        .auth-pillar { flex: 1; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1); border-radius: var(--radius-md); padding: .9rem 1rem; backdrop-filter: blur(8px); }
        .auth-pillar-title { font-size: .7rem; font-weight: 600; color: var(--green-200); text-transform: uppercase; letter-spacing: .08em; margin-bottom: .2rem; }
        .auth-pillar-desc { font-size: .8rem; color: rgba(255,255,255,.5); }
        .auth-panel-right { width: 460px; flex-shrink: 0; background: var(--gray-50); display: flex; flex-direction: column; justify-content: center; padding: 3rem; overflow-y: auto; }
        .auth-heading { margin-bottom: 2rem; }
        .auth-eyebrow { font-size: .72rem; font-weight: 600; color: var(--green-700); text-transform: uppercase; letter-spacing: .14em; margin-bottom: .6rem; display: flex; align-items: center; gap: .5rem; }
        .auth-eyebrow::before { content: ''; display: inline-block; width: 18px; height: 2px; background: var(--green-700); border-radius: 1px; }
        .auth-heading h2 { font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--gray-900); line-height: 1.2; }
        .auth-heading h2 em { font-style: normal; color: var(--green-700); }
        .auth-desc { font-size: .875rem; color: var(--gray-500); margin-top: .5rem; line-height: 1.5; }
        .auth-field { margin-bottom: 1.1rem; }
        .auth-field label { display: block; font-size: .75rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: .08em; margin-bottom: .45rem; }
        .auth-field-wrap { position: relative; }
        .auth-field-wrap .auth-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: .85rem; pointer-events: none; }
        .auth-field input { width: 100%; padding: .8rem 1rem .8rem 2.5rem; background: var(--white); border: 1.5px solid var(--gray-200); border-radius: var(--radius-md); font-family: var(--font-body); font-size: .92rem; color: var(--gray-900); transition: border-color .2s, box-shadow .2s; outline: none; box-sizing: border-box; }
        .auth-field input:focus { border-color: var(--green-700); box-shadow: 0 0 0 3px rgba(64,145,108,.12); }
        .auth-field input::placeholder { color: var(--gray-400); }
        .auth-alert-error { background: #fef2f2; border: 1px solid #fecaca; border-left: 3px solid var(--danger); color: var(--danger); padding: .75rem 1rem; border-radius: var(--radius-md); font-size: .85rem; margin-bottom: 1.2rem; font-weight: 500; display: flex; align-items: center; gap: .5rem; }
        .auth-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 3px solid var(--green-600); color: var(--green-800); padding: 1rem 1.1rem; border-radius: var(--radius-md); font-size: .875rem; margin-bottom: 1.2rem; display: flex; align-items: flex-start; gap: .6rem; line-height: 1.45; }
        .auth-alert-success i { margin-top: .1rem; color: var(--green-600); flex-shrink: 0; }
        .auth-submit { width: 100%; padding: .9rem 1rem; background: var(--green-800); color: var(--white); border: none; border-radius: var(--radius-md); font-family: var(--font-body); font-size: .95rem; font-weight: 600; cursor: pointer; transition: var(--transition); margin-top: .5rem; letter-spacing: .02em; }
        .auth-submit:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 5px 18px rgba(45,106,79,.3); }
        .auth-links { display: flex; justify-content: center; margin-top: 1.4rem; font-size: .82rem; }
        .auth-links a { color: var(--green-700); text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: .35rem; }
        .auth-links a:hover { color: var(--green-800); text-decoration: underline; }
        @media (max-width: 860px) { .auth-panel-left { display: none; } .auth-panel-right { width: 100%; padding: 2rem 1.5rem; } }
    </style>
</head>
<body>

<div class="auth-panel-left">
    <div class="auth-brand">
        <h1>Flow<span>Zone</span></h1>
        <p>Turismo · Ortega, Tolima</p>
    </div>
    <div class="auth-pillars">
        <div class="auth-pillar"><div class="auth-pillar-title">Naturaleza</div><div class="auth-pillar-desc">Cascadas y miradores</div></div>
        <div class="auth-pillar"><div class="auth-pillar-title">Gastronomía</div><div class="auth-pillar-desc">Sabores del Tolima</div></div>
        <div class="auth-pillar"><div class="auth-pillar-title">Hospedaje</div><div class="auth-pillar-desc">Hoteles y posadas</div></div>
    </div>
</div>

<div class="auth-panel-right">
    <div class="auth-heading">
        <div class="auth-eyebrow">Recuperar acceso</div>
        <h2>¿Olvidaste tu<br><em>contraseña?</em></h2>
        <p class="auth-desc">Ingresa tu correo y te enviaremos un enlace para crear una nueva contraseña.</p>
    </div>

    @if(session('status'))
        <div class="auth-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="auth-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="auth-field">
            <label for="campo-email">Correo electrónico</label>
            <div class="auth-field-wrap">
                <i class="auth-icon fa-solid fa-envelope"></i>
                <input type="email" name="email" id="campo-email" required
                       autocomplete="email" placeholder="tu@correo.com"
                       value="{{ old('email') }}">
            </div>
        </div>
        <button type="submit" class="auth-submit">
            <i class="fa-solid fa-paper-plane"></i> Enviar enlace de recuperación
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left"></i> Volver al inicio de sesión</a>
    </div>
</div>

</body>
</html>