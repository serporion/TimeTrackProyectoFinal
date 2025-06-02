<?php

namespace App\Components\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Administrador extends Model
{

    use CrudTrait;

    /**
     * Se usa la tabla 'usuarios' porque Administrador es una subclase de Usuario.
     */
    protected $table = 'administradores';

    protected $primaryKey = 'usuario_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['usuario_id', 'permisos'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Devuelve los permisos del administrador como array.
     */
    public function getPermisosComoArray(): array
    {
        return json_decode($this->attributes['permisos'] ?? '[]', true);
    }

    /**
     * Muestra los permisos de forma legible.
     *
     */
    public function getPermisosAsStringAttribute()
    {
        return implode(', ', json_decode($this->permisos, true) ?? []);
    }




    /**
     * Verifica si el administrador tiene un permiso específico.
     */
    public function tienePermiso(string $permiso): bool
    {
        return in_array($permiso, $this->permisos(), true);
    }

    /**
     * Verifica si este usuario tiene el rol de administrador (por ENUM o campo de texto).
     */
    public function esAdministrador(): bool
    {
        return $this->role === 'administrador'; // o "admin" según tu sistema
    }

    public function setPermisosAttribute($value)
    {
        $this->attributes['permisos'] = json_encode($value);
    }

}
