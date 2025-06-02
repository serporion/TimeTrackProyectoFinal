<?php

namespace App\Components\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Notifications\Notifiable;


class Credencial extends Model
{
    use Notifiable, HasFactory, CrudTrait;

    protected $table = 'credenciales';

    protected $primaryKey = 'usuario_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'usuario_id',
        'clave',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

