<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Vital Clean')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Paleta de colores tomada del SRS v1.1 §12 (Azul industrial, Rojo, Amarillo, Azul claro). --}}
    <style>
        :root {
            --azul: #1B4F8A;
            --azul-claro: #2980B9;
            --blanco: #FFFFFF;
            --gris-claro: #F5F5F5;
            --rojo: #C0392B;
            --amarillo: #F39C12;
            --texto: #1f2937;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            background: var(--gris-claro);
            color: var(--texto);
        }
        header.app-header {
            background: var(--azul);
            color: var(--blanco);
            padding: .9rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header.app-header a { color: var(--blanco); text-decoration: none; font-weight: 600; }
        header.app-header .brand { font-size: 1.1rem; letter-spacing: .02em; }
        main { max-width: 960px; margin: 0 auto; padding: 1.5rem; }
        .card {
            background: var(--blanco);
            border-radius: .5rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        .btn {
            display: inline-block;
            background: var(--azul);
            color: var(--blanco);
            border: none;
            padding: .6rem 1.1rem;
            border-radius: .375rem;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover { background: var(--azul-claro); }
        .badge { display: inline-block; padding: .15rem .5rem; border-radius: 999px; font-size: .75rem; color: var(--blanco); }
        .badge-admin { background: var(--azul); }
        .badge-operador { background: var(--azul-claro); }
        .badge-vendedor { background: var(--amarillo); }
    </style>
</head>
<body>
    @auth
        <header class="app-header">
            <span class="brand">💧 Vital Clean</span>
            <span style="display:flex; align-items:center; gap:.75rem;">
                <span>{{ auth()->user()->nombre_completo ?? auth()->user()->username }}</span>
                <span class="badge badge-{{ strtolower(auth()->user()->rol) }}">{{ auth()->user()->rol }}</span>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn" style="background:var(--rojo);">Salir</button>
                </form>
            </span>
        </header>
    @endauth

    <main>
        @yield('content')
    </main>
</body>
</html>
