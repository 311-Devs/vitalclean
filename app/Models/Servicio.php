<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo: cat_servicios (SRS Vital Clean §10.1)
 */
class Servicio extends Model
{
    protected $table = 'cat_servicios';

    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'descripcion',
        'unidad',
        'categoria',
    ];

    public function tarifas()
    {
        return $this->hasMany(TarifaCliente::class, 'id_servicio', 'id_servicio');
    }
}
