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

        /* Identidad de marca — ver resources/views/partials/logo.blade.php */
        .vc-logo { display: flex; align-items: center; gap: .45rem; }
        .vc-logo-icon { flex-shrink: 0; display: block; }
        .vc-logo-text { display: flex; align-items: baseline; gap: .3rem; line-height: 1; }
        .vc-logo-super { display: none; }
        .vc-logo-main { font-size: 1.05rem; font-weight: 900; letter-spacing: .02em; font-family: Arial, "Helvetica Neue", sans-serif; }
        .vc-logo-script { font-size: .95rem; font-style: italic; font-family: "Brush Script MT", "Segoe Script", cursive; }
        .layout { display: flex; align-items: flex-start; }
        nav.sidebar {
            width: 210px;
            flex-shrink: 0;
            background: var(--blanco);
            min-height: calc(100vh - 58px);
            padding: 1.25rem 0;
            border-right: 1px solid #e5e7eb;
        }
        nav.sidebar .section-title {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #9ca3af;
            padding: .5rem 1.25rem .25rem;
        }
        nav.sidebar a {
            display: block;
            padding: .55rem 1.25rem;
            color: var(--texto);
            text-decoration: none;
            font-size: .92rem;
        }
        nav.sidebar a:hover { background: var(--gris-claro); }
        nav.sidebar a.active { background: var(--gris-claro); font-weight: 600; color: var(--azul); border-right: 3px solid var(--azul); }
        .content { flex: 1; padding: 1.5rem; max-width: 1080px; }
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
        .alert { padding: .75rem 1rem; border-radius: .375rem; margin-bottom: 1rem; font-size: .9rem; }
        .alert-status { background: #eafaf1; border: 1px solid #28a745; color: #1e7e34; }
        .alert-error { background: #fdecea; border: 1px solid var(--rojo); color: var(--rojo); }
        table.data-table { width: 100%; border-collapse: collapse; background: var(--blanco); border-radius: .5rem; overflow: hidden; }
        table.data-table th, table.data-table td { text-align: left; padding: .65rem .9rem; border-bottom: 1px solid #eee; font-size: .9rem; }
        table.data-table th { background: var(--azul); color: var(--blanco); font-weight: 600; }
        table.data-table tr:hover td { background: #fafafa; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .page-header h1 { margin: 0; font-size: 1.3rem; color: var(--azul); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .3rem; }
        .form-group input[type=text], .form-group input[type=email], .form-group input[type=number],
        .form-group select, .form-group textarea {
            width: 100%; max-width: 420px; padding: .55rem .7rem; font-size: .95rem;
            border: 1px solid #d1d5db; border-radius: .375rem;
        }
        .form-actions { margin-top: 1.25rem; display: flex; gap: .6rem; align-items: center; }
        .btn-secondary { background: #6b7280; }
        .btn-secondary:hover { background: #4b5563; }
        .btn-danger { background: var(--rojo); }
        .btn-danger:hover { opacity: .9; }
        .btn-sm { padding: .3rem .7rem; font-size: .85rem; }
        .field-error { color: var(--rojo); font-size: .8rem; margin-top: .2rem; }
        .actions-cell { display: flex; gap: .4rem; }
        /* RN-07: colores por estatus_orden */
        .badge-ruta { background: var(--amarillo); }
        .badge-planta_recibido { background: var(--azul-claro); }
        .badge-proceso { background: #8e44ad; }
        .badge-listo { background: #28a745; }
        .badge-entregado { background: #1e7e34; }
        .badge-cancelado { background: var(--rojo); }
    </style>
</head>
<body>
    @auth
        <header class="app-header">
            <span class="brand">@include('partials.logo', ['size' => 30, 'stacked' => false, 'light' => true])</span>
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

    @auth
        @if (in_array(auth()->user()->rol, ['ADMIN', 'OPERADOR'], true))
            <div class="layout">
                <nav class="sidebar">
                    <a href="{{ route('operaciones.dashboard') }}" class="{{ request()->routeIs('operaciones.dashboard') ? 'active' : '' }}">Operaciones</a>
                    <a href="{{ route('planta.buscar') }}" class="{{ request()->routeIs('planta.*') ? 'active' : '' }}">Auditoría de Planta</a>
                    @if (auth()->user()->rol === 'ADMIN')
                        <div class="section-title">Catálogos</div>
                        <a href="{{ route('operaciones.clientes.index') }}" class="{{ request()->routeIs('operaciones.clientes.*') ? 'active' : '' }}">Clientes</a>
                        <a href="{{ route('operaciones.servicios.index') }}" class="{{ request()->routeIs('operaciones.servicios.*') ? 'active' : '' }}">Servicios</a>
                        <a href="{{ route('operaciones.tarifas.index') }}" class="{{ request()->routeIs('operaciones.tarifas.*') ? 'active' : '' }}">Tarifarios</a>
                    @endif
                </nav>
                <div class="content">
                    @if (session('status'))
                        <div class="alert alert-status">{{ session('status') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-error">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                </div>
            </div>
        @else
            <main>
                @yield('content')
            </main>
        @endif
    @else
        <main>
            @yield('content')
        </main>
    @endauth
</body>
</html>
