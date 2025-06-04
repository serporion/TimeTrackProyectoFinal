<?php

namespace App\Components\Fichaje\Models;

use App\Components\Auth\Models\Usuario;
use App\Components\Media\Models\Foto;
use App\Components\Media\Models\QR;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Fichaje extends Model
{
    use Notifiable, HasFactory, CrudTrait;
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
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function qr()
    {
        return $this->belongsTo(QR::class, 'qr_id');
    }

    public function foto()
    {
        return $this->belongsTo(Foto::class, 'foto_id');
    }
}
