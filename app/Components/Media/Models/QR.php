<?php

namespace App\Components\Media\Models;

use App\Components\Fichaje\Models\Fichaje;
use Illuminate\Database\Eloquent\Model;

class QR extends Model
{
    protected $table = 'qrs';

    protected $fillable = [
        'contenido',
        'estado',
        'timestamp',
    ];

    public $timestamps = false;

    public function fichaje()
    {
        return $this->hasOne(Fichaje::class, 'qr_id');
    }
}
