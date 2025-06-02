<?php

namespace App\Components\Media\Models;

use App\Components\Fichaje\Models\Fichaje;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $table = 'fotos';

    protected $fillable = [
        'ruta_imagen',
        'timestamp',
    ];

    public $timestamps = false;

    public function fichaje()
    {
        return $this->hasOne(Fichaje::class, 'foto_id');
    }
}
