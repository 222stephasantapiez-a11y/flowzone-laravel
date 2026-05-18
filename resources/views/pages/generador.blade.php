@extends('layouts.app')

@section('title', 'Demo: Generadores JS')
@section('body-class', 'no-hero')

@section('content')
<main>

<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow"><i class="fa-solid fa-code"></i> JavaScript</span>
            <h1>Generadores en JS</h1>
            <p>Demo interactiva — <code style="background:rgba(255,255,255,.15);padding:.2rem .5rem;border-radius:4px;">function*</code> y <code style="background:rgba(255,255,255,.15);padding:.2rem .5rem;border-radius:4px;">yield</code></p>
        </div>
    </div>
</section>

<section class="container section" style="max-width:860px;">

    {{-- Explicación --}}
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:1.5rem;margin-bottom:2rem;">
        <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;color:#1a202c;">
            <i class="fa-solid fa-circle-question" style="color:var(--primary)"></i> ¿Qué es un Generador?
        </h2>
        <p style="color:#4a5568;line-height:1.7;margin:0;">
            Un <strong>generador</strong> es una función especial que puede <strong>pausarse</strong> y <strong>reanudarse</strong>.
            Usa <code style="background:#edf2f7;padding:.1rem .4rem;border-radius:4px;">function*</code> para declararse
            y <code style="background:#edf2f7;padding:.1rem .4rem;border-radius:4px;">yield</code> para entregar un valor y pausarse.
            Cada vez que llamas <code style="background:#edf2f7;padding:.1rem .4rem;border-radius:4px;">.next()</code> avanza al siguiente <code>yield</code>.
        </p>
    </div>

    {{-- Código del generador (igual a la imagen) --}}
    <div style="background:#1e1e1e;border-radius:12px;padding:1.5rem;margin-bottom:2rem;font-family:'Courier New',monospace;font-size:1rem;line-height:1.8;">
        <div style="color:#888;font-size:.78rem;margin-bottom:.8rem;letter-spacing:.05em;">CÓDIGO — ejemplo.js</div>
        <div><span style="color:#569cd6;">function*</span> <span style="color:#dcdcaa;">contador</span><span style="color:#fff;">() {</span></div>
        <div style="padding-left:2rem;"><span style="color:#c586c0;">yield</span> <span style="color:#b5cea8;">5</span><span style="color:#fff;">;</span></div>
        <div style="padding-left:2rem;"><span style="color:#c586c0;">yield</span> <span style="color:#b5cea8;">2</span><span style="color:#fff;">;</span></div>
        <div style="padding-left:2rem;"><span style="color:#c586c0;">yield</span> <span style="color:#b5cea8;">0</span><span style="color:#fff;">;</span></div>
        <div style="color:#fff;">}</div>
        <div style="margin-top:.8rem;">
            <span style="color:#569cd6;">const</span> <span style="color:#9cdcfe;">generador</span> <span style="color:#fff;">= </span><span style="color:#dcdcaa;">contador</span><span style="color:#fff;">();</span>
        </div>
    </div>

    {{-- Demo interactiva --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.5rem;margin-bottom:2rem;">
        <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;color:#1a202c;">
            <i class="fa-solid fa-play" style="color:var(--primary)"></i> Demo interactiva
        </h2>
        <p style="color:#718096;font-size:.9rem;margin-bottom:1rem;">
            Presiona el botón para llamar <code>.next()</code> y ver qué devuelve el generador paso a paso.
        </p>

        <button id="btn-next" onclick="llamarNext()"
                style="background:var(--primary,#2d6a4f);color:#fff;border:none;padding:.7rem 1.5rem;
                       border-radius:8px;font-size:1rem;cursor:pointer;font-weight:600;margin-bottom:1rem;">
            <i class="fa-solid fa-forward-step"></i> Llamar .next()
        </button>
        <button onclick="reiniciar()"
                style="background:#e2e8f0;color:#4a5568;border:none;padding:.7rem 1.2rem;
                       border-radius:8px;font-size:1rem;cursor:pointer;font-weight:600;margin-bottom:1rem;margin-left:.5rem;">
            <i class="fa-solid fa-rotate-left"></i> Reiniciar
        </button>

        {{-- Resultado visual --}}
        <div id="resultado"
             style="background:#f0fff4;border:2px solid #9ae6b4;border-radius:8px;padding:1rem;
                    font-family:'Courier New',monospace;font-size:1.1rem;min-height:60px;
                    display:flex;align-items:center;gap:.8rem;">
            <i class="fa-solid fa-arrow-right" style="color:#38a169;"></i>
            <span id="resultado-texto" style="color:#276749;">Presiona el botón para empezar...</span>
        </div>

        {{-- Historial tipo consola --}}
        <div style="margin-top:1rem;">
            <div style="font-size:.8rem;color:#718096;margin-bottom:.4rem;">Historial (consola):</div>
            <div id="consola"
                 style="background:#1e1e1e;border-radius:8px;padding:1rem;font-family:'Courier New',monospace;
                        font-size:.9rem;min-height:80px;color:#d4d4d4;line-height:1.8;">
            </div>
        </div>
    </div>

    {{-- Tabla explicativa --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.5rem;">
        <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;color:#1a202c;">
            <i class="fa-solid fa-table" style="color:var(--primary)"></i> ¿Qué pasa en cada .next()?
        </h2>
        <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:.6rem 1rem;text-align:left;border-bottom:2px solid #e2e8f0;">Llamada</th>
                    <th style="padding:.6rem 1rem;text-align:left;border-bottom:2px solid #e2e8f0;">Llega hasta...</th>
                    <th style="padding:.6rem 1rem;text-align:left;border-bottom:2px solid #e2e8f0;">Devuelve</th>
                    <th style="padding:.6rem 1rem;text-align:left;border-bottom:2px solid #e2e8f0;">done</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><code>generador.next()</code></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><code>yield 5</code></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><strong style="color:#2d6a4f;">5</strong></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;">false</td>
                </tr>
                <tr style="background:#f7fafc;">
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><code>generador.next()</code></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><code>yield 2</code></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><strong style="color:#2d6a4f;">2</strong></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;">false</td>
                </tr>
                <tr>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><code>generador.next()</code></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><code>yield 0</code></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;"><strong style="color:#2d6a4f;">0</strong></td>
                    <td style="padding:.6rem 1rem;border-bottom:1px solid #e2e8f0;">false</td>
                </tr>
                <tr style="background:#fff5f5;">
                    <td style="padding:.6rem 1rem;"><code>generador.next()</code></td>
                    <td style="padding:.6rem 1rem;">fin de la función</td>
                    <td style="padding:.6rem 1rem;"><strong style="color:#c53030;">undefined</strong></td>
                    <td style="padding:.6rem 1rem;"><strong style="color:#c53030;">true</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

</section>
</main>
@endsection

@push('scripts')
<script>
// El generador — exactamente igual a la imagen
function* contador() {
    yield 5;
    yield 2;
    yield 0;
}

// Creamos la instancia
var generador = contador();
var llamada = 0;

function llamarNext() {
    llamada++;
    var resultado = generador.next();
    var consola   = document.getElementById('consola');
    var textoEl   = document.getElementById('resultado-texto');
    var btn       = document.getElementById('btn-next');

    // Mostrar en el resultado principal
    if (resultado.done) {
        textoEl.innerHTML = '<span style="color:#c53030;">undefined</span> &nbsp;— <em>done: true (generador terminado)</em>';
        btn.disabled = true;
        btn.style.opacity = '0.5';
        btn.style.cursor  = 'not-allowed';
    } else {
        textoEl.innerHTML = 'value: <strong style="color:#276749;font-size:1.3rem;">' + resultado.value + '</strong> &nbsp;— done: false';
    }

    // Agregar línea al historial
    var linea = document.createElement('div');
    linea.innerHTML = '<span style="color:#888;">llamada ' + llamada + ' →</span> '
        + 'generador.next().value = '
        + '<span style="color:' + (resultado.done ? '#f87171' : '#86efac') + ';">'
        + (resultado.done ? 'undefined' : resultado.value)
        + '</span>';
    consola.appendChild(linea);
}

function reiniciar() {
    generador = contador();
    llamada   = 0;
    document.getElementById('consola').innerHTML = '';
    document.getElementById('resultado-texto').textContent = 'Presiona el botón para empezar...';
    var btn = document.getElementById('btn-next');
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor  = 'pointer';
}
</script>
@endpush
