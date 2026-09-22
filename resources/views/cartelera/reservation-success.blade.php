@extends('layouts.public', [
    'title' => 'Solicitud recibida · Centro Cultural Domingo Soler',
])

@section('content')
    <section class="success">
        <p class="eyebrow">Solicitud recibida</p>
        <h1>Tu lugar está en proceso de confirmación.</h1>

        <p class="intro">
            Guarda este folio. El equipo del Centro Cultural Domingo Soler revisará
            tu solicitud y te contactará por WhatsApp para confirmar disponibilidad
            e indicaciones de pago, cuando corresponda.
        </p>

        <div class="folio">{{ $reservation->folio }}</div>

        <p class="detail-meta">
            {{ $event->title }} ·
            {{ $reservation->eventSchedule->starts_at->translatedFormat('d \d\e F · H:i') }}
        </p>

        <p class="intro" style="margin-top: 28px;">
            Solicitaste {{ $reservation->quantity }}
            {{ $reservation->quantity === 1 ? 'lugar' : 'lugares' }}.
        </p>

        <a class="button" href="{{ route('cartelera.index') }}">Volver a cartelera</a>
    </section>
@endsection