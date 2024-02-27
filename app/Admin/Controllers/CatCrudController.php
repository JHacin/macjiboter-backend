<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminCatRequest;
use App\Admin\Traits\ClearsModelGlobalScopes;
use App\Admin\Traits\DisplaysOldWebsiteData;
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
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CatCrudController extends CrudController
{
    use ClearsModelGlobalScopes, DisplaysOldWebsiteData;
    use CreateOperation {
        store as traitStore;
    }
    use DeleteOperation {
        destroy as traitDestroy;
    }
    use ListOperation;
    use ReviseOperation;
    use UpdateOperation {
        update as traitUpdate;
    }

    /**
     * @throws Exception
     */
    public function setup(): void
    {
        $this->crud->setModel(Cat::class);
        $this->clearModelGlobalScopes([Cat::SCOPE_ONLY_PUBLICLY_VISIBLE_CATS]);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/'.config('routes.admin.cats'));
        $this->crud->setEntityNameStrings('Muca', 'Muce');
        $this->crud->setSubheading('Dodaj novo muco', 'create');
        $this->crud->setSubheading('Uredi muco', 'edit');
        $this->crud->addButtonFromModelFunction('line', 'open_view', 'openViewFromCrud');
        $this->crud->enableExportButtons();
    }

    protected function setupListOperation(): void
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
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'is_published',
            'label' => trans('cat.is_published'),
            'type' => 'boolean',
        ]);
        $this->crud->addColumn(CrudColumnGenerator::genderLabel());
        //        $this->crud->addColumn([
        //            'name' => 'date_of_arrival_mh',
        //            'label' => trans('cat.date_of_arrival_mh'),
        //            'type' => 'date',
        //        ]);
        //        $this->crud->addColumn([
        //            'name' => 'location',
        //            'label' => trans('cat.location'),
        //            'type' => 'relationship',
        //            'wrapper' => [
        //                'href' => function ($crud, $column, $entry, $related_key) {
        //                    return backpack_url(config('routes.admin.cat_locations'), [$related_key, 'edit']);
        //                },
        //            ],
        //            'searchLogic' => function (Builder $query, $column, $searchTerm) {
        //                $query->orWhereHas('location', function (Builder $query) use ($searchTerm) {
        //                    $query->where('name', 'like', "%$searchTerm%");
        //                });
        //            }
        //        ]);
        $this->crud->addColumn(CrudColumnGenerator::createdAt());
        $this->crud->addColumn(CrudColumnGenerator::updatedAt());

        $this->crud->orderBy('updated_at', 'DESC');

        $this->addFilters();
    }

    protected function addFilters(): void
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
        CrudFilterGenerator::addBooleanFilter($this->crud, 'is_published', trans('cat.is_published'));
    }

    protected function setupCreateOperation(): void
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
                'dusk' => 'name-input-wrapper',
            ],
            'tab' => 'Podatki',
        ]);
        $this->crud->addField([
            'name' => 'gender',
            'label' => trans('cat.gender'),
            'type' => 'radio',
            'options' => Cat::GENDER_LABELS,
            'inline' => true,
            'wrapper' => [
                'dusk' => 'gender-input-wrapper',
            ],
            'hint' => 'Pri skupinah muc spol ni obvezen.',
            'tab' => 'Podatki',
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
                'dusk' => 'status-input-wrapper',
            ],
            'hint' => '<em>išče botre</em>: objavljena, botrstvo je možno<br>'.
                '<em>trenutno ne išče botrov</em>: objavljena, botrstvo ni možno, prikazana je opomba<br>'.
                '<em>ne išče botrov</em>: ni objavljena, botrstvo ni možno<br>'.
                '<em>v novem domu</em>: ni objavljena, botrstvo ni možno<br>'.
                '<em>RIP</em>: ni objavljena, botrstvo ni možno<br>',
            'tab' => 'Podatki',
        ]);
        $this->crud->addField([
            'name' => 'is_published',
            'label' => trans('cat.is_published').'?',
            'type' => 'radio',
            'options' => [
                1 => 'Da',
                0 => 'Ne',
            ],
            'default' => 0,
            'inline' => true,
            'hint' => 'Ali naj se muco objavi. Na objavo vpliva tudi status.',
            'tab' => 'Podatki',
        ]);
        $this->crud->addField([
            'name' => 'is_group',
            'label' => trans('cat.is_group').'?',
            'type' => 'radio',
            'options' => [
                1 => 'Da',
                0 => 'Ne',
            ],
            'default' => 0,
            'inline' => true,
            'wrapper' => [
                'dusk' => 'is_group-input-wrapper',
            ],
            'hint' => 'Ali naj se ta vnos obravnava kot druge skupine - Čombe, Pozitivčki, Bubiji...',
            'tab' => 'Podatki',
        ]);
        $this->crud->addField(CrudFieldGenerator::richTextField([
            'name' => 'story',
            'label' => trans('cat.story'),
            'tab' => 'Zgodba',
        ]));
        $this->crud->addField(CrudFieldGenerator::dateField([
            'name' => 'date_of_birth',
            'label' => trans('cat.date_of_birth'),
            'wrapper' => [
                'dusk' => 'date-of-birth-input-wrapper',
            ],
            'tab' => 'Podatki',
        ]));
        $this->crud->addField(CrudFieldGenerator::dateField([
            'name' => 'date_of_arrival_mh',
            'label' => trans('cat.date_of_arrival_mh'),
            'wrapper' => [
                'dusk' => 'date-of-arrival-mh-input-wrapper',
            ],
            'tab' => 'Podatki',
        ]));
        $this->crud->addField([
            'name' => 'location_id',
            'label' => trans('cat.location'),
            'type' => 'relationship',
            'placeholder' => 'Izberi lokacijo',
            'wrapper' => [
                'dusk' => 'location-input-wrapper',
            ],
            'tab' => 'Podatki',
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
                    'hint' => 'Dovoljeni formati: .jpg/.jpeg, .png, .gif, .webp',
                ],
                [
                    'name' => 'caption',
                    'label' => 'Naslov',
                    'type' => 'text',
                ],
            ],

            'new_item_label' => 'Dodaj sliko',
            'init_rows' => 0,
            'max_rows' => 5,
            'reorder' => true,
            'tab' => 'Slike',
        ]);
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
        $this->displayOldWebsiteData('cat');
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

        $this->revalidateClientPage($this->getCurrentEntrySlug());

        return $response;
    }

    /**
     * @throws ValidationException
     */
    public function update(): Response
    {
        $slug = $this->getCurrentEntrySlug();
        $response = $this->traitUpdate();

        $this->updatePhotos();
        $this->revalidateClientPage($slug);

        return $response;
    }

    /**
     * @throws ValidationException
     */
    public function destroy($id): string
    {
        $slug = $this->getCurrentEntrySlug();

        $response = $this->traitDestroy($id);

        $this->revalidateClientPage($slug);

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

        if (! $photos) {
            $cat->photos()->delete();

            return;
        }

        $urlsInRequest = array_column($photos, 'url');
        $photosToDelete = $cat->photos->filter(function (CatPhoto $photo) use ($urlsInRequest) {
            return ! in_array($photo->url, $urlsInRequest);
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

    private function revalidateClientPage(string $slug)
    {
        $client = new Client(['base_uri' => config('app.frontend_url')]);

        $client->request('POST', 'api/revalidate-cat', [
            'query' => ['secret' => config('app.frontend_revalidate_secret')],
            'json' => ['slug' => $slug],
        ]);
    }

    private function getCurrentEntrySlug(): string
    {
        return $this->crud->getCurrentEntry()->slug;
    }
}
