<?php

namespace App\Components\Fichaje\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'historialFichaje',
    ];

    protected $casts = [
        'historialFichaje' => 'array',
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(\App\Components\Auth\Models\Usuario::class, 'id');
    }
}
