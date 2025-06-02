<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CredencialCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuditoriaCrudController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }

    public function setup()
    {
        CRUD::setModel(\App\Components\Informes\Models\Auditoria::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/auditoria');
        CRUD::setEntityNameStrings('auditoria', 'auditorias');
    }

    protected function setupListOperation()
    {
        CRUD::column('accion');
        CRUD::column('usuario_id');
        CRUD::column('tabla_afectada');
        CRUD::column('registro_id');
        CRUD::column('timestamp');
    }

    protected function setupCreateOperation()
    {
        CRUD::field('accion');
        CRUD::field('usuario_id');
        CRUD::field('tabla_afectada');
        CRUD::field('registro_id');
        CRUD::field('timestamp');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
