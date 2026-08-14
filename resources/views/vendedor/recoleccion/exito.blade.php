@extends('layouts.app')

@section('title', 'Vital Clean — Pedido Creado')

@section('content')
    <div class="card" style="max-width:480px; text-align:center;">
        <div style="font-size:2.5rem; color:#28a745;">✓</div>
        <h1 style="color:#28a745;">¡Pedido creado exitosamente!</h1>
        <p><strong>Cliente:</strong> {{ $nota->cliente->nombre_comercial }}</p>
        <p><strong>Folio Sistema:</strong> VC-{{ str_pad($nota->folio_sistema, 4, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Folio Físico:</strong> {{ $nota->folio_fisico }}</p>
        <p><strong>Total de piezas:</strong> {{ $nota->detalle->sum('cantidad_entrada') }}</p>

        <div class="form-actions" style="justify-content:center;">
            <a href="{{ route('vendedor.pedidos.show', $nota) }}" class="btn">Ver Detalle</a>
            <a href="{{ route('vendedor.recoleccion.create') }}" class="btn btn-secondary">Nuevo Pedido</a>
            <a href="{{ route('vendedor.home') }}" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>
@endsection
