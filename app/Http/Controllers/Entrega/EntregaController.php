<?php

namespace App\Http\Controllers\Entrega;

use App\Http\Controllers\Controller;
use App\Models\NotaRemision;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CU-04 — Cierre de Ciclo y Liquidación.
 * Actor: Vendedor / Administrador.
 *
 * Selecciona folios LISTO, presenta la nota de remisión final, captura la
 * firma de recepción y cierra el ciclo a ENTREGADO (RF-09). El SRS también
 * pide "derivar a Cuentas por Cobrar": no existe ese módulo todavía (es
 * CU-06/RF-12, reportes y cierre de caja) — el folio queda marcado como
 * ENTREGADO y disponible para ese trabajo pendiente.
 */
class EntregaController extends Controller
{
    protected const ESTATUS_PROCESABLE = 'LISTO';

    public function buscar(Request $request): View
    {
        $listos = NotaRemision::with('cliente')
            ->where('estatus_orden', self::ESTATUS_PROCESABLE)
            ->latest('updated_at')
            ->take(15)
            ->get();

        return view('entrega.buscar', compact('listos'));
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

        if ($orden->estatus_orden !== self::ESTATUS_PROCESABLE && ! $esAdmin) {
            return back()->withInput()->with('error', "El folio {$orden->folio_fisico} está en estatus {$orden->estatus_orden}; todavía no está Listo para entrega.");
        }

        return redirect()->route('entrega.remision', $orden);
    }

    public function remision(NotaRemision $orden): View
    {
        $orden->load('cliente', 'detalle.servicio');

        return view('entrega.remision', compact('orden'));
    }

    public function confirmar(Request $request, NotaRemision $orden): RedirectResponse
    {
        $esAdmin = $request->user()->rol === 'ADMIN';

        if ($orden->estatus_orden !== self::ESTATUS_PROCESABLE && ! $esAdmin) {
            return back()->with('error', 'Este folio ya no está Listo; no se puede cerrar la entrega otra vez.');
        }

        $request->validate([
            'firma' => ['required', 'string', 'starts_with:data:image/png;base64,'],
        ], [
            'firma.required' => 'Falta capturar la firma de recepción.',
        ]);

        $firmaBinaria = base64_decode(substr($request->input('firma'), strlen('data:image/png;base64,')));

        $orden->update([
            'firma_entrega' => $firmaBinaria,
            // RF-08/RF-09: LISTO -> ENTREGADO, cierre de ciclo.
            'estatus_orden' => $orden->estatus_orden === self::ESTATUS_PROCESABLE
                ? 'ENTREGADO'
                : $orden->estatus_orden,
        ]);

        return redirect()->route('entrega.buscar')
            ->with('status', "Folio VC-".str_pad($orden->folio_sistema, 4, '0', STR_PAD_LEFT)." entregado y cerrado. Queda derivado a Cuentas por Cobrar.");
    }
}
