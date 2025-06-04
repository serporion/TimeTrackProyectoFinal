<?php

namespace App\Http\Controllers\Admin;

use App\Components\Auth\Models\Credencial;
use App\Components\Auth\Models\Usuario;
use App\Components\Auth\Requests\CredencialRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CredencialCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CredencialCrudController extends CrudController
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
        //CRUD::setModel(\App\Models\Credencial::class);
        CRUD::setModel(Credencial::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/credencial');
        CRUD::setEntityNameStrings('credencial', 'credenciales');
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
            'name' => 'usuario_id',
            'type' => 'select',
            'label' => 'Usuario',
            'entity' => 'usuario',
            'model' => \App\Components\Auth\Models\Usuario::class,
            'attribute' => 'email',
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
        CRUD::setValidation(CredencialRequest::class);

        CRUD::field('usuario_id')->type('select')->label('Usuario')
            ->model(Usuario::class)
            ->attribute('email');

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::field('usuario_id')->type('select')->label('Usuario')
            ->model(Usuario::class)
            ->attribute('email');

        $this->setupCreateOperation();
    }
}
