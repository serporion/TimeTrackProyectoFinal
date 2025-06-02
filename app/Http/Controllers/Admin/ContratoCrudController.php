<?php

namespace App\Http\Controllers\Admin;

use App\Components\Fichaje\Models\Contrato;
use App\Components\Fichaje\Requests\ContratoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UsuarioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContratoCrudController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }


    public function setup()
    {
        CRUD::setModel(Contrato::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contrato');
        CRUD::setEntityNameStrings('contrato', 'contratos');
    }

    protected function setupListOperation()
    {
        CRUD::column('usuario_id');
        CRUD::column('horas');
        CRUD::column('fecha_inicio');
        CRUD::column('fecha_fin');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ContratoRequest::class);


        CRUD::field('usuario_id');
        CRUD::field('horas');
        CRUD::field('fecha_inicio');
        CRUD::field('fecha_fin');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
