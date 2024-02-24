<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\AdminSpecialSponsorshipRequest;
use App\Admin\Utilities\CrudColumnGenerator;
use App\Admin\Utilities\CrudFieldGenerator;
use App\Admin\Utilities\CrudFilterGenerator;
use App\Models\PersonData;
use App\Models\SpecialSponsorship;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\ReviseOperation\ReviseOperation;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class SpecialSponsorshipCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ReviseOperation;
    use UpdateOperation;

    /**
     * @throws Exception
     */
    public function setup()
    {
        $this->crud->setModel(SpecialSponsorship::class);
        $this->crud->setRoute(config('backpack.base.route_prefix').'/'.config('routes.admin.special_sponsorships'));
        $this->crud->setEntityNameStrings('Posebno botrstvo', 'Posebna botrstva');
        $this->crud->enableExportButtons();
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn(CrudColumnGenerator::id());
        $this->crud->addColumn([
            'name' => 'payment_reference_number',
            'label' => trans('sponsorship.payment_reference_number'),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'type_label',
            'label' => trans('special_sponsorship.type'),
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'sponsor',
            'label' => trans('sponsor.sponsor'),
            'type' => 'relationship',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url(config('routes.admin.sponsors'), [$related_key, 'edit']);
                },
            ],
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhereHas('sponsor', function (Builder $query) use ($searchTerm) {
                    $query
                        ->where('email', 'like', "%$searchTerm%")
                        ->orWhere('id', 'like', "%$searchTerm%");
                });
            },
        ]);
        $this->crud->addColumn([
            'name' => 'payer',
            'label' => trans('sponsorship.payer'),
            'type' => 'relationship',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url(config('routes.admin.sponsors'), [$related_key, 'edit']);
                },
            ],
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhereHas('payer', function (Builder $query) use ($searchTerm) {
                    $query
                        ->where('email', 'like', "%$searchTerm%")
                        ->orWhere('id', 'like', "%$searchTerm%");
                });
            },
        ]);
        $this->crud->addColumn([
            'name' => 'confirmed_at',
            'label' => trans('special_sponsorship.confirmed_at'),
            'type' => 'date',
        ]);
        $this->crud->addColumn([
            'name' => 'is_anonymous',
            'label' => trans('special_sponsorship.is_anonymous'),
            'type' => 'boolean',
        ]);
        $this->crud->addColumn(CrudColumnGenerator::moneyColumn([
            'name' => 'amount',
            'label' => trans('special_sponsorship.amount'),
        ]));
        $this->crud->addColumn(CrudColumnGenerator::createdAt());
        $this->crud->addColumn(CrudColumnGenerator::updatedAt());

        $this->addFilters();
    }

    protected function addFilters()
    {
        CrudFilterGenerator::addDropdownFilter(
            $this->crud,
            'type',
            trans('special_sponsorship.type'),
            SpecialSponsorship::TYPE_LABELS
        );

        $this->crud->addFilter(
            [
                'name' => 'sponsor',
                'type' => 'select2',
                'label' => trans('sponsor.sponsor'),
            ],
            function () {
                return PersonData::all()->pluck('email_and_id', 'id')->toArray();
            },
            function ($value) {
                $this->crud->addClause('where', 'sponsor_id', $value);
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'payer',
                'type' => 'select2',
                'label' => trans('sponsorship.payer'),
            ],
            function () {
                return PersonData::all()->pluck('email_and_id', 'id')->toArray();
            },
            function ($value) {
                $this->crud->addClause('where', 'payer_id', $value);
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'confirmed_at',
                'label' => trans('special_sponsorship.confirmed_at'),
                'type' => 'date_range',
            ],
            false,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'confirmed_at', '>=', $dates->from);
                $this->crud->addClause('where', 'confirmed_at', '<=', $dates->to.' 23:59:59');
            }
        );

        CrudFilterGenerator::addBooleanFilter($this->crud, 'is_anonymous', trans('special_sponsorship.is_anonymous'));
        CrudFilterGenerator::addBooleanFilter($this->crud, 'is_gift', 'Podarjeno');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AdminSpecialSponsorshipRequest::class);

        $this->crud->addField([
            'name' => 'type',
            'label' => trans('special_sponsorship.type'),
            'type' => 'select_from_array',
            'options' => SpecialSponsorship::TYPE_LABELS,
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'type-input-wrapper',
            ],
        ]);

        $this->crud->addField([
            'name' => 'sponsor',
            'label' => trans('sponsor.sponsor'),
            'type' => 'relationship',
            'placeholder' => 'Izberi botra',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'sponsor-wrapper',
            ],
        ]);

        $this->crud->addField([
            'name' => 'is_gift',
            'label' => 'Botrstvo je podarjeno',
            'type' => 'checkbox',
            'wrapper' => [
                'dusk' => 'is_gift-wrapper',
            ],
        ]);

        $this->crud->addField([
            'name' => 'payer',
            'label' => trans('sponsorship.payer'),
            'type' => 'relationship',
            'placeholder' => 'Izberi plačnika',
            'wrapper' => [
                'dusk' => 'payer-wrapper',
            ],
        ]);

        $this->crud->addField(CrudFieldGenerator::moneyField([
            'name' => 'amount',
            'label' => trans('special_sponsorship.amount'),
            'wrapper' => [
                'dusk' => 'amount-wrapper',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]));

        $this->crud->addField(CrudFieldGenerator::dateField([
            'name' => 'confirmed_at',
            'label' => trans('special_sponsorship.confirmed_at'),
        ]));

        $this->crud->addField([
            'name' => 'is_anonymous',
            'label' => trans('special_sponsorship.is_anonymous'),
            'type' => 'checkbox',
            'wrapper' => [
                'dusk' => 'is_anonymous-wrapper',
            ],
        ]);

        $this->crud->addField(CrudFieldGenerator::dateField([
            'name' => 'gift_requested_activation_date',
            'label' => trans('special_sponsorship.gift_requested_activation_date'),
        ]));
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $this->crud->addField(CrudFieldGenerator::giftMessageField());
        $this->crud->addField(CrudFieldGenerator::giftNotesField());
    }
}
