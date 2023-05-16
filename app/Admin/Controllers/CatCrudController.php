<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminCatRequest;
use App\Admin\Traits\ClearsModelGlobalScopes;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Admin\Utilities\CrudFieldGenerator;
use App\Admin\Utilities\CrudFilterGenerator;
use App\Models\Cat;
use App\Models\CatLocation;
use App\Models\CatPhoto;
use App\Services\CatPhotoService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\ReviseOperation\ReviseOperation;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Log;
use Symfony\Component\HttpFoundation\Response;

class CatCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation {
        update as traitUpdate;
    }
    use DeleteOperation;
    use ReviseOperation;
    use ClearsModelGlobalScopes;

    /**
     * @throws Exception
     */
    public function setup()
    {
        $this->crud->setModel(Cat::class);
        $this->clearModelGlobalScopes([Cat::SCOPE_ONLY_PUBLICALLY_VISIBLE_STATUSES]);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.cats'));
        $this->crud->setEntityNameStrings('Muca', 'Muce');
        $this->crud->setSubheading('Dodaj novo muco', 'create');
        $this->crud->setSubheading('Uredi muco', 'edit');
        $this->crud->addButtonFromModelFunction('line', 'open_view', 'openViewFromCrud');
        $this->crud->enableExportButtons();
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn(CrudColumnGenerator::id());
        $this->crud->addColumn([
            'name' => 'photo',
            'label' => trans('cat.photo'),
            'type' => 'cat_photo',
        ]);
        $this->crud->addColumn(CrudColumnGenerator::name());
        $this->crud->addColumn([
            'name' => 'status_label',
            'label' => trans('cat.status'),
            'type' => 'text'
        ]);
        $this->crud->addColumn(CrudColumnGenerator::genderLabel());
        $this->crud->addColumn([
            'name' => 'date_of_arrival_mh',
            'label' => trans('cat.date_of_arrival_mh'),
            'type' => 'date',
        ]);
        $this->crud->addColumn([
            'name' => 'date_of_arrival_boter',
            'label' => trans('cat.date_of_arrival_boter'),
            'type' => 'date',
        ]);
        $this->crud->addColumn([
            'name' => 'location',
            'label' => trans('cat.location'),
            'type' => 'relationship',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url(config('routes.admin.cat_locations'), [$related_key, 'edit']);
                },
            ],
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhereHas('location', function (Builder $query) use ($searchTerm) {
                    $query->where('name', 'like', "%$searchTerm%");
                });
            }
        ]);
        $this->crud->addColumn(CrudColumnGenerator::createdAt());
        $this->crud->addColumn(CrudColumnGenerator::updatedAt());

        $this->crud->orderBy('updated_at', 'DESC');

        $this->addFilters();
    }

    protected function addFilters()
    {
        $this->crud->addFilter(
            [
                'name' => 'location_id',
                'type' => 'select2',
                'label' => trans('cat.location'),
            ],
            function () {
                return CatLocation::all()->pluck('name', 'id')->toArray();
            },
            function ($value) {
                $this->crud->addClause('where', 'location_id', $value);
            }
        );

        CrudFilterGenerator::addDropdownFilter($this->crud, 'gender', trans('cat.gender'), Cat::GENDER_LABELS);
        CrudFilterGenerator::addDropdownFilter($this->crud, 'status', trans('cat.status'), Cat::STATUS_LABELS);
        CrudFilterGenerator::addBooleanFilter($this->crud, 'is_group', trans('cat.is_group'));
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AdminCatRequest::class);

        $this->crud->addField([
            'name' => 'name',
            'label' => trans('cat.name'),
            'type' => 'text',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'name-input-wrapper'
            ],
        ]);
        $this->crud->addField([
            'name' => 'gender',
            'label' => trans('cat.gender'),
            'type' => 'radio',
            'options' => Cat::GENDER_LABELS,
            'inline' => true,
            'wrapper' => [
                'dusk' => 'gender-input-wrapper'
            ],
            'hint' => 'Pri skupinah muc spol ni obvezen.',
        ]);
        $this->crud->addField([
            'name' => 'status',
            'label' => trans('cat.status'),
            'type' => 'select_from_array',
            'options' => Cat::STATUS_LABELS,
            'allows_null' => false,
            'default' => Cat::STATUS_NOT_SEEKING_SPONSORS,
            'required' => true,
            'wrapper' => [
                'dusk' => 'status-input-wrapper'
            ],
            'hint' =>
                '<em>išče botre</em>: objavljena, botrstvo je možno<br>' .
                '<em>trenutno ne išče botrov</em>: objavljena, botrstvo ni možno, prikazana je opomba<br>' .
                '<em>ne išče botrov</em>: ni objavljena, botrstvo ni možno<br>' .
                '<em>v novem domu</em>: ni objavljena, botrstvo ni možno<br>' .
                '<em>RIP</em>: ni objavljena, botrstvo ni možno<br>'
        ]);
        $this->crud->addField([
            'name' => 'is_group',
            'label' => trans('cat.is_group') . '?',
            'type' => 'radio',
            'options' => [
                1 => 'Da',
                0 => 'Ne',
            ],
            'default' => 0,
            'inline' => true,
            'wrapper' => [
                'dusk' => 'is_group-input-wrapper'
            ],
            'hint' => 'Ali naj se ta vnos obravnava kot druge skupine - Čombe, Pozitivčki, Bubiji...',
        ]);
        $this->crud->addField([
            'name' => 'story_short',
            'label' => trans('cat.story_short'),
            'type' => 'textarea',
            'hint' => 'Največ ' . config('validation.cat.story_short_maxlength') . ' znakov.',
            'attributes' => [
                'maxlength' => config('validation.cat.story_short_maxlength'),
                'required' => 'required',
                'rows' => 3,
            ],
        ]);
        $this->crud->addField(CrudFieldGenerator::richTextField([
            'name' => 'story',
            'label' => trans('cat.story'),
        ]));
        $this->crud->addField(CrudFieldGenerator::dateField([
            'name' => 'date_of_birth',
            'label' => trans('cat.date_of_birth'),
            'wrapper' => [
                'dusk' => 'date-of-birth-input-wrapper'
            ],
        ]));
        $this->crud->addField(CrudFieldGenerator::dateField([
            'name' => 'date_of_arrival_mh',
            'label' => trans('cat.date_of_arrival_mh'),
            'wrapper' => [
                'dusk' => 'date-of-arrival-mh-input-wrapper'
            ],
        ]));
        $this->crud->addField(CrudFieldGenerator::dateField([
            'name' => 'date_of_arrival_boter',
            'label' => trans('cat.date_of_arrival_boter'),
            'wrapper' => [
                'dusk' => 'date-of-arrival-boter-input-wrapper'
            ],
        ]));
        $this->crud->addField([
            'name' => 'location_id',
            'label' => trans('cat.location'),
            'type' => 'relationship',
            'placeholder' => 'Izberi lokacijo',
            'wrapper' => [
                'dusk' => 'location-input-wrapper'
            ],
        ]);
        $this->crud->addField([
            'name' => 'crud_photos_array',
            'label' => 'Slike',
            'type' => 'repeatable',
            'subfields' => [
                [
                    'name' => 'url',
                    'label' => trans('cat.photo'),
                    'type' => 'image',
                    'crop' => true,
                    'aspect_ratio' => 1,
                ],
                [
                    'name' => 'caption',
                    'label' => 'Naslov',
                    'type' => 'text',
                ],
            ],

            'new_item_label' => 'Dodaj sliko',
            'init_rows' => 0,
            'reorder' => true,
        ]);
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    public function store(): Response
    {
        $response = $this->traitStore();

        /** @var Cat $cat */
        $cat = $this->crud->getCurrentEntry();
        $photos = $this->crud->getRequest()->input('crud_photos_array');

        if ($photos) {
            foreach ($photos as $index => $payload) {
                CatPhotoService::create($cat, [
                    'url' => $payload['url'],
                    'index' => $index,
                    'caption' => $payload['caption'],
                ]);
            }
        }

        return $response;
    }

    /**
     * @throws ValidationException
     */
    public function update(): Response
    {
        $response = $this->traitUpdate();

        $this->updatePhotos();

        return $response;
    }

    /**
     * @throws ValidationException
     */
    private function updatePhotos(): void
    {
        /** @var Cat $cat */
        $cat = $this->crud->getCurrentEntry();
        $photos = $this->crud->getRequest()->input('crud_photos_array');

        if (!$photos) {
            $cat->photos()->delete();
            return;
        }

        $urlsInRequest = array_column($photos, 'url');
        $photosToDelete = $cat->photos->filter(function (CatPhoto $photo) use ($urlsInRequest) {
            return !in_array($photo->url, $urlsInRequest);
        });

        foreach ($photosToDelete as $photo) {
            $photo->delete();
        }

        foreach ($photos as $index => $payload) {
            $this->upsertPhoto($payload, $index);
        }
    }

    /**
     * @throws ValidationException
     */
    private function upsertPhoto(array $attributes, int $index): void
    {
        /** @var Cat $cat */
        $cat = $this->crud->getCurrentEntry();
        $url = $attributes['url'];
        $isNew = Str::startsWith($url, 'data:image');

        $attributes = [
            'url' => $url,
            'index' => $index,
            'caption' => $attributes['caption'],
        ];

        if ($isNew) {
            CatPhotoService::create($cat, $attributes);
        } else {
            $cat->photos->where('url', $url)->first()->update($attributes);
        }
    }
}
