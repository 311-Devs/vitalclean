<?php

namespace App\Http\Controllers\Planta;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConteoPlantaRequest;
use App\Models\Incidencia;
use App\Models\NotaRemision;
use App\Models\TarifaCliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * CU-02 — Auditoría de Conteo y Valoración (Planta).
 * GUI §12.2: Módulo de Operador de Planta (Tablet — Terminal de Conteo).
 */
class AuditoriaController extends Controller
{
    protected const ESTATUS_PROCESABLES = ['RUTA', 'PLANTA_RECIBIDO'];

    public function buscar(): View
    {
        return view('planta.buscar');
    }

    public function iniciar(Request $request): RedirectResponse
    {
        $request->validate(['folio' => ['required', 'string']]);

        $folio = trim($request->input('folio'));
        $folioNumerico = (int) preg_replace('/\D/', '', $folio);

        $orden = NotaRemision::where('folio_fisico', $folio)
            ->when($folioNumerico > 0, fn ($q) => $q->orWhere('folio_sistema', $folioNumerico))
            ->first();

        if (! $orden) {
            return back()->withInput()->with('error', "No se encontró ningún folio con \"{$folio}\".");
        }

        $esAdmin = $request->user()->rol === 'ADMIN';

        if (! in_array($orden->estatus_orden, self::ESTATUS_PROCESABLES, true) && ! $esAdmin) {
            return back()->withInput()->with('error', "El folio {$orden->folio_fisico} ya está en estatus {$orden->estatus_orden}; no se puede volver a auditar.");
        }

        // Pantalla A-04 KPI "En Auditoría": marca la llegada a planta.
        if ($orden->estatus_orden === 'RUTA') {
            $orden->update(['estatus_orden' => 'PLANTA_RECIBIDO']);
        }

        return redirect()->route('planta.conteo', $orden);
    }

    public function conteo(NotaRemision $orden): View
    {
        $orden->load('cliente', 'detalle.servicio', 'detalle.incidencias');

        $tarifas = TarifaCliente::where('id_cliente', $orden->id_cliente)
            ->pluck('precio_pactado', 'id_servicio');

        return view('planta.conteo', compact('orden', 'tarifas'));
    }

    public function guardar(ConteoPlantaRequest $request, NotaRemision $orden): RedirectResponse
    {
        $esAdmin = $request->user()->rol === 'ADMIN';

        // RN-03: una vez bloqueado, solo ADMIN puede modificar cantidades.
        if ($orden->conteo_bloqueado && ! $esAdmin) {
            return back()->with('error', 'Este conteo ya fue guardado y bloqueado. Solo un Administrador puede modificarlo.');
        }

        $orden->load('detalle');
        $cantidades = $request->input('cantidades', []);
        $danos = $request->input('dano', []);
        $comentarios = $request->input('comentario_dano', []);

        DB::transaction(function () use ($orden, $cantidades, $danos, $comentarios, $request) {
            foreach ($orden->detalle as $linea) {
                if (! array_key_exists($linea->id_detalle, $cantidades)) {
                    continue;
                }

                $cantidadReal = (int) $cantidades[$linea->id_detalle];

                // RN-01: el precio no es global, se consulta por contrato.
                $tarifa = TarifaCliente::where('id_cliente', $orden->id_cliente)
                    ->where('id_servicio', $linea->id_servicio)
                    ->first();

                $linea->update([
                    'cantidad_entrada' => $cantidadReal,
                    // RN-02: precio_aplicado congela el precio vigente en
                    // este momento; si el cliente no tiene tarifa pactada
                    // para esta prenda, queda pendiente para revisión manual.
                    'precio_aplicado' => $tarifa?->precio_pactado,
                    'subtotal' => $tarifa ? $cantidadReal * $tarifa->precio_pactado : null,
                ]);

                $tipoDano = $danos[$linea->id_detalle] ?? null;
                $comentario = $comentarios[$linea->id_detalle] ?? null;

                if ($tipoDano || $comentario) {
                    $rutaFoto = null;
                    $archivoFoto = $request->file("foto.{$linea->id_detalle}");
                    if ($archivoFoto) {
                        $rutaFoto = $archivoFoto->store('', 'incidencias');
                    }

                    Incidencia::create([
                        'id_detalle' => $linea->id_detalle,
                        'foto_evidencia' => $rutaFoto,
                        'comentario' => trim(($tipoDano ?? '').($comentario ? " — {$comentario}" : '')) ?: null,
                    ]);
                }
            }

            // RN-07: solo avanza el estatus si aún no había pasado de aquí;
            // una edición posterior de ADMIN no debe hacer retroceder folios
            // que ya siguieron avanzando en el flujo.
            $orden->update([
                'conteo_bloqueado' => true,
                'estatus_orden' => in_array($orden->estatus_orden, self::ESTATUS_PROCESABLES, true)
                    ? 'PROCESO'
                    : $orden->estatus_orden,
            ]);
        });

        return redirect()->route('planta.buscar')
            ->with('status', "Conteo de VC-".str_pad($orden->folio_sistema, 4, '0', STR_PAD_LEFT)." guardado y bloqueado correctamente.");
    }
}
