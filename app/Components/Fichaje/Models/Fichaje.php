<?php

namespace App\Components\Fichaje\Models;

use Illuminate\Database\Eloquent\Model;

class Fichaje extends Model
{
    protected $table = 'fichajes';

    protected $fillable = [
        'usuario_id',
        'qr_id',
        'foto_id',
        'tipo',
        'timestamp',
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(\App\Components\Auth\Models\Usuario::class, 'usuario_id');
    }

    public function qr()
    {
        return $this->belongsTo(\App\Components\Media\Models\QR::class, 'qr_id');
    }

    public function foto()
    {
        return $this->belongsTo(\App\Components\Media\Models\Foto::class, 'foto_id');
    }
}
