@extends('layouts.app')

@section('title', 'Vital Clean — Operaciones')

@section('content')
    <div class="card">
        <h1>Panel de Operaciones</h1>
        <p>Bienvenido, {{ auth()->user()->nombre_completo ?? auth()->user()->username }}.</p>
        <p>Aquí irá el dashboard de la pantalla A-04 (monitor de folios, KPIs y alertas de calidad).</p>
    </div>
@endsection
