<?php

namespace App\Admin\Controllers;

use App\Admin\Traits\ProhibitsCrudAccess;
use Backpack\CRUD\app\Exceptions\AccessDeniedException;
use Backpack\PermissionManager\app\Http\Controllers\RoleCrudController as BackpackRoleCrudController;

class RoleCrudController extends BackpackRoleCrudController
{
    use ProhibitsCrudAccess;

    /**
     * {@inheritDoc}
     * @throws AccessDeniedException
     */
    public function setup(): void
    {
        parent::setup();
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.roles'));
        $this->throwBelowSuperAdmin();
    }

    public function setupListOperation(): void
    {
        $this->crud->addColumn([
            'name' => 'label',
            'label' => trans('backpack::permissionmanager.name'),
            'type' => 'text',
        ]);

        parent::setupListOperation();

        $this->crud->modifyColumn(
            'users_count',
            [
                'wrapper' => [
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url(config('routes.admin.users') . '?role=' . $entry->getKey());
                    },
                ],
                'suffix' => ' uporabnikov',
            ]
        );

        $this->crud->removeColumn('name');
    }
}
