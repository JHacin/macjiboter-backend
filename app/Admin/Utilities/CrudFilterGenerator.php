<?php

namespace App\Admin\Utilities;

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
}
