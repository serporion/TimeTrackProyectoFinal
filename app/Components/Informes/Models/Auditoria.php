<?php

namespace App\Components\Informes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Auditoria extends Model
{
    use Notifiable, HasFactory, CrudTrait;

    protected $table = 'auditorias';

    protected $fillable = [
        'accion',
        'usuario_id',
        'tabla_afectada',
        'registro_id',
        'timestamp'
    ];

    public $timestamps = false;
}
