@extends('layouts.app')

@section('title', 'Cartelera | Centro Cultural Domingo Soler')
@section('meta_description', 'Consulta los próximos conciertos, funciones, talleres y actividades del Centro Cultural Domingo Soler en Acapulco.')

@section('content')
<main class="billboard-page">
  <header class="billboard-header">
    <div class="container">
      <div class="eyebrow">Programación cultural en Acapulco</div>
      <h1>Cartelera</h1>
      <p>Conciertos, teatro, lecturas, cine, talleres y encuentros para todos los públicos.</p>
    </div>
  </header>

  <section class="container billboard-content">
    <form class="billboard-filters" action="{{ route('cartelera.index') }}" method="GET" role="search">
      <label class="billboard-search">
        <span>Buscar evento</span>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Título, disciplina o actividad">
      </label>

      <label>
        <span>Categoría</span>
        <select name="category">
          <option value="">Todas las categorías</option>
          @foreach ($categories as $category)
            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
              {{ $category->title ?? $category->name }}
            </option>
          @endforeach
        </select>
      </label>

      <label>
        <span>Fecha</span>
        <select name="period">
          <option value="">Todos los próximos</option>
          <option value="week" @selected(request('period') === 'week')>Esta semana</option>
          <option value="month" @selected(request('period') === 'month')>Este mes</option>
        </select>
      </label>

      <button class="btn btn-primary" type="submit">Aplicar filtros</button>
      @if (request()->hasAny(['q', 'category', 'period']))
        <a class="billboard-clear" href="{{ route('cartelera.index') }}">Limpiar</a>
      @endif
    </form>

    <div class="billboard-results-head">
      <p>
        <strong>{{ $events->total() }}</strong>
        {{ $events->total() === 1 ? 'evento encontrado' : 'eventos encontrados' }}
      </p>
    </div>

    <div class="billboard-grid">
      @forelse ($events as $event)
        @php
          $schedule = $event->schedules->first();
          $categoryName = $event->category?->title ?? $event->category?->name ?? $event->discipline ?? 'Evento';
          $image = $event->posterMedia?->url ?? $event->coverMedia?->url ?? asset('images/event-placeholder.svg');
          $venue = $schedule?->venue?->title ?? $schedule?->venue?->name ?? 'Centro Cultural Domingo Soler';
          $paidPrices = $schedule
            ? $schedule->ticketTypes->where('access_kind', 'paid')->whereNotNull('price_amount')->pluck('price_amount')
            : collect();
          $price = $paidPrices->min() ?? $schedule?->price_amount ?? $event->reference_price_amount;
        @endphp

        <article class="billboard-card">
          <a class="billboard-card-image" href="{{ route('cartelera.show', $event->slug) }}">
            <img src="{{ $image }}" alt="{{ $event->posterMedia?->alt_text ?? $event->coverMedia?->alt_text ?? $event->title }}">
            @if ($event->is_featured)<span class="billboard-featured">Destacado</span>@endif
          </a>

          <div class="billboard-card-body">
            <span class="tag">{{ $categoryName }}</span>
            <h2><a href="{{ route('cartelera.show', $event->slug) }}">{{ $event->title }}</a></h2>
            @if ($event->summary)<p>{{ \Illuminate\Support\Str::limit($event->summary, 125) }}</p>@endif

            @if ($schedule)
              <div class="billboard-card-meta">
                <span>{{ $schedule->starts_at->locale('es')->translatedFormat('l j \d\e F') }}</span>
                <span>{{ $schedule->starts_at->format('h:i A') }} · {{ $venue }}</span>
              </div>
            @endif

            <div class="billboard-card-footer">
              <div class="billboard-price">
                @if ($event->is_free)
                  <small>Acceso</small><strong>Entrada libre</strong>
                @elseif ($paidPrices->isNotEmpty() || $price)
                  <small>Desde</small><strong>${{ number_format((float) $price, 0) }} <span>{{ $schedule?->currency ?? 'MXN' }}</span></strong>
                @else
                  <small>Acceso</small><strong>Cooperación</strong>
                @endif
              </div>
              <a class="text-link" href="{{ route('cartelera.show', $event->slug) }}">Ver evento →</a>
            </div>
          </div>
        </article>
      @empty
        <div class="billboard-empty">
          <h2>No encontramos eventos</h2>
          <p>Prueba con otra categoría, periodo o palabra de búsqueda.</p>
          <a class="btn btn-outline" href="{{ route('cartelera.index') }}">Ver toda la cartelera</a>
        </div>
      @endforelse
    </div>

    @if ($events->hasPages())
      <nav class="billboard-pagination" aria-label="Páginas de la cartelera">
        {{ $events->links() }}
      </nav>
    @endif
  </section>
</main>
@endsection
