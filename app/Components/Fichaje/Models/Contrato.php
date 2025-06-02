<?php

namespace App\Components\Fichaje\Models;

use App\Components\Auth\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
class Contrato extends Model
{
    use Notifiable, HasFactory, CrudTrait;

    protected $table = 'contratos';

    protected $fillable = [
        'usuario_id',
        'horas',
        'fecha_inicio',
        'fecha_fin'
    ];

    public $timestamps = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
