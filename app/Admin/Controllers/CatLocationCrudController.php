<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminCatLocationRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Admin\Utilities\CrudFieldGenerator;
use App\Models\CatLocation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\ReviseOperation\ReviseOperation;
use Exception;

class CatLocationCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ReviseOperation;

    /**
     * @throws Exception
     */
    public function setup()
    {
        $this->crud->setModel(CatLocation::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.cat_locations'));
        $this->crud->setEntityNameStrings('Lokacija', 'Lokacije');
        $this->crud->setSubheading('Dodaj novo lokacijo', 'create');
        $this->crud->setSubheading('Uredi lokacijo', 'edit');
        $this->crud->enableExportButtons();
    }

    protected function setupListOperation(): void
    {
        $this->crud->addColumn(CrudColumnGenerator::id());
        $this->crud->addColumn(CrudColumnGenerator::name());
        $this->crud->addColumn(CrudColumnGenerator::address());
        $this->crud->addColumn(CrudColumnGenerator::zipCode());
        $this->crud->addColumn(CrudColumnGenerator::city());
        $this->crud->addColumn(CrudColumnGenerator::country());
        $this->crud->addColumn(CrudColumnGenerator::createdAt());
        $this->crud->addColumn(CrudColumnGenerator::updatedAt());

        $this->crud->orderBy('updated_at', 'DESC');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(AdminCatLocationRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Ime',
            'type' => 'text',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'name-wrapper'
            ],
        ]);

        CrudFieldGenerator::addAddressFields($this->crud);
    }
}
