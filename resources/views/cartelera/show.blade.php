@extends('layouts.app')

@php
  $categoryName = $event->category?->title ?? $event->category?->name ?? $event->discipline ?? 'Evento';
  $heroImage = $event->coverMedia?->url ?? $event->posterMedia?->url ?? asset('images/event-placeholder.svg');
  $mobileImage = $event->mobileMedia?->url ?? $event->posterMedia?->url ?? $heroImage;
  $socialImage = $event->seoImageMedia?->url ?? $heroImage;
  $firstSchedule = $event->schedules->first();
  $eventUrl = route('cartelera.show', $event->slug);
  $encodedUrl = rawurlencode($eventUrl);
  $encodedShareText = rawurlencode($event->title . ' — Centro Cultural Domingo Soler');
@endphp

@section('title', ($event->seo_title ?: $event->title) . ' | Centro Cultural Domingo Soler')
@section('meta_description', $event->seo_description ?: $event->summary)
@section('meta_image', $socialImage)

@section('content')
<main class="event-detail">
  <nav class="event-breadcrumb container" aria-label="Navegación secundaria">
    <a href="{{ route('home') }}">Inicio</a><span>/</span>
    <a href="{{ route('cartelera.index') }}">Cartelera</a><span>/</span>
    <span aria-current="page">{{ $event->title }}</span>
  </nav>

  <header class="event-detail-hero">
    <div class="container event-detail-hero-grid">
      <div class="event-detail-copy">
        <span class="tag">{{ $categoryName }}</span>
        <h1>{{ $event->title }}</h1>
        @if ($event->summary)<p class="event-detail-summary">{{ $event->summary }}</p>@endif

        <div class="event-detail-highlights">
          @if ($firstSchedule)
            <span><small>Próxima función</small>{{ $firstSchedule->starts_at->locale('es')->translatedFormat('l j \d\e F, Y') }}</span>
            <span><small>Hora</small>{{ $firstSchedule->starts_at->format('h:i A') }}</span>
            <span><small>Lugar</small>{{ $firstSchedule->venue?->title ?? $firstSchedule->venue?->name ?? 'Centro Cultural Domingo Soler' }}</span>
          @endif
        </div>

        <div class="event-detail-actions">
          @if ($event->schedules->isNotEmpty())
            <a class="btn btn-primary" href="#funciones">Ver funciones y precios</a>
          @endif
          <a class="btn btn-outline event-back-button" href="{{ route('cartelera.index') }}">Volver a cartelera</a>
        </div>
      </div>

      <figure class="event-detail-cover">
        <picture>
          <source media="(max-width: 720px)" srcset="{{ $mobileImage }}">
          <img src="{{ $heroImage }}" alt="{{ $event->coverMedia?->alt_text ?? $event->posterMedia?->alt_text ?? $event->title }}">
        </picture>
        @if ($event->coverMedia?->credit ?? $event->posterMedia?->credit)
          <figcaption>Foto: {{ $event->coverMedia?->credit ?? $event->posterMedia?->credit }}</figcaption>
        @endif
      </figure>
    </div>
  </header>

  <div class="container event-detail-layout">
    <article class="event-detail-content">
      <section class="event-share" aria-labelledby="share-event-title">
        <strong id="share-event-title">Compartir evento</strong>
        <div class="event-share-actions">
          <a href="https://wa.me/?text={{ $encodedShareText }}%20{{ $encodedUrl }}" target="_blank" rel="noopener" aria-label="Compartir por WhatsApp">WhatsApp</a>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener" aria-label="Compartir en Facebook">Facebook</a>
          <a href="https://twitter.com/intent/tweet?text={{ $encodedShareText }}&url={{ $encodedUrl }}" target="_blank" rel="noopener" aria-label="Compartir en X">X</a>
          <button type="button" data-share-native data-share-title="{{ $event->title }}" data-share-text="{{ $event->summary }}" data-share-url="{{ $eventUrl }}">Más opciones</button>
          <button type="button" data-copy-link data-share-url="{{ $eventUrl }}">Copiar enlace</button>
        </div>
      </section>

      @if ($event->body)
        <section>
          <div class="eyebrow">Sobre el evento</div>
          <h2>{{ $event->event_type ?: 'Información' }}</h2>
          <div class="event-prose">{!! nl2br(e($event->body)) !!}</div>
        </section>
      @endif

      @if ($event->participants->isNotEmpty())
        <section class="event-participants">
          <div class="eyebrow">Participantes</div>
          <h2>Créditos</h2>
          <ul>
            @foreach ($event->participants as $participant)
              @php
                $participantName = $participant->credit_text
                  ?: $participant->person?->name
                  ?: $participant->organization?->name;
              @endphp
              @if ($participantName)
                <li><strong>{{ $participantName }}</strong><span>{{ $participant->role }}</span></li>
              @endif
            @endforeach
          </ul>
        </section>
      @endif

      @if ($event->video_url)
        <p><a class="text-link" href="{{ $event->video_url }}" target="_blank" rel="noopener">Ver video del evento →</a></p>
      @endif
    </article>

    <aside class="event-detail-aside" id="funciones">
      <div class="schedule-panel">
        <div class="eyebrow">Fechas disponibles</div>
        <h2>Funciones y acceso</h2>

        @forelse ($event->schedules as $schedule)
          @php
            $venueName = $schedule->venue?->title ?? $schedule->venue?->name ?? 'Centro Cultural Domingo Soler';
            $layoutName = $schedule->venueLayout?->title ?? $schedule->venueLayout?->name;
            $scheduleUrl = $schedule->cta_url ?: $event->cta_url;
            $calendarStart = $schedule->starts_at->copy()->utc();
            $calendarEnd = ($schedule->ends_at ?? $schedule->starts_at->copy()->addHours(2))->copy()->utc();
            $googleCalendarUrl = 'https://calendar.google.com/calendar/render?' . http_build_query([
              'action' => 'TEMPLATE',
              'text' => $event->title,
              'dates' => $calendarStart->format('Ymd\\THis\\Z') . '/' . $calendarEnd->format('Ymd\\THis\\Z'),
              'details' => ($event->summary ? $event->summary . "\n\n" : '') . $eventUrl,
              'location' => $venueName,
            ]);
          @endphp
          <section class="schedule-card">
            <div class="schedule-date">
              <strong>{{ $schedule->starts_at->locale('es')->translatedFormat('j M') }}</strong>
              <span>{{ $schedule->starts_at->locale('es')->translatedFormat('l') }}</span>
            </div>
            <div class="schedule-info">
              <h3>{{ $schedule->starts_at->format('h:i A') }}</h3>
              <p>{{ $venueName }}@if($layoutName) · {{ $layoutName }}@endif</p>

              @if ($schedule->ticketTypes->isNotEmpty())
                <ul class="ticket-list">
                  @foreach ($schedule->ticketTypes as $ticket)
                    <li>
                      <span>{{ $ticket->name }}</span>
                      <strong>
                        @if ($ticket->access_kind === 'complimentary' || is_null($ticket->price_amount))
                          Gratuito
                        @else
                          ${{ number_format((float) $ticket->price_amount, 0) }} {{ $ticket->currency }}
                        @endif
                      </strong>
                    </li>
                  @endforeach
                </ul>
              @elseif ($event->is_free || is_null($schedule->price_amount))
                <p class="schedule-price">Entrada libre</p>
              @else
                <p class="schedule-price">{{ $schedule->price_label ?: 'Acceso' }}: ${{ number_format((float) $schedule->price_amount, 0) }} {{ $schedule->currency }}</p>
              @endif

              @if ($schedule->public_note)<p class="schedule-note">{{ $schedule->public_note }}</p>@endif

              @if ($scheduleUrl)
                <a class="btn btn-primary schedule-cta" href="{{ $scheduleUrl }}" target="_blank" rel="noopener">
                  {{ $schedule->cta_label ?: $event->cta_label ?: 'Reservar' }}
                </a>
              @endif

              <div class="schedule-calendar-actions">
                <a href="{{ route('cartelera.calendar', ['slug' => $event->slug, 'schedule' => $schedule]) }}">＋ Agregar a agenda</a>
                <a href="{{ $googleCalendarUrl }}" target="_blank" rel="noopener">Google Calendar</a>
              </div>
            </div>
          </section>
        @empty
          <p class="empty-state">Por ahora no hay funciones futuras disponibles.</p>
        @endforelse

        @if ($event->access_notes)
          <div class="access-note"><strong>Información de acceso</strong><p>{{ $event->access_notes }}</p></div>
        @endif
      </div>

      <dl class="event-facts">
        @if ($event->duration_minutes)<div><dt>Duración</dt><dd>{{ $event->duration_minutes }} minutos</dd></div>@endif
        @if ($event->recommended_age)<div><dt>Edad recomendada</dt><dd>{{ $event->recommended_age }}</dd></div>@endif
        @if ($event->language)<div><dt>Idioma</dt><dd>{{ $event->language }}</dd></div>@endif
        @if ($event->target_audience)<div><dt>Público</dt><dd>{{ $event->target_audience }}</dd></div>@endif
        @if ($event->modality)<div><dt>Modalidad</dt><dd>{{ match($event->modality) { 'in_person' => 'Presencial', 'online' => 'En línea', 'hybrid' => 'Híbrida', default => $event->modality } }}</dd></div>@endif
      </dl>
    </aside>
  </div>
</main>
@endsection
