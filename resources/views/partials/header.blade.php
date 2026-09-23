<header class="header">
  <div class="container nav">
    <a class="logo" href="{{ route('home') }}" aria-label="Centro Cultural Domingo Soler">
      <img src="{{ asset('images/logo-centro-cultural-domingo-soler-transparente.png') }}" alt="Centro Cultural Domingo Soler">
    </a>
    <nav class="nav-links" aria-label="Principal">
      <a class="{{ request()->routeIs('cartelera.*') ? 'active' : '' }}" href="{{ route('cartelera.index') }}">Cartelera</a>
      <a class="{{ request()->routeIs('el-soler') ? 'active' : '' }}" href="{{ route('el-soler') }}">El Soler</a>
      <a href="{{ route('home') }}#renta">Renta del espacio</a>
      <a href="{{ route('home') }}#galeria">Galería</a>
      <a href="{{ route('home') }}#apoya">Apoya al Soler</a>
      <a href="#contacto">Contacto</a>
    </nav>
    <a class="btn btn-primary" href="{{ route('cartelera.index') }}">🎟 Comprar boletos</a>
    <button class="menu-btn btn btn-outline" id="menuBtn" aria-expanded="false">Menú</button>
  </div>
</header>
