{{--
  Partial: calificaciones y reseñas
  Variables requeridas: $stats, $miCalificacion, $reseñas, $tipo, $itemId
--}}

<div class="rv-section">

    {{-- ── Encabezado con score global ── --}}
    <div class="rv-header">
        <div class="rv-score-box">
            <div class="rv-score-num">{{ $stats['promedio'] > 0 ? number_format($stats['promedio'], 1) : '—' }}</div>
            <div class="rv-score-stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa-{{ $i <= round($stats['promedio']) ? 'solid' : 'regular' }} fa-star"></i>
                @endfor
            </div>
            <div class="rv-score-label">
                {{ $stats['total'] }} {{ $stats['total'] === 1 ? 'reseña' : 'reseñas' }}
            </div>
        </div>
        <div class="rv-header-text">
            <h3 class="rv-title">Calificaciones y reseñas</h3>
            <p class="rv-subtitle">
                @if($stats['total'] > 0)
                    Basado en {{ $stats['total'] }} {{ $stats['total'] === 1 ? 'calificación' : 'calificaciones' }} de visitantes.
                @else
                    Sé el primero en compartir tu experiencia.
                @endif
            </p>
        </div>
    </div>

    {{-- ── Formulario ── --}}
    @auth
        <div class="rv-form-wrap">
            <div class="rv-form-header">
                <div class="rv-form-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <p class="rv-form-name">{{ Auth::user()->name }}</p>
                    <p class="rv-form-hint">{{ $miCalificacion ? 'Actualiza tu reseña' : 'Escribe una reseña' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('calificaciones.store') }}" class="rv-form">
                @csrf
                <input type="hidden" name="tipo"    value="{{ $tipo }}">
                <input type="hidden" name="item_id" value="{{ $itemId }}">
                <input type="hidden" name="calificacion" id="rv-cal-{{ $tipo }}-{{ $itemId }}"
                       value="{{ $miCalificacion?->calificacion ?? '' }}">

                {{-- Estrellas interactivas --}}
                <div class="rv-stars-label">Tu puntuación <span class="rv-stars-required">*</span></div>
                <div class="rv-star-picker" id="rv-picker-{{ $tipo }}-{{ $itemId }}"
                     data-input="rv-cal-{{ $tipo }}-{{ $itemId }}"
                     data-value="{{ $miCalificacion?->calificacion ?? 0 }}"
                     role="radiogroup" aria-label="Puntuación">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                class="rv-star-btn {{ ($miCalificacion && $miCalificacion->calificacion >= $i) ? 'active' : '' }}"
                                data-value="{{ $i }}"
                                aria-label="{{ $i }} {{ $i === 1 ? 'estrella' : 'estrellas' }}"
                                title="{{ $i }} {{ $i === 1 ? 'estrella' : 'estrellas' }}">
                            <i class="fa-{{ ($miCalificacion && $miCalificacion->calificacion >= $i) ? 'solid' : 'regular' }} fa-star"></i>
                        </button>
                    @endfor
                    <span class="rv-star-text" id="rv-text-{{ $tipo }}-{{ $itemId }}">
                        @if($miCalificacion)
                            @php $labels = ['','Malo','Regular','Bueno','Muy bueno','Excelente']; @endphp
                            {{ $labels[$miCalificacion->calificacion] ?? '' }}
                        @endif
                    </span>
                </div>

                {{-- Comentario --}}
                <div class="rv-textarea-wrap">
                    <textarea name="comentario"
                              class="rv-textarea"
                              rows="4"
                              maxlength="1000"
                              placeholder="Cuéntanos tu experiencia (opcional)...">{{ $miCalificacion?->comentario }}</textarea>
                    <span class="rv-char-count">0 / 1000</span>
                </div>

                <div class="rv-form-actions">
                    <button type="submit" class="btn btn-primary rv-submit">
                        <i class="fa-solid fa-paper-plane fa-xs"></i>
                        {{ $miCalificacion ? 'Actualizar reseña' : 'Publicar reseña' }}
                    </button>
                    @if($miCalificacion)
                        <span class="rv-edit-badge">
                            <i class="fa-solid fa-pen-to-square fa-xs"></i> Editando tu reseña
                        </span>
                    @endif
                </div>
            </form>
        </div>
    @else
        <div class="rv-login-prompt">
            <div class="rv-login-icon">
                <i class="fa-solid fa-star"></i>
            </div>
            <div>
                <p class="rv-login-title">¿Visitaste este lugar?</p>
                <p class="rv-login-sub">
                    <a href="{{ route('login') }}">Inicia sesión</a> para dejar tu calificación y comentario.
                </p>
            </div>
        </div>
    @endauth

    {{-- ── Lista de reseñas ── --}}
    @if($reseñas->isNotEmpty())
        <div class="rv-list-header">
            <h4 class="rv-list-title">
                <i class="fa-solid fa-comments fa-xs"></i>
                Comentarios recientes
            </h4>
            <span class="rv-list-count">{{ $reseñas->count() }}</span>
        </div>

        <div class="rv-list">
            @foreach($reseñas as $r)
                <div class="rv-card">
                    <div class="rv-card-top">
                        <div class="rv-card-avatar">
                            {{ strtoupper(substr($r->usuario?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="rv-card-meta">
                            <span class="rv-card-name">{{ $r->usuario?->name ?? 'Usuario' }}</span>
                            <span class="rv-card-date">{{ $r->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="rv-card-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= $r->calificacion ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                            <span class="rv-card-score">{{ $r->calificacion }}.0</span>
                        </div>
                    </div>
                    <p class="rv-card-text">{{ $r->comentario }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="rv-empty">
            <i class="fa-regular fa-comment-dots rv-empty-icon"></i>
            <p>Aún no hay reseñas. ¡Sé el primero en comentar!</p>
        </div>
    @endif

</div>

<script>
(function() {
    var labels = ['', 'Malo', 'Regular', 'Bueno', 'Muy bueno', 'Excelente'];

    document.querySelectorAll('.rv-star-picker').forEach(function(picker) {
        var inputId  = picker.dataset.input;
        var textId   = 'rv-text-' + inputId.replace('rv-cal-', '');
        var input    = document.getElementById(inputId);
        var textEl   = document.getElementById(textId);
        var buttons  = picker.querySelectorAll('.rv-star-btn');
        var current  = parseInt(picker.dataset.value) || 0;

        function paint(val, hover) {
            buttons.forEach(function(btn) {
                var v = parseInt(btn.dataset.value);
                var icon = btn.querySelector('i');
                if (v <= val) {
                    icon.className = 'fa-solid fa-star';
                    btn.classList.add('active');
                } else {
                    icon.className = 'fa-regular fa-star';
                    btn.classList.remove('active');
                }
            });
            if (textEl) textEl.textContent = val > 0 ? labels[val] : '';
        }

        // Hover
        buttons.forEach(function(btn) {
            btn.addEventListener('mouseenter', function() {
                paint(parseInt(btn.dataset.value), true);
            });
            btn.addEventListener('mouseleave', function() {
                paint(current);
            });
            btn.addEventListener('click', function() {
                current = parseInt(btn.dataset.value);
                if (input) input.value = current;
                paint(current);
            });
        });

        // Estado inicial
        paint(current);
    });

    // Contador de caracteres
    document.querySelectorAll('.rv-textarea').forEach(function(ta) {
        var counter = ta.parentElement.querySelector('.rv-char-count');
        if (!counter) return;
        function update() { counter.textContent = ta.value.length + ' / 1000'; }
        ta.addEventListener('input', update);
        update();
    });
})();
</script>
