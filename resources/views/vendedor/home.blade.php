@extends('layouts.app')

@section('title', 'Vital Clean — Vendedor')

@section('content')
    <div class="card">
        <h1>Hola, {{ auth()->user()->nombre_completo ?? auth()->user()->username }}</h1>
        <p>Aquí irá el flujo de recolección (CU-01: búsqueda de cliente, armado de pedido y firma digital).</p>
    </div>
@endsection
