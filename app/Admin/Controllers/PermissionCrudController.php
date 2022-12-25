<?php

namespace App\Admin\Controllers;

use Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController as BackpackPermissionCrudController;

class PermissionCrudController extends BackpackPermissionCrudController
{
    /**
     * @inheritDoc
     */
    public function setup()
    {
        parent::setup();
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/' . config('routes.admin.permissions'));
    }
}
