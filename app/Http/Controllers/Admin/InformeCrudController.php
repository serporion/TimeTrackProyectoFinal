<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CredencialCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InformeCrudController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }

    public function setup()
    {
        CRUD::setModel(\App\Components\Informes\Models\Informe::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/informe');
        CRUD::setEntityNameStrings('informe', 'informes');
    }

    protected function setupListOperation()
    {
        CRUD::column('usuario_id');
        CRUD::column('contenido');
        CRUD::column('fecha_generado');
    }

    protected function setupCreateOperation()
    {
        CRUD::field('usuario_id');
        CRUD::field('contenido');
        CRUD::field('fecha_generado');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
