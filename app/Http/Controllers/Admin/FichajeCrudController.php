<?php

namespace App\Http\Controllers\Admin;

use App\Components\Fichaje\Models\Fichaje;
use App\Components\Fichaje\Requests\FichajeRequest;
use App\Components\Media\Models\Foto;
use App\Components\Media\Models\QR;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FichajeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FichajeCrudController extends CrudController
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
        CRUD::setModel(Fichaje::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/fichaje');
        CRUD::setEntityNameStrings('fichaje', 'fichajes');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // set columns from db columns.

        CRUD::column('usuario_id')->label('Usuario');
        //CRUD::column('qr_id')->label('QR');

        CRUD::addColumn([
            'name' => 'qr_id',
            'label' => 'QR',
            'type' => 'select',
            'entity' => 'qr',
            'model' => QR::class,
            'attribute' => 'contenido'
        ]);

        //CRUD::column('foto_id')->label('Foto');

        CRUD::addColumn([
            'name' => 'foto_id',
            'label' => 'Foto',
            'type' => 'select',
            'entity' => 'foto',
            'model' => Foto::class,
            'attribute' => 'ruta_imagen'
        ]);


        CRUD::column('tipo');
        //CRUD::column('timestamp');
        CRUD::addColumn([
            'name' => 'timestamp',
            'label' => 'Fecha y hora',
            'type' => 'datetime',
            'format' => 'DD/MM/YYYY HH:mm'
        ]);

        $this->crud->orderBy('timestamp', 'desc');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(FichajeRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        CRUD::field('usuario_id')->type('select')->model(\App\Components\Auth\Models\Usuario::class)->attribute('name');

        //CRUD::field('qr_id')->type('select')->model(\App\Components\Media\Models\QR::class)->attribute('id');
        CRUD::addField([
            'name' => 'qr_id',
            'label' => 'QR',
            'type' => 'select',
            'model' => QR::class,
            'attribute' => 'contenido'
        ]);

        //CRUD::field('foto_id')->type('select')->model(\App\Components\Media\Models\Foto::class)->attribute('ruta_imagen');
        CRUD::addField([
            'name' => 'foto_id',
            'label' => 'Foto',
            'type' => 'select',
            'model' => Foto::class,
            'attribute' => 'ruta_imagen'
        ]);




        CRUD::field('tipo')->type('select_from_array')->options(['entrada' => 'Entrada', 'salida' => 'Salida']);
        CRUD::field('timestamp')->type('datetime');
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
