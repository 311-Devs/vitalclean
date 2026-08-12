@extends('layouts.app')

@section('title', 'Vital Clean — Nuevo Pedido')

@section('content')
    <div class="page-header"><h1>Nuevo Pedido</h1></div>

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('vendedor.recoleccion.store') }}">
        @csrf
        <div class="card" style="margin-bottom:1rem;">
            <div class="form-group">
                <label for="id_cliente">Cliente *</label>
                <select id="id_cliente" name="id_cliente" required style="max-width:100%;">
                    <option value="">— Buscar cliente —</option>
                    @forelse ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                            {{ $cliente->nombre_comercial }}
                        </option>
                    @empty
                        <option value="" disabled>No hay clientes con crédito activo</option>
                    @endforelse
                </select>
            </div>

            <div class="form-group">
                <label for="folio_fisico">Folio Físico (nota de remisión en papel) *</label>
                <input type="text" id="folio_fisico" name="folio_fisico" maxlength="20"
                       value="{{ old('folio_fisico') }}" placeholder="Ej. 02149" required style="max-width:200px;">
            </div>

            <div class="form-group">
                <label for="fecha_entrega_prog">Fecha de Entrega Comprometida</label>
                <input type="date" id="fecha_entrega_prog" name="fecha_entrega_prog"
                       value="{{ old('fecha_entrega_prog') }}" style="max-width:200px;">
            </div>
        </div>

        <div class="card">
            <div class="page-header">
                <h1 style="font-size:1.1rem;">Checklist de Prendas</h1>
                <input type="text" id="filtro-prendas" placeholder="Buscar prenda..."
                       style="padding:.5rem .7rem; border:1px solid #d1d5db; border-radius:.375rem; max-width:220px;">
            </div>

            <table class="data-table" id="tabla-prendas">
                <thead>
                    <tr>
                        <th>Prenda</th>
                        <th>Categoría</th>
                        <th>Unidad</th>
                        <th style="width:110px;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($servicios as $servicio)
                        <tr class="fila-prenda">
                            <td class="nombre-prenda">{{ $servicio->descripcion }}</td>
                            <td>{{ $servicio->categoria ?? '—' }}</td>
                            <td>{{ $servicio->unidad }}</td>
                            <td>
                                <input type="number" min="0" step="1" name="cantidades[{{ $servicio->id_servicio }}]"
                                       value="{{ old('cantidades.'.$servicio->id_servicio, 0) }}" style="width:80px; max-width:80px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Continuar a Resumen</button>
            <a href="{{ route('vendedor.home') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <script>
        document.getElementById('filtro-prendas').addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabla-prendas .fila-prenda').forEach(function (fila) {
                var nombre = fila.querySelector('.nombre-prenda').textContent.toLowerCase();
                fila.style.display = nombre.includes(q) ? '' : 'none';
            });
        });
    </script>
@endsection
