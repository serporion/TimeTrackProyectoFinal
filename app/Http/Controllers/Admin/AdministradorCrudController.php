<?php

namespace App\Http\Controllers\Admin;

use App\Components\Auth\Requests\AdministradorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Components\Auth\Models\Usuario;
use Illuminate\Http\Request;

/**
 * Class AdministradorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AdministradorCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Components\Auth\Models\Administrador::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/administrador');
        CRUD::setEntityNameStrings('administrador', 'administradores');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::column('usuario_id');

        CRUD::addColumn([
            'name' => 'usuario_id',
            'label' => 'ID Usuario',
            'type' => 'number',
        ]);

        CRUD::addColumn([
            'name' => 'usuario.name', // accede a la relación y muestra el nombre
            'label' => 'Nombre',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'permisos',
            'label' => 'Permisos',
            'type' => 'array',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AdministradorRequest::class);

        /*
                CRUD::addField([
                    'name' => 'usuario_id',
                    'label' => 'ID de Usuario',
                    'type' => 'number',
                ]);

                //BACKPACK PRO, NO FUNCIONA
                CRUD::addField([
                    'name' => 'usuario_id',
                    'label' => 'Seleccionar Usuario Administrador',
                    //'type' => 'select2_from_ajax',
                    'entity' => 'usuario', // Se crea función para relacionar en el modelo Administrador
                    'attribute' => 'id',
                    'data_source' => url('/admin/usuario-administrador-ajax'),
                    'placeholder' => 'Selecciona un administrador',
                    'minimum_input_length' => 1,
                ]);
        */

        $usuariosDisponibles = Usuario::where('role', 'administrador')
            ->whereNotIn('id', function ($q) {
                $q->select('usuario_id')->from('administradores');
            })
            ->pluck('name', 'id')
            ->toArray();

        CRUD::addField([
            'name' => 'usuario_id',
            'label' => 'Usuario Administrador',
            'type' => 'select_from_array',
            'options' => $usuariosDisponibles,
            'allows_null' => false,
            'default' => null,
        ]);

        CRUD::addField([
            'name' => 'permisos',
            'label' => 'Permisos',
            'type' => 'select_from_array',
            'options' => [
                'gestionar_usuarios' => 'Gestionar usuarios',
                'gestionar_fichajes' => 'Gestionar fichajes',
                'gestionar_permisos' => 'Gestionar permisos',
                'gestionar_inicio' => 'Gestionar inicio',
            ],
            'allows_null' => false,
            'allows_multiple' => true, // 👈 habilita múltiples selecciones
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(AdministradorRequest::class);

        CRUD::addField([
            'name' => 'usuario_id',
            'label' => 'ID de Usuario',
            'type' => 'number',
            'attributes' => [
                'readonly' => 'readonly',
            ],
        ]);

        CRUD::addField([
            'name' => 'permisos',
            'label' => 'Permisos',
            'type' => 'select_from_array',
            'options' => [
                'gestionar_usuarios' => 'Gestionar usuarios',
                'gestionar_fichajes' => 'Gestionar fichajes',
                'gestionar_permisos' => 'Gestionar permisos',
                'gestionar_inicio' => 'Gestionar inicio',
            ],
            'allows_null' => false,
            'allows_multiple' => true,
        ]);
    }

    public function buscarAdministradores(Request $request)
    {
        $term = $request->input('term');

        $usuarios = Usuario::where('role', 'administrador')
            ->whereNotIn('id', function ($q) {
                $q->select('usuario_id')->from('administradores');
            })
            ->where('name', 'like', '%' . $term . '%')
            ->limit(10)
            ->get();

        return $usuarios->map(function ($usuario) {
            return [
                'id' => $usuario->id,
                'text' => $usuario->name . ' (' . $usuario->email . ')',
            ];
        });
    }
}
