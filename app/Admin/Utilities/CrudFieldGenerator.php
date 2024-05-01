<?php

namespace App\Admin\Utilities;

use App\Models\PersonData;
use App\Models\UnscopedCat;
use App\Utilities\CountryList;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;

class CrudFieldGenerator
{
    public static function addAddressFields(CrudPanel $crudPanel, string $namePrefix = ''): void
    {
        $crudPanel->addField([
            'name' => $namePrefix.'address',
            'label' => trans('person_data.address'),
            'type' => 'text',
        ]);
        $crudPanel->addField([
            'name' => $namePrefix.'zip_code',
            'label' => trans('person_data.zip_code'),
            'type' => 'text',
        ]);
        $crudPanel->addField([
            'name' => $namePrefix.'city',
            'label' => trans('person_data.city'),
            'type' => 'text',
        ]);
        $crudPanel->addField([
            'name' => $namePrefix.'country',
            'label' => trans('person_data.country'),
            'type' => 'select2_from_array',
            'options' => CountryList::COUNTRY_NAMES,
            'allows_null' => true,
            'default' => CountryList::DEFAULT,
        ]);
    }

    public static function addPersonDataFields(CrudPanel $crudPanel): void
    {
        $isNested = ! ($crudPanel->getModel() instanceof PersonData);
        $namePrefix = $isNested ? 'personData.' : '';

        $crudPanel->addField([
            'name' => $namePrefix.'first_name',
            'label' => trans('person_data.first_name'),
            'type' => 'text',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'first_name-input-wrapper',
            ],
        ]);
        $crudPanel->addField([
            'name' => $namePrefix.'last_name',
            'label' => trans('person_data.last_name'),
            'type' => 'text',
            'attributes' => [
                'required' => 'required',
            ],
            'wrapper' => [
                'dusk' => 'last_name-input-wrapper',
            ],
        ]);
        $crudPanel->addField([
            'name' => $namePrefix.'gender',
            'label' => trans('person_data.gender'),
            'type' => 'select2_from_array',
            'options' => PersonData::GENDER_LABELS,
            'allows_null' => true,
            'wrapper' => [
                'dusk' => 'gender-input-wrapper',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ]);
        $crudPanel->addField([
            'name' => $namePrefix.'date_of_birth',
            'label' => trans('person_data.date_of_birth'),
            'type' => 'date_picker',
            'date_picker_options' => [
                'format' => 'dd. mm. yyyy',
            ],
            'wrapper' => [
                'dusk' => 'date_of_birth-input-wrapper',
            ],
        ]);

        self::addAddressFields($crudPanel, $namePrefix);
    }

    public static function moneyField(array $additions = []): array
    {
        return array_merge([
            'type' => 'number',
            'prefix' => '€',
            'hint' => 'Decimalne vrednosti naj bodo ločene s piko. Dovoljeni sta največ 2 decimalki.',
            'attributes' => array_merge([
                'min' => '0.00',
                'max' => config('validation.integer_max'),
                'step' => '1',
                'placeholder' => '0.00',
            ], $additions['attributes'] ?? []),
        ], $additions);
    }

    public static function dateField(array $additions = []): array
    {
        return array_merge([
            'type' => 'date',
        ], $additions);
    }

    public static function richTextField(array $additions = []): array
    {
        return array_merge([
            'type' => 'wysiwyg',
            'extra_plugins' => ['autogrow'],
            'options' => [
                'autoGrow_minHeight' => 300,
                'autoGrow_maxHeight' => 600,
                'autoGrow_onStartup' => true,
                'language' => 'sl',
                'disableNativeSpellChecker' => true,
                'linkDefaultProtocol' => 'https://',
                'removeButtons' => '',
                'format_tags' => 'p;h1;h2;h3',
                'toolbar' => [
                    [
                        'name' => 'clipboard',
                        'items' => ['Undo', 'Redo', '-', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
                    ],
                    [
                        'name' => 'links',
                        'items' => ['Link', 'Unlink'],
                    ],
                    [
                        'name' => 'document',
                        'items' => ['Source'],
                    ],
                    '/',
                    [
                        'name' => 'basicstyles',
                        'items' => ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
                    ],
                    [
                        'name' => 'paragraph',
                        'items' => ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
                    ],
                    [
                        'name' => 'styles',
                        'items' => ['Format'],
                    ],
                ],
            ],
        ], $additions);
    }

    public static function giftMessageField(): array
    {
        return [
            'name' => 'gift_message',
            'type' => 'textarea',
            'label' => trans('sponsorship.gift_message'),
            'attributes' => [
                'disabled' => true,
                'rows' => 4,
            ],
        ];
    }

    public static function giftNotesField(): array
    {
        return [
            'name' => 'gift_notes',
            'type' => 'textarea',
            'label' => trans('sponsorship.gift_notes'),
            'attributes' => [
                'disabled' => true,
                'rows' => 6,
            ],
        ];
    }

    public static function cat(): array
    {
        return [
            'name' => 'cat',
            'entity' => 'unscopedCat', // clears status scope
            'model' => UnscopedCat::class, // clears status scope
            'attribute' => 'name_and_id_and_status', // shows name, id and status label
            'label' => trans('cat.cat'),
            'type' => 'relationship',
            'placeholder' => 'Izberi muco',
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }
}
