<?php

namespace App\Admin\Utilities;

use App\Models\Cat;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;

class CrudFilterGenerator
{
    public static function addBooleanFilter(CrudPanel $crud, string $fieldName, string $label): void
    {
        $options = [true => 'Da', false => 'Ne'];
        self::addDropdownFilter($crud, $fieldName, $label, $options);
    }

    public static function addDropdownFilter(CrudPanel $crud, string $fieldName, string $label, array $options): void
    {
        $crud->addFilter(
            [
                'name' => $fieldName,
                'type' => 'dropdown',
                'label' => $label,
            ],
            $options,
            function ($value) use ($crud, $fieldName) {
                $crud->addClause('where', $fieldName, $value);
            }
        );
    }

    public static function addCatFilter(CrudPanel $crud): void
    {
        $crud->addFilter(
            [
                'name' => 'cat',
                'type' => 'select2',
                'label' => trans('cat.cat'),
            ],
            function () {
                // clears status scope
                return Cat::withoutGlobalScopes()->get()->pluck('name_and_id', 'id')->toArray();
            },
            function ($value) use ($crud) {
                $crud->addClause('where', 'cat_id', $value);
            }
        );
    }
}
