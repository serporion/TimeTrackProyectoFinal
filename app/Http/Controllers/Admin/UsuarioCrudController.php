<?php

namespace App\Http\Controllers\Admin;

use App\Components\Auth\Models\Usuario;
use App\Components\Auth\Requests\UsuarioRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Components\Auth\Models\Credencial;
use Tymon\JWTAuth\Facades\JWTAuth;
use Prologue\Alerts\Facades\Alert;


/**
 * Class UsuarioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UsuarioCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        //CRUD::setModel(\App\Models\Usuario::class);
        CRUD::setModel(Usuario::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/usuario');
        CRUD::setEntityNameStrings('usuario', 'usuarios');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        CRUD::addColumn([
            'name' => 'credencial.clave_acceso',
            'label' => 'Clave de acceso',
            'type' => 'text',
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
        CRUD::setValidation(UsuarioRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        if ($this->crud->getCurrentEntry()) {

            //$this->crud->addButtonFromView('top', 'regenerarCredencial', 'regenerarCredencial', 'beginning');
            $this->crud->addButtonFromView('form_top', 'regenerarCredencial', 'crud::buttons.regenerarCredencial');

            //dd($this->crud->buttons());

            if (!view()->exists('crud::buttons.regenerarCredencial')) {
                dd('La vista no existe');
            }

        }

        $this->setupCreateOperation();

        //dd('Botón cargado');
        //dd($this->crud->buttons());

    }

    /**
     *
     *
     * @return mixed
     */
    public function store()
    {
        $response = $this->traitStore();
        $usuario = $this->crud->entry;

        // Crear JWT persistente como credencial
        $tokenPersistente = JWTAuth::fromUser($usuario);

        if (!Credencial::where('usuario_id', $usuario->id)->exists()) {
            Credencial::create([
                'usuario_id' => $usuario->id,
                'clave' => $tokenPersistente,
            ]);
        }

        return $response;
    }

    /**
     * Método que regenera la credencial usada para su cotejo en la acción de fichar.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateCredencial($id)
    {
        $usuario = $this->crud->getModel()::findOrFail($id);
        $nuevoToken = JWTAuth::fromUser($usuario);

        Credencial::updateOrCreate(
            ['usuario_id' => $usuario->id],
            ['clave' => $nuevoToken]
        );

        Alert::success('Credencial regenerada correctamente')->flash();

        return redirect()->back();
    }
}
