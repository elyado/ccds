@extends('layouts.app')

@section('title', 'El Soler | Centro Cultural Domingo Soler')
@section('meta_description', 'Conoce la historia, organización y espacios del Centro Cultural Domingo Soler, patrimonio cultural al servicio del pueblo de Acapulco.')
@section('meta_image', asset('images/centro-cultural-domingo-soler-fachada.png'))

@section('content')
<main class="soler-page">
  <header class="soler-hero">
    <div class="container soler-hero-grid">
      <div class="soler-hero-copy">
        <div class="eyebrow">Desde 1965 · Acapulco, Guerrero</div>
        <h1>Un espacio del pueblo de Acapulco</h1>
        <p>El Centro Cultural Domingo Soler es un inmueble de interés público dedicado al encuentro, la creación y la presentación de las artes.</p>
        <div class="soler-hero-actions">
          <a class="btn btn-primary" href="{{ route('cartelera.index') }}">Consultar cartelera</a>
          <a class="btn btn-outline" href="{{ route('home') }}#renta">Rentar un espacio</a>
        </div>
      </div>
      <figure class="soler-hero-image">
        <img src="{{ asset('images/centro-cultural-domingo-soler-fachada.png') }}" alt="Fachada del Centro Cultural Domingo Soler en Acapulco">
        <figcaption>Fachada del Centro Cultural Domingo Soler</figcaption>
      </figure>
    </div>
  </header>

  <section class="soler-history section">
    <div class="container soler-history-grid">
      <div>
        <div class="eyebrow">Historia y patrimonio</div>
        <h2 class="editorial-title">Una casa para la cultura</h2>
      </div>
      <div class="soler-history-copy">
        <p>El inmueble fue donado al pueblo de Acapulco por la Fundación Mary Street Jenkins en 1965. Su carácter de interés público define una vocación cultural vinculada con la ciudad y sus habitantes.</p>
        <p>Desde 1993 se encuentra en posesión y bajo la administración de la asociación civil <strong>Patronato Teatro Domingo Soler</strong>, responsable de su cuidado, funcionamiento y continuidad.</p>
      </div>
    </div>

    <div class="container soler-timeline" aria-label="Historia del Centro Cultural Domingo Soler">
      <article><strong>1965</strong><span>Donación del inmueble al pueblo de Acapulco.</span></article>
      <article><strong>1993</strong><span>El Patronato Teatro Domingo Soler asume su posesión y administración.</span></article>
      <article><strong>Hoy</strong><span>Continúa como espacio para actividades artísticas, culturales, académicas y comunitarias.</span></article>
    </div>
  </section>

  <section class="soler-spaces section" id="espacios">
    <div class="container">
      <div class="section-head">
        <div><div class="eyebrow">Infraestructura cultural</div><h2>Nuestros espacios</h2><p>Cuatro áreas con capacidades y configuraciones distintas para funciones, conciertos, conferencias, talleres y reuniones.</p></div>
        <a class="text-link" href="{{ route('home') }}#renta">Solicitar disponibilidad →</a>
      </div>

      <div class="soler-space-grid">
        <article class="soler-space-card soler-space-theatre">
          <div class="soler-space-heading"><span>01</span><div><div class="eyebrow">Sala principal</div><h3>Teatro Domingo Soler</h3></div></div>
          <div class="soler-capacity"><strong>200</strong><span>personas</span></div>
          <dl>
            <div><dt>Escenario</dt><dd>Boca de 8.35 a 10.42 m; altura de 6 m y fondo de 6.84 m.</dd></div>
            <div><dt>Iluminación</dt><dd>Tres varas: contra con 4 pares 64, cenital con 2 fresneles y frontal con 3 leekos.</dd></div>
            <div><dt>Equipamiento</dt><dd>Aire acondicionado, cámara negra, bocina, dos micrófonos inalámbricos y reproductor de CD.</dd></div>
          </dl>
          <a href="https://centroculturaldomingosoler.blogspot.com/p/teatro-domingo-soler.html" target="_blank" rel="noopener">Consultar ficha histórica →</a>
        </article>

        <article class="soler-space-card">
          <div class="soler-space-heading"><span>02</span><div><div class="eyebrow">Sala multiusos</div><h3>Sala Luis Zapata</h3></div></div>
          <div class="soler-capacity"><strong>80</strong><span>personas · auditorio</span></div>
          <dl>
            <div><dt>Área</dt><dd>72.50 m².</dd></div>
            <div><dt>Equipamiento</dt><dd>Aire acondicionado, internet, proyector, pizarrón blanco, bocina, micrófono y reproductor de CD.</dd></div>
            <div><dt>Montaje</dt><dd>80 sillas, mesa de 1.80 m con bambalina y cámara negra opcional.</dd></div>
          </dl>
          <a href="https://centroculturaldomingosoler.blogspot.com/p/sala-luis-zapata.html" target="_blank" rel="noopener">Consultar ficha histórica →</a>
        </article>

        <article class="soler-space-card">
          <div class="soler-space-heading"><span>03</span><div><div class="eyebrow">Espacio abierto</div><h3>Plazoleta del Fuego</h3></div></div>
          <div class="soler-capacity"><strong>300</strong><span>personas</span></div>
          <dl>
            <div><dt>Área</dt><dd>470 m².</dd></div>
            <div><dt>Vocación</dt><dd>Actividades al aire libre, encuentros, presentaciones y eventos de mayor aforo.</dd></div>
            <div><dt>Equipamiento</dt><dd>Bocina, un micrófono y reproductor de CD.</dd></div>
          </dl>
          <a href="https://centroculturaldomingosoler.blogspot.com/p/plazoleta-del-fuego.html" target="_blank" rel="noopener">Consultar ficha histórica →</a>
        </article>

        <article class="soler-space-card">
          <div class="soler-space-heading"><span>04</span><div><div class="eyebrow">Consulta y reunión</div><h3>Biblioteca</h3></div></div>
          <div class="soler-capacity"><strong>10</strong><span>personas</span></div>
          <dl>
            <div><dt>Acervo</dt><dd>400 libros especializados en teatro.</dd></div>
            <div><dt>Mobiliario</dt><dd>Mesa de 1.80 m y 8 sillas.</dd></div>
            <div><dt>Uso</dt><dd>Consulta, estudio, reuniones de trabajo y actividades de pequeño formato.</dd></div>
          </dl>
        </article>
      </div>
    </div>
  </section>

  <section class="soler-governance section">
    <div class="container soler-governance-grid">
      <div><div class="eyebrow">Organización</div><h2 class="editorial-title">Patronato Teatro Domingo Soler A.C.</h2><p>La mesa directiva articula la administración, operación técnica, comunicación y vinculación académica del Centro Cultural.</p></div>
      <ul aria-label="Cargos de la mesa directiva">
        <li>Presidencia de la Asociación</li><li>Vicepresidencia</li><li>Secretaría</li><li>Tesorería</li><li>Jefatura de Foro</li><li>Prensa y difusión</li><li>Vinculación Académica</li>
      </ul>
    </div>
  </section>

  <section class="soler-cta">
    <div class="container"><div><div class="eyebrow">Hazlo parte de tu proyecto</div><h2>Presenta, ensaya o reúnete en el Soler</h2></div><a class="btn btn-primary" href="{{ route('home') }}#renta">Consultar renta del espacio</a></div>
  </section>
</main>
@endsection
