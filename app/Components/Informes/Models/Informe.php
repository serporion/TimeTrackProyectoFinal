<?php

namespace App\Components\Informes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Informe extends Model
{
    use Notifiable, HasFactory, CrudTrait;

    protected $table = 'informes';

    protected $fillable = [
        'usuario_id',
        'contenido',
        'fecha_generado'
    ];

    public $timestamps = false;
}
