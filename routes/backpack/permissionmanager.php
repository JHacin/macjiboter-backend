<?php

Route::group([
    'namespace' => 'App\Admin\Controllers',
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', backpack_middleware()],
], function () {
    Route::crud(config('routes.admin.permissions'), 'PermissionCrudController');
    Route::crud(config('routes.admin.roles'), 'RoleCrudController');
    Route::crud(config('routes.admin.users'), 'UserCrudController');
});
