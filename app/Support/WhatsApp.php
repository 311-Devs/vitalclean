<?php

namespace App\Support;

use App\Models\NotaRemision;

/**
 * Genera enlaces "click to chat" de WhatsApp (wa.me) para compartir la nota
 * de remisión con la persona encargada del hotel/comercio, tanto al
 * recolectar (CU-01) como al entregar (CU-04).
 *
 * No usa la API de WhatsApp Business (requeriría credenciales y aprobación
 * de Meta) — abre WhatsApp Web/App con el mensaje ya redactado y es el
 * vendedor quien confirma el envío. No depende de nada fuera de lo que ya
 * hay en el hosting.
 */
class WhatsApp
{
    /**
     * @return string|null null si el cliente no tiene teléfono registrado.
     */
    public static function linkTo(?string $telefono, string $mensaje): ?string
    {
        $normalizado = self::normalizarTelefono($telefono);

        if (! $normalizado) {
            return null;
        }

        return 'https://wa.me/'.$normalizado.'?text='.rawurlencode($mensaje);
    }

    /**
     * Enlace de WhatsApp para avisar la recolección (CU-01). Espera
     * $nota->cliente y $nota->detalle.servicio ya cargados.
     */
    public static function linkRecoleccion(NotaRemision $nota): ?string
    {
        return self::linkTo($nota->cliente->telefono, self::mensajeRecoleccion($nota));
    }

    /**
     * Enlace de WhatsApp para avisar la entrega (CU-04). Espera
     * $nota->cliente y $nota->detalle.servicio ya cargados.
     */
    public static function linkEntrega(NotaRemision $nota): ?string
    {
        return self::linkTo($nota->cliente->telefono, self::mensajeEntrega($nota));
    }

    protected static function mensajeRecoleccion(NotaRemision $nota): string
    {
        $folio = 'VC-'.str_pad((string) $nota->folio_sistema, 4, '0', STR_PAD_LEFT);

        $lineas = $nota->detalle->map(
            fn ($linea) => "- {$linea->servicio->descripcion}: {$linea->cantidad_entrada}"
        )->implode("\n");

        return "Hola, le confirmamos la *recolección* de su pedido en Lavandería Vital Clean.\n\n"
            ."Cliente: {$nota->cliente->nombre_comercial}\n"
            ."Folio: {$folio} / {$nota->folio_fisico}\n"
            ."Fecha: {$nota->fecha_recoleccion->format('d/m/Y')}\n\n"
            ."Prendas recolectadas:\n{$lineas}\n\n"
            .'Le avisaremos en cuanto esté lista para entrega. ¡Gracias por su preferencia!';
    }

    protected static function mensajeEntrega(NotaRemision $nota): string
    {
        $folio = 'VC-'.str_pad((string) $nota->folio_sistema, 4, '0', STR_PAD_LEFT);

        $lineas = $nota->detalle->map(
            fn ($linea) => '- '.$linea->servicio->descripcion.': '.($linea->cantidad_salida ?? $linea->cantidad_entrada)
        )->implode("\n");

        $total = $nota->detalle->every(fn ($l) => $l->subtotal !== null)
            ? '$'.number_format((float) $nota->detalle->sum('subtotal'), 2)
            : 'Pendiente';

        return "Hola, le confirmamos la *entrega* de su pedido de Lavandería Vital Clean.\n\n"
            ."Cliente: {$nota->cliente->nombre_comercial}\n"
            ."Folio: {$folio} / {$nota->folio_fisico}\n\n"
            ."Prendas entregadas:\n{$lineas}\n\n"
            ."Total: {$total}\n\n"
            .'¡Gracias por su preferencia!';
    }

    /**
     * Deja solo dígitos y antepone el código de país de México (52) si el
     * número capturado es un local de 10 dígitos (formato usual en el
     * catálogo de clientes, ej. "999 780 8557").
     */
    protected static function normalizarTelefono(?string $telefono): ?string
    {
        if (! $telefono) {
            return null;
        }

        $digitos = preg_replace('/\D/', '', $telefono);

        if (! $digitos) {
            return null;
        }

        if (strlen($digitos) === 10) {
            $digitos = '52'.$digitos;
        }

        return $digitos;
    }
}
