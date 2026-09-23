@extends('layouts.app')

@section('title', 'Centro Cultural Domingo Soler')
@section('meta_description', 'Cartelera, boletos, renta de espacio, galería y apoyo al Centro Cultural Domingo Soler en Acapulco.')

@section('content')
<main id="top">
  <section class="hero hero-slider" aria-label="Próximos eventos">
    @forelse ($sliderEvents as $event)
      @php
        $slideSchedule = $event->schedules->first();
        $slideTicketPrice = $slideSchedule?->ticketTypes
            ?->where('access_kind', 'paid')->whereNotNull('price_amount')->min('price_amount');
        $slidePrice = $slideTicketPrice ?? $slideSchedule?->price_amount ?? $event->reference_price_amount;
        $slideVenue = $slideSchedule?->venue?->title ?? $slideSchedule?->venue?->name ?? 'Centro Cultural Domingo Soler';
        $slideImage = $event->coverMedia?->url ?? $event->posterMedia?->url ?? asset('images/event-placeholder.svg');
        $slideMobileImage = $event->mobileMedia?->url ?? $slideImage;
      @endphp

      <article class="hero-slide {{ $loop->first ? 'is-active' : '' }}" data-hero-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
        <div class="container">
          <div class="hero-copy">
            <div class="eyebrow">{{ $event->category?->title ?? $event->category?->name ?? $event->discipline ?? 'Próximo evento' }}</div>
            <h1 id="hero-title-{{ $event->id }}">{{ $event->title }}</h1>

            <div class="hero-meta" aria-label="Información del evento">
              @if ($slideSchedule)
                <span><span class="icon" aria-hidden="true">▣</span> {{ $slideSchedule->starts_at->locale('es')->translatedFormat('l j \d\e F') }}</span>
                <span><span class="icon" aria-hidden="true">◷</span> {{ $slideSchedule->starts_at->format('h:i A') }}</span>
              @endif
              <span><span class="icon" aria-hidden="true">⌖</span> {{ $slideVenue }}</span>
            </div>

            @if ($event->summary)<p class="hero-desc">{{ $event->summary }}</p>@endif

            <div class="hero-actions">
              <div class="hero-price">
                @if ($event->is_free || ! $slidePrice)
                  <small>Acceso</small><strong>Gratuito</strong>
                @else
                  <small>{{ $event->reference_price_label ?: 'Desde' }}</small>
                  <strong>${{ number_format((float) $slidePrice, 0) }} <span>{{ $slideSchedule?->currency ?? 'MXN' }}</span></strong>
                @endif
              </div>
              <a class="btn btn-primary" href="{{ route('cartelera.show', $event->slug) }}">{{ $event->cta_label ?: 'Ver evento' }}</a>
              <a class="text-link hero-more" href="{{ route('cartelera.index') }}">Ver cartelera completa →</a>
            </div>
          </div>

          <figure class="hero-event-media">
            <picture>
              <source media="(max-width: 700px)" srcset="{{ $slideMobileImage }}">
              <img src="{{ $slideImage }}" alt="{{ $event->coverMedia?->alt_text ?? $event->posterMedia?->alt_text ?? $event->title }}">
            </picture>
            <figcaption class="hero-event-caption">Próximo evento · {{ $slideVenue }}</figcaption>
          </figure>
        </div>
      </article>
    @empty
      <div class="container"><div class="hero-copy"><div class="eyebrow">Centro Cultural Domingo Soler</div><h1>Próximamente nueva cartelera</h1><p class="hero-desc">Estamos preparando nuevas funciones y actividades para la comunidad.</p></div></div>
    @endforelse

    @if ($sliderEvents->count() > 1)
      <div class="hero-slider-controls container" aria-label="Controles del slider">
        <button type="button" class="hero-arrow" data-hero-prev aria-label="Evento anterior">←</button>
        <div class="hero-dots">
          @foreach ($sliderEvents as $event)
            <button type="button" class="hero-dot {{ $loop->first ? 'is-active' : '' }}" data-hero-dot="{{ $loop->index }}" aria-label="Mostrar {{ $event->title }}"></button>
          @endforeach
        </div>
        <button type="button" class="hero-arrow" data-hero-next aria-label="Evento siguiente">→</button>
      </div>
    @endif
  </section>

  <section class="section cartelera" id="cartelera">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Cartelera</h2>
          <div class="filters">
            <button class="filter active" data-filter="all">Todos</button>
            @foreach ($categories as $category)
              <button class="filter" data-filter="{{ \Illuminate\Support\Str::slug($category->slug ?? $category->title ?? $category->name) }}">
                {{ $category->title ?? $category->name }}
              </button>
            @endforeach
          </div>
        </div>
        <a class="text-link" href="{{ route('cartelera.index') }}">Ver cartelera completa →</a>
      </div>

      <div class="event-grid" id="eventGrid">
        @forelse ($upcomingEvents as $event)
          @php
            $schedule = $event->schedules->first();
            $ticketPrice = $schedule?->ticketTypes
                ?->where('access_kind', 'paid')->whereNotNull('price_amount')->min('price_amount');
            $price = $ticketPrice ?? $schedule?->price_amount ?? $event->reference_price_amount;
            $categoryName = $event->category?->title ?? $event->category?->name ?? $event->discipline ?? 'Evento';
            $categorySlug = \Illuminate\Support\Str::slug($event->category?->slug ?? $categoryName);
            $venueName = $schedule?->venue?->title ?? $schedule?->venue?->name ?? 'Domingo Soler';
            $cardImage = $event->posterMedia?->url ?? $event->coverMedia?->url ?? asset('images/event-placeholder.svg');
          @endphp

          <article class="event-card" data-cat="{{ $categorySlug }}">
            <figure><img src="{{ $cardImage }}" alt="{{ $event->posterMedia?->alt_text ?? $event->coverMedia?->alt_text ?? $event->title }}"></figure>
            <div class="event-body">
              <span class="tag">{{ $categoryName }}</span>
              <h3 class="event-title">{{ $event->title }}</h3>
              <div class="event-meta">
                @if ($schedule)
                  <span>▣ {{ $schedule->starts_at->locale('es')->translatedFormat('l j \d\e F') }}</span>
                  <span>◷ {{ $schedule->starts_at->format('h:i A') }} · {{ $venueName }}</span>
                @endif
              </div>
              <div class="event-bottom">
                <div class="event-price">
                  @if ($event->is_free || ! $price)
                    <strong>Entrada libre</strong>
                  @else
                    Desde <strong>${{ number_format((float) $price, 0) }}</strong>
                  @endif
                </div>
                <a class="btn btn-outline" href="{{ route('cartelera.show', $event->slug) }}">Ver evento</a>
              </div>
            </div>
          </article>
        @empty
          <p class="empty-state">No hay más eventos próximos publicados por ahora.</p>
        @endforelse
      </div>
    </div>
  </section>

  <section class="support-wrap" id="apoya">
    <div class="container">
      <div class="support">
        <div class="support-copy">
          <div class="support-icon">♥</div>
          <div><h3>Apoya al Soler</h3><p>Tu aportación mantiene viva la cultura independiente en Acapulco.</p></div>
        </div>
        <div class="donation-actions">
          <button class="amount">$10</button><button class="amount active">$50</button>
          <button class="amount">$100</button><button class="amount">Otro monto</button>
          <button class="btn btn-gold">Aportar ahora ♡</button>
        </div>
      </div>
    </div>
  </section>

  <section class="features" id="soler">
    <div class="container">
      <div class="section-head"><div><div class="eyebrow">Más que una sala</div><h2>Un espacio para encontrarnos</h2></div></div>
      <div class="feature-grid">
        <article class="feature">
          <div class="feature-media"><img src="{{ asset('images/event-placeholder.svg') }}" alt="Comunidad del Centro Cultural Domingo Soler"></div>
          <div class="feature-body"><h3>El Soler</h3><p>Espacio cultural independiente en Acapulco dedicado a las artes escénicas, la música, la formación y la comunidad.</p></div>
        </article>
        <article class="feature" id="renta">
          <div class="feature-media"><img src="{{ asset('images/event-placeholder.svg') }}" alt="Espacios del Domingo Soler"></div>
          <div class="feature-body"><h3>Renta del espacio</h3><p>Disponibilidad para ensayos, presentaciones, talleres, reuniones y eventos privados.</p><a class="btn btn-outline" href="#contacto">Solicitar disponibilidad</a></div>
        </article>
        <article class="feature" id="galeria">
          <div class="feature-body"><h3>Galería de las Artes Acapulqueñas</h3><p>Un punto de encuentro y venta para obra producida por artistas locales.</p></div>
        </article>
      </div>
    </div>
  </section>

  @if ($archiveEvents->isNotEmpty())
    <section class="archive" id="archivo">
      <div class="container">
        <div class="section-head"><div><div class="eyebrow">Funciones pasadas</div><h2>Archivo / Memoria del Soler</h2></div></div>
        <div class="archive-grid">
          @foreach ($archiveEvents as $event)
            @php
              $lastSchedule = $event->schedules->first();
              $archiveImage = $event->posterMedia?->url ?? $event->coverMedia?->url ?? asset('images/event-placeholder.svg');
            @endphp
            <a class="archive-card" href="{{ route('cartelera.show', $event->slug) }}">
              <img src="{{ $archiveImage }}" alt="{{ $event->posterMedia?->alt_text ?? $event->coverMedia?->alt_text ?? $event->title }}">
              <div class="body"><h4>{{ $event->title }}</h4><small>{{ ($event->finished_at ?? $lastSchedule?->starts_at)?->locale('es')->translatedFormat('F Y') }}</small></div>
            </a>
          @endforeach
        </div>
      </div>
    </section>
  @endif
</main>
@endsection
