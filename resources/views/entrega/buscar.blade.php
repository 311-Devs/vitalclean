@extends('layouts.app')

@section('title', 'Vital Clean — Cierre de Entrega')

@section('content')
    <div class="page-header"><h1>Cierre de Entrega</h1></div>

    @if (session('status'))
        <div class="alert alert-status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <div class="card" style="max-width:480px;">
        <p style="color:#6b7280; margin-top:0;">
            Escribe el folio físico (o el folio de sistema, ej.
            <code>VC-0001</code>) del pedido Listo que vas a entregar, para
            generar la nota de remisión final y capturar la firma de
            recepción del cliente.
        </p>
        <form method="POST" action="{{ route('entrega.iniciar') }}">
            @csrf
            <div class="form-group">
                <label for="folio">Folio</label>
                <input type="text" id="folio" name="folio" autofocus required
                       placeholder="Ej. 02149 o VC-0001" value="{{ old('folio') }}"
                       style="max-width:100%; font-size:1.2rem; padding:.8rem;">
            </div>
            <button type="submit" class="btn" style="width:100%;">Buscar Folio</button>
        </form>
    </div>

    <div class="card" style="max-width:700px; margin-top:1.5rem;">
        <h2 style="font-size:1rem; margin-top:0;">Listos para Entregar</h2>
        <table class="data-table">
            <thead><tr><th>Folio</th><th>Cliente</th><th></th></tr></thead>
            <tbody>
                @forelse ($listos as $orden)
                    <tr>
                        <td>VC-{{ str_pad($orden->folio_sistema, 4, '0', STR_PAD_LEFT) }} / {{ $orden->folio_fisico }}</td>
                        <td>{{ $orden->cliente->nombre_comercial }}</td>
                        <td><a class="btn btn-sm" href="{{ route('entrega.remision', $orden) }}">Entregar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="3">No hay folios Listos para entregar ahora mismo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
