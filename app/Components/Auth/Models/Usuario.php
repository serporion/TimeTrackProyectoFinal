<?php

namespace App\Components\Auth\Models;

use App\Components\Fichaje\Models\Contrato;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

use App\Components\Fichaje\Models\Empleado;

use Tymon\JWTAuth\Contracts\JWTSubject;


class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory, CrudTrait;

    protected $table = 'usuarios';

    // Campos que se pueden asignar en masa (formulario, seeder, etc.)
    protected $fillable = [
        'name',
        'email',
        'dni',
        'password',
        'role',
        'consiente_datos',
    ];

    // Campos ocultos al serializar (JSON, API, etc.)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting de tipos automáticos
    protected $casts = [
        'consiente_datos' => 'boolean',
    ];

    // Laravel con BackPack usa timestamps así para los campos created_at y updated_at.
    public $timestamps = true;

    /**
     * Método para verificar rol.
     */
    public function isAdmin()
    {
        return $this->role === 'administrador';
    }

    /**
     * Devuelve la credencial para mostrarla posteriormente mediante una relación con Credencial
     *
     * @return mixed
     */
    public function credencial()
    {
        return $this->hasOne(Credencial::class, 'usuario_id');
    }

    /*
    //ELIMINAR??
    // Encrypta la contraseña
    // @param $value
    // @return void
    //
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
    */

    public function setPasswordAttribute($value)
    {
        // Si ya viene hasheado porque Breeze, Tinker ya ho hasheo, no se vuelve a hashear. Problema con JWT también.
        if (!empty($value) && !\Illuminate\Support\Str::startsWith($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }


    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id'); //Cambiado de Modelo
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'usuario_id');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey(); // o $this->id
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'usuario_id');
    }

}
