@extends('layouts.app')

@section('title', 'Vital Clean — Detalle de Pedido')

@section('content')
    <div class="page-header"><h1>Detalle de Pedido</h1></div>

    <div class="card" style="max-width:600px;">
        <p><strong>Nota No.:</strong> VC-{{ str_pad($nota->folio_sistema, 4, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Folio Físico:</strong> {{ $nota->folio_fisico }}</p>
        <p><strong>Cliente:</strong> {{ $nota->cliente->nombre_comercial }}</p>
        <p><strong>Estatus:</strong> <span class="badge badge-{{ strtolower($nota->estatus_orden) }}">{{ $nota->estatus_orden }}</span></p>
        <p><strong>Fecha de Recolección:</strong> {{ $nota->fecha_recoleccion->format('d/m/Y H:i') }}</p>
        @if ($nota->fecha_entrega_prog)
            <p><strong>Entrega Comprometida:</strong> {{ $nota->fecha_entrega_prog->format('d/m/Y') }}</p>
        @endif

        <table class="data-table" style="margin-top:1rem;">
            <thead>
                <tr><th>Artículo</th><th>Cantidad</th></tr>
            </thead>
            <tbody>
                @foreach ($nota->detalle as $linea)
                    <tr>
                        <td>{{ $linea->servicio->descripcion }}</td>
                        <td>{{ $linea->cantidad_entrada }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top:.75rem;"><strong>Total de Piezas: {{ $nota->detalle->sum('cantidad_entrada') }}</strong></p>
    </div>

    <div class="form-actions">
        <a href="{{ route('vendedor.pedidos.index') }}" class="btn btn-secondary">Volver al Listado</a>
    </div>
@endsection
