<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo: ope_notas_remision (Cabecera) (SRS Vital Clean §10.2)
 */
class NotaRemision extends Model
{
    protected $table = 'ope_notas_remision';

    protected $primaryKey = 'folio_sistema';

    protected $fillable = [
        'folio_fisico',
        'id_cliente',
        'id_vendedor',
        'fecha_recoleccion',
        'fecha_entrega_prog',
        'estatus_orden',
        'firma_cliente',
        'geolocalizacion',
        'conteo_bloqueado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_recoleccion' => 'datetime',
            'fecha_entrega_prog' => 'date',
            'conteo_bloqueado' => 'boolean',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'id_vendedor', 'id_usuario');
    }

    public function detalle()
    {
        return $this->hasMany(DetalleRemision::class, 'folio_sistema', 'folio_sistema');
    }

    public function getRouteKeyName(): string
    {
        return 'folio_sistema';
    }
}
