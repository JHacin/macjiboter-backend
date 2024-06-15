<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\SponsorshipWallpaperRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Models\SponsorshipWallpaper;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Exception;

class SponsorshipWallpaperCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use UpdateOperation;

    /**
     * @throws Exception
     */
    public function setup(): void
    {
        $this->crud->setModel(SponsorshipWallpaper::class);
        $this->crud->setRoute(
            config('backpack.base.route_prefix').'/'.config('routes.admin.sponsorship_wallpapers')
        );
        $this->crud->setEntityNameStrings('Ozadje', 'Ozadja');
    }

    protected function setupListOperation(): void
    {
        $this->crud->addColumn(CrudColumnGenerator::id());
        $this->crud->addColumn([
            'name' => 'month_and_year',
            'label' => trans('sponsorship_wallpaper.month_and_year'),
            'type' => 'date',
            'format' => 'MMMM Y',
        ]);

    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(SponsorshipWallpaperRequest::class);

        $this->crud->addField([
            'name' => 'month_and_year',
            'label' => trans('sponsorship_wallpaper.month_and_year'),
            'type' => 'month_picker',
        ]);
        $this->crud->addField([
            'name' => 'file_path',
            'label' => 'Datoteka',
            'type' => 'upload',
            'disk' => 's3',
            'upload' => true,
            'hint' => 'Datoteka ne sme biti večja od 25 MB.'
        ]);
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
