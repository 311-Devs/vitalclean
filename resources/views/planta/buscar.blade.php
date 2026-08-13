@extends('layouts.app')

@section('title', 'Vital Clean — Auditoría de Planta')

@section('content')
    <div class="page-header"><h1>Auditoría de Planta</h1></div>

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <div class="card" style="max-width:480px;">
        <p style="color:#6b7280; margin-top:0;">
            Escanea o escribe el folio físico de la nota de remisión (o el
            folio de sistema, ej. <code>VC-0001</code>) para iniciar el
            conteo de piezas.
        </p>
        <form method="POST" action="{{ route('planta.iniciar') }}">
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
@endsection
