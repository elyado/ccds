@extends('layouts.public', [
    'title' => $event->title . ' · Centro Cultural Domingo Soler',
    'description' => $event->summary,
])

@section('content')
    <p class="eyebrow">
        <a href="{{ route('cartelera.index') }}">← Cartelera</a>
    </p>

    <div class="detail">
        <article>
            <div class="detail-meta">
                {{ $event->discipline ?: 'Actividad cultural' }}
                @if ($event->recommended_age)
                    · {{ $event->recommended_age }}
                @endif
            </div>

            <h1>{{ $event->title }}</h1>

            @if ($event->summary)
                <p class="intro">{{ $event->summary }}</p>
            @endif

            @if ($event->body)
                <div class="detail-copy">
                    {!! nl2br(e($event->body)) !!}
                </div>
            @endif
        </article>

        <aside class="schedule-panel">
            <p class="eyebrow">Funciones disponibles</p>

            @forelse ($event->schedules as $schedule)
                @php
                    $reserved = $schedule->reservations()
                        ->where('status', 'active')
                        ->sum('quantity');

                    $available = max(0, $schedule->public_capacity - $reserved);
                    $isFree = $event->is_free || (float) $schedule->price_amount <= 0;
                @endphp

                <section class="schedule-card">
                    <div class="schedule-meta">
                        {{ $schedule->starts_at->translatedFormat('l d \d\e F · H:i') }}
                    </div>

                    <h3>
                        {{ $schedule->venue?->name ?? 'Centro Cultural Domingo Soler' }}
                    </h3>

                    <p class="price">
                        @if ($isFree)
                            Entrada libre
                        @else
                            Desde ${{ number_format((float) $schedule->price_amount, 2) }} MXN
                        @endif
                    </p>

                    @if ($available > 0)
                        <p class="note">
                            {{ $available }} {{ $available === 1 ? 'lugar disponible' : 'lugares disponibles' }}
                        </p>

                        <form
                            class="form-card"
                            method="POST"
                            action="{{ route('cartelera.reservations.store', [
                                'slug' => $event->slug,
                                'schedule' => $schedule->id,
                            ]) }}"
                        >
                            @csrf

                            <h3>Solicitar reserva</h3>

                            <label for="name-{{ $schedule->id }}">Nombre completo</label>
                            <input
                                id="name-{{ $schedule->id }}"
                                name="customer_name"
                                value="{{ old('customer_name') }}"
                                required
                            >
                            @error('customer_name')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <label for="phone-{{ $schedule->id }}">WhatsApp o teléfono</label>
                            <input
                                id="phone-{{ $schedule->id }}"
                                name="customer_phone"
                                value="{{ old('customer_phone') }}"
                                required
                            >
                            @error('customer_phone')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <label for="email-{{ $schedule->id }}">Correo <span style="font-weight:400;">(opcional)</span></label>
                            <input
                                id="email-{{ $schedule->id }}"
                                name="customer_email"
                                type="email"
                                value="{{ old('customer_email') }}"
                            >
                            @error('customer_email')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <label for="quantity-{{ $schedule->id }}">Número de asistentes</label>
                            <input
                                id="quantity-{{ $schedule->id }}"
                                name="quantity"
                                type="number"
                                min="1"
                                max="{{ min(10, $available) }}"
                                value="{{ old('quantity', 1) }}"
                                required
                            >
                            @error('quantity')
                                <div class="error">{{ $message }}</div>
                            @enderror

                            <label for="note-{{ $schedule->id }}">Nota <span style="font-weight:400;">(opcional)</span></label>
                            <textarea
                                id="note-{{ $schedule->id }}"
                                name="internal_note"
                                placeholder="Ejemplo: requiero accesibilidad, llegaré tarde…"
                            >{{ old('internal_note') }}</textarea>

                            <button class="button" type="submit">
                                Solicitar reserva
                            </button>

                            <p class="note">
                                Esta solicitud no es un pago. El equipo del Soler confirmará
                                disponibilidad e indicaciones por WhatsApp.
                            </p>
                        </form>
                    @else
                        <p class="note">Esta función ya no tiene lugares disponibles.</p>
                    @endif
                </section>
            @empty
                <p class="note">No hay funciones disponibles para esta actividad.</p>
            @endforelse
        </aside>
    </div>
@endsection