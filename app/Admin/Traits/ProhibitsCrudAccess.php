<?php

namespace App\Admin\Traits;

use App\Models\User;
use Backpack\CRUD\app\Exceptions\AccessDeniedException;
use Illuminate\Support\Facades\Auth;

trait ProhibitsCrudAccess
{
    /**
     * @throws AccessDeniedException
     */
    public function throwBelowSuperAdmin(): void
    {
        $user = Auth::user();

        if (! $user->hasRole(User::ROLE_SUPER_ADMIN)) {
            throw new AccessDeniedException(trans('backpack::crud.unauthorized_access'));
        }
    }

    /**
     * @throws AccessDeniedException
     */
    public function throwBelowAdmin(): void
    {
        $user = Auth::user();

        if (! $user->hasAnyRole([User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])) {
            throw new AccessDeniedException(trans('backpack::crud.unauthorized_access'));
        }
    }
}
