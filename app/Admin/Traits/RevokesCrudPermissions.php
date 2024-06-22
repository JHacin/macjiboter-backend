<?php

namespace App\Admin\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait RevokesCrudPermissions
{
    public function denyAccessBelowSuperAdmin(array $permissions): void
    {
        $user = Auth::user();

        if (! $user->hasRole(User::ROLE_SUPER_ADMIN)) {
            $this->crud->denyAccess($permissions);
        }
    }

    public function denyAccessBelowAdmin(array $permissions): void
    {
        $user = Auth::user();

        if (! $user->hasAnyRole([User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])) {
            $this->crud->denyAccess($permissions);
        }
    }
}
