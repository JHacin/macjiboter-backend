<?php

namespace App\Admin\Controllers;

use App\Admin\Traits\ProhibitsCrudAccess;
use Backpack\CRUD\app\Exceptions\AccessDeniedException;
use Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController as BackpackPermissionCrudController;

class PermissionCrudController extends BackpackPermissionCrudController
{
    use ProhibitsCrudAccess;

    /**
     * {@inheritDoc}
     * @throws AccessDeniedException
     */
    public function setup(): void
    {
        parent::setup();
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.permissions'));
        $this->throwBelowSuperAdmin();
    }
}
