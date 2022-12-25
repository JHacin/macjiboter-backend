<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminNewsRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Admin\Utilities\CrudFieldGenerator;
use App\Models\News;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\ReviseOperation\ReviseOperation;
use Exception;

/**
 * Class NewsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class NewsCrudController extends CrudController
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
        $this->crud->setModel(News::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.news'));
        $this->crud->setEntityNameStrings('Novica', 'Novice');
        $this->crud->enableExportButtons();
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn(CrudColumnGenerator::id());
        $this->crud->addColumn([
            'name' => 'title',
            'label' => trans('news.title'),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'body',
            'label' => trans('news.body'),
            'type' => 'text',
        ]);
        $this->crud->addColumn(CrudColumnGenerator::createdAt());
        $this->crud->addColumn(CrudColumnGenerator::updatedAt());
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AdminNewsRequest::class);

        $this->crud->addField([
            'name' => 'title',
            'label' => trans('news.title'),
            'type' => 'text',
        ]);

        $this->crud->addField(CrudFieldGenerator::richTextField([
            'name' => 'body',
            'label' => trans('news.body'),
        ]));
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
