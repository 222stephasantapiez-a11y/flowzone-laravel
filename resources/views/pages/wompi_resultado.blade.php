@extends('layouts.app')

@section('title', 'Resultado del Pago')
@section('body-class', 'no-hero')

@php
    $aprobado = $estado === 'APPROVED';
    $declinado = in_array($estado, ['DECLINED', 'VOIDED', 'ERROR']);
    $icono   = $aprobado  ? 'fa-circle-check' : ($declinado ? 'fa-circle-xmark' : 'fa-clock');
    $color   = $aprobado  ? 'var(--green-600)' : ($declinado ? '#dc2626' : '#d97706');
    $bgColor = $aprobado  ? 'var(--green-50)'  : ($declinado ? '#fef2f2' : '#fffbeb');
    $titulo  = $aprobado  ? '¡Pago Aprobado! 🎉' : ($declinado ? 'Pago Rechazado' : 'Pago Pendiente');
    $msg     = $aprobado
        ? 'Tu reserva ha sido confirmada exitosamente.'
        : ($declinado
            ? 'Tu pago no pudo procesarse. Puedes intentar con otro método.'
            : 'Tu pago está siendo procesado. Te notificaremos cuando se confirme.');
@endphp

<section style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);padding:5rem 0 3rem;margin-top:var(--navbar-height);">
    <div class="container" style="text-align:center;">
        <i class="fa-solid {{ $icono }}" style="font-size:3.5rem;color:{{ $color }};margin-bottom:1rem;display:block;"></i>
        <h1 style="font-family:var(--font-display);font-size:clamp(1.6rem,4vw,2.4rem);font-weight:900;color:#fff;margin-bottom:.5rem;">
            {{ $titulo }}
        </h1>
        <p style="color:rgba(255,255,255,.75);font-size:.95rem;max-width:520px;margin:0 auto;">{{ $msg }}</p>
    </div>
</section>

<section class="container section">
    <div style="max-width:700px;margin:0 auto;display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Tarjeta estado --}}
        <div class="admin-section" style="border-top:4px solid {{ $color }};background:{{ $bgColor }};">
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
                <div style="width:52px;height:52px;background:{{ $color }};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid {{ $icono }}" style="color:#fff;font-size:1.4rem;"></i>
                </div>
                <div>
                    <p style="font-size:.78rem;font-weight:700;color:{{ $color }};text-transform:uppercase;letter-spacing:.08em;margin-bottom:.15rem;">Estado del pago</p>
                    <p style="font-size:1.2rem;font-weight:800;color:var(--gray-900);">{{ $estado }}</p>
                </div>
            </div>

            @php
                $pm = $transaction['payment_method'] ?? [];
                $metodoLabel = match($pm['type'] ?? '') {
                    'NEQUI'                => 'Nequi',
                    'PSE'                  => 'PSE / Bancolombia',
                    'CARD'                 => 'Tarjeta ' . strtoupper($pm['extra']['brand'] ?? ''),
                    'BANCOLOMBIA_TRANSFER' => 'Transferencia Bancolombia',
                    default                => $pm['type'] ?? 'Wompi',
                };
            @endphp

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem 1.5rem;">
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">Referencia</p>
                    <p style="font-size:.92rem;font-weight:700;color:var(--gray-900);font-family:monospace;">{{ $reserva->referencia_pago }}</p>
                </div>
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">ID Transacción Wompi</p>
                    <p style="font-size:.85rem;font-weight:600;color:var(--gray-700);font-family:monospace;">{{ $reserva->wompi_transaction_id ?? ($transaction['id'] ?? '—') }}</p>
                </div>
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">Monto pagado</p>
                    <p style="font-size:1rem;font-weight:800;color:var(--green-800);">
                        ${{ number_format(($transaction['amount_in_cents'] ?? 0) / 100, 0, ',', '.') }} COP
                    </p>
                </div>
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">Método</p>
                    <p style="font-size:.92rem;font-weight:600;color:var(--gray-800);">{{ $metodoLabel }}</p>
                </div>
            </div>
        </div>

        {{-- Detalles de la reserva --}}
        <div class="admin-section" style="border-top:4px solid var(--green-600);">
            <h3 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:1rem;">
                <i class="fa-solid fa-hotel fa-xs" style="color:var(--green-600);margin-right:.4rem;"></i>
                Detalle de la Reserva
            </h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem 1.5rem;">
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">Hotel</p>
                    <p style="font-size:.92rem;font-weight:700;color:var(--gray-900);">{{ $reserva->hotel->nombre ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">Personas</p>
                    <p style="font-size:.92rem;font-weight:700;color:var(--gray-900);">{{ $reserva->num_personas }}</p>
                </div>
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">Check-in</p>
                    <p style="font-size:.92rem;font-weight:700;color:var(--gray-900);">{{ $reserva->fecha_entrada->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.1rem;">Check-out</p>
                    <p style="font-size:.92rem;font-weight:700;color:var(--gray-900);">{{ $reserva->fecha_salida->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
            <a href="{{ route('mis-reservas') }}" class="btn btn-primary" style="flex:1;text-align:center;">
                <i class="fa-solid fa-list-check"></i> Ver mis reservas
            </a>
            @if($declinado)
                <a href="{{ route('hoteles.detalle', $reserva->hotel_id) }}" class="btn btn-outline" style="flex:1;text-align:center;">
                    <i class="fa-solid fa-rotate-left"></i> Intentar de nuevo
                </a>
            @else
                <a href="{{ route('home') }}" class="btn btn-outline" style="flex:1;text-align:center;">
                    <i class="fa-solid fa-house"></i> Ir al inicio
                </a>
            @endif
        </div>

        {{-- Badge Wompi --}}
        <div style="text-align:center;padding:1rem;border-top:1px solid var(--gray-100);">
            <p style="font-size:.78rem;color:var(--gray-400);">
                <i class="fa-solid fa-shield-halved fa-xs"></i>
                Pago procesado de forma segura a través de <strong style="color:var(--gray-600);">Wompi</strong>
            </p>
        </div>

    </div>
</section>

@endsection