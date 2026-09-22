<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Centro Cultural Domingo Soler' }}</title>
    <meta name="description" content="{{ $description ?? 'Cartelera del Centro Cultural Domingo Soler' }}">

    <style>
        :root {
            --ink: #1f1d1b;
            --paper: #f4f0e8;
            --red: #a72c24;
            --line: #d6cfc2;
            --muted: #706a61;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: Georgia, "Times New Roman", serif;
        }

        a { color: inherit; text-decoration: none; }

        .shell {
            width: min(1160px, calc(100% - 40px));
            margin: 0 auto;
        }

        .site-header {
            border-bottom: 1px solid var(--line);
            padding: 22px 0;
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            align-items: center;
        }

        .brand {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .brand small {
            display: block;
            color: var(--muted);
            font-family: Arial, sans-serif;
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .15em;
            margin-top: 4px;
        }

        .nav-link {
            color: var(--red);
            font-family: Arial, sans-serif;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        main { padding: 64px 0 80px; }

        .eyebrow {
            color: var(--red);
            font-family: Arial, sans-serif;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .14em;
            margin: 0 0 12px;
            text-transform: uppercase;
        }

        h1, h2, h3, p { margin-top: 0; }

        h1 {
            font-size: clamp(2.4rem, 6vw, 5.6rem);
            font-weight: 400;
            letter-spacing: -.05em;
            line-height: .95;
            max-width: 900px;
        }

        h2 {
            font-size: clamp(1.6rem, 3vw, 2.5rem);
            font-weight: 400;
            line-height: 1;
        }

        .intro {
            color: var(--muted);
            font-size: 1.12rem;
            line-height: 1.6;
            max-width: 650px;
        }

        .event-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, 1fr);
            margin-top: 48px;
        }

        .event-card {
            border: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            min-height: 340px;
            padding: 24px;
            transition: background .2s ease, transform .2s ease;
        }

        .event-card:hover {
            background: #ebe4d9;
            transform: translateY(-3px);
        }

        .event-card__meta,
        .schedule-meta,
        .detail-meta {
            color: var(--muted);
            font-family: Arial, sans-serif;
            font-size: .74rem;
            letter-spacing: .05em;
            line-height: 1.5;
            text-transform: uppercase;
        }

        .event-card h2 {
            font-size: 2rem;
            margin: 22px 0 16px;
        }

        .event-card__summary {
            color: #4e4941;
            font-size: 1rem;
            line-height: 1.55;
        }

        .event-card__footer {
            color: var(--red);
            font-family: Arial, sans-serif;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin-top: auto;
            text-transform: uppercase;
        }

        .detail {
            display: grid;
            gap: 64px;
            grid-template-columns: minmax(0, 1.2fr) minmax(320px, .8fr);
        }

        .detail-copy {
            color: #4e4941;
            font-size: 1.08rem;
            line-height: 1.75;
            white-space: normal;
        }

        .schedule-panel {
            border-top: 3px solid var(--red);
            padding-top: 20px;
        }

        .schedule-card {
            border-bottom: 1px solid var(--line);
            padding: 20px 0;
        }

        .schedule-card:last-child { border-bottom: 0; }

        .schedule-card h3 {
            font-size: 1.3rem;
            font-weight: 400;
            margin: 8px 0;
        }

        .price {
            font-family: Arial, sans-serif;
            font-size: .9rem;
            font-weight: 700;
            margin: 12px 0 0;
        }

        .button {
            background: var(--red);
            border: 0;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-family: Arial, sans-serif;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin-top: 18px;
            padding: 14px 16px;
            text-transform: uppercase;
        }

        .button:hover { background: #7e211b; }

        .form-card {
            background: #ebe4d9;
            margin-top: 18px;
            padding: 22px;
        }

        .form-card h3 { font-size: 1.3rem; font-weight: 400; }

        label {
            display: block;
            font-family: Arial, sans-serif;
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .05em;
            margin: 16px 0 7px;
            text-transform: uppercase;
        }

        input, textarea {
            background: #fffdf8;
            border: 1px solid #bcb3a5;
            border-radius: 0;
            font: inherit;
            padding: 11px;
            width: 100%;
        }

        textarea { min-height: 88px; resize: vertical; }

        .error {
            color: #8d1919;
            font-family: Arial, sans-serif;
            font-size: .8rem;
            margin-top: 5px;
        }

        .note {
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.5;
            margin-top: 16px;
        }

        .success {
            border-top: 3px solid var(--red);
            max-width: 730px;
            padding-top: 24px;
        }

        .folio {
            border: 1px solid var(--line);
            display: inline-block;
            font-family: Arial, sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .12em;
            margin: 10px 0 28px;
            padding: 14px 18px;
        }

        .site-footer {
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-family: Arial, sans-serif;
            font-size: .75rem;
            padding: 24px 0;
        }

        @media (max-width: 820px) {
            .event-grid, .detail { grid-template-columns: 1fr; }
            main { padding-top: 42px; }
        }

        @media (max-width: 520px) {
            .shell { width: min(100% - 28px, 1160px); }
            .header-inner { align-items: flex-start; flex-direction: column; }
            .event-card { min-height: 280px; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="shell header-inner">
            <a class="brand" href="{{ route('cartelera.index') }}">
                Centro Cultural Domingo Soler
                <small>Acapulco, Guerrero</small>
            </a>

            <a class="nav-link" href="{{ route('cartelera.index') }}">Cartelera</a>
        </div>
    </header>

    <main class="shell">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="shell">Centro Cultural Domingo Soler · Acapulco, Guerrero</div>
    </footer>
</body>
</html>