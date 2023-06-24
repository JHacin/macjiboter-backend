<?php

namespace App\Admin\Utilities;

use App\Utilities\CountryList;
use Illuminate\Database\Eloquent\Builder;

class CrudColumnGenerator
{
    public static function id(): array
    {
        return [
            'name' => 'id',
            'label' => trans('model.id'),
            'type' => 'text',
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('id', '=', $searchTerm);
            }
        ];
    }

    public static function createdAt($additions = []): array
    {
        return array_merge([
            'name' => 'created_at',
            'label' => trans('model.created_at'),
            'type' => 'datetime',
        ], $additions);
    }

    public static function updatedAt(): array
    {
        return [
            'name' => 'updated_at',
            'label' => trans('model.updated_at'),
            'type' => 'datetime',
        ];
    }

    public static function isActive(array $additions = []): array
    {
        return array_merge([
            'name' => 'is_active',
            'label' => trans('user.is_active'),
            'type' => 'boolean',
        ], $additions);
    }

    public static function address(array $additions = []): array
    {
        return array_merge([
            'name' => 'address',
            'label' => trans('person_data.address'),
            'type' => 'text',
        ], $additions);
    }

    public static function zipCode(array $additions = []): array
    {
        return array_merge([
            'name' => 'zip_code',
            'label' => trans('person_data.zip_code'),
            'type' => 'text',
        ], $additions);
    }

    public static function city(array $additions = []): array
    {
        return array_merge([
            'name' => 'city',
            'label' => trans('person_data.city'),
            'type' => 'text',
        ], $additions);
    }

    public static function country(array $additions = []): array
    {
        return array_merge([
            'name' => 'country',
            'label' => trans('person_data.country'),
            'type' => 'select_from_array',
            'options' => CountryList::COUNTRY_NAMES,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $code = CountryList::getCodeByName($searchTerm);
                if (!$code) {
                    return false;
                }
                return $query->orWhere('country', $code);
            }
        ], $additions);
    }

    public static function firstName(array $additions = []): array
    {
        return array_merge([
            'name' => 'first_name',
            'label' => trans('person_data.first_name'),
            'type' => 'text',
        ], $additions);
    }

    public static function lastName(array $additions = []): array
    {
        return array_merge([
            'name' => 'last_name',
            'label' => trans('person_data.last_name'),
            'type' => 'text',
        ], $additions);
    }

    public static function genderLabel(array $additions = []): array
    {
        return array_merge([
            'name' => 'gender_label',
            'label' => trans('person_data.gender'),
            'type' => 'text',
        ], $additions);
    }

    public static function dateOfBirth(array $additions = []): array
    {
        return array_merge([
            'name' => 'date_of_birth',
            'label' => trans('person_data.date_of_birth'),
            'type' => 'date',
        ], $additions);
    }

    public static function name(array $additions = []): array
    {
        return array_merge([
            'name' => 'name',
            'label' => trans('model.name'),
            'type' => 'text',
        ], $additions);
    }

    public static function email(array $additions = []): array
    {
        return array_merge([
            'name' => 'email',
            'label' => trans('user.email'),
            'type' => 'text',
        ], $additions);
    }

    public static function moneyColumn(array $additions = []): array
    {
        return array_merge([
            'type' => 'number',
            'suffix' => ' €',
            'decimals' => 2,
            'dec_point' => ',',
            'thousands_sep' => '.',
        ], $additions);
    }

    public static function cat(): array
    {
        return [
            'name' => 'cat',
            'entity' => 'unscopedCat', // removes status scope
            'label' => trans('cat.cat'),
            'type' => 'relationship',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url(config('routes.admin.cats'), [$related_key, 'edit']);
                },
            ],
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhereHas('cat', function (Builder $query) use ($searchTerm) {
                    $query
                        ->where('name', 'like', "%$searchTerm%")
                        ->orWhere('id', 'like', "%$searchTerm%");
                });
            }
        ];
    }
}
