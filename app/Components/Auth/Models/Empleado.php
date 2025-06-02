<?php

namespace App\Components\Auth\Models;

use Illuminate\Database\Eloquent\Model;
class Empleado extends Model
{
    protected $table = 'empleados';
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id');
    }
}

