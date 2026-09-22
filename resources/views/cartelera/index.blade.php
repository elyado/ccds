@extends('layouts.public')

@section('content')
    <p class="eyebrow">Programación</p>
    <h1>Cartelera</h1>
    <p class="intro">
        Actividades, encuentros y escenas para habitar el Centro Cultural Domingo Soler.
    </p>

    <div class="event-grid">
        @forelse ($events as $event)
            @php
                $nextSchedule = $event->schedules->first();
            @endphp

            <a class="event-card" href="{{ route('cartelera.show', $event->slug) }}">
                <div class="event-card__meta">
                    {{ $event->discipline ?: 'Actividad cultural' }}
                    @if ($nextSchedule)
                        · {{ $nextSchedule->starts_at->translatedFormat('d M · H:i') }}
                    @endif
                </div>

                <h2>{{ $event->title }}</h2>

                @if ($event->summary)
                    <p class="event-card__summary">{{ $event->summary }}</p>
                @endif

                <div class="event-card__footer">Ver actividad →</div>
            </a>
        @empty
            <p class="intro">No hay actividades publicadas en este momento.</p>
        @endforelse
    </div>

    <div style="margin-top: 42px;">
        {{ $events->links() }}
    </div>
@endsection