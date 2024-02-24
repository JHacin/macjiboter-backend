<?php

namespace App\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Request;

class AdminUserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        $userModel = config('backpack.permissionmanager.models.user');
        $userModel = new $userModel();
        $routeSegmentWithId = empty(config('backpack.base.route_prefix')) ? '2' : '3';

        $userId = $this->get('id') ?? Request::instance()->segment($routeSegmentWithId);

        if (! $userModel->find($userId)) {
            abort(400, 'Could not find that entry in the database.');
        }

        return [
            'email' => [
                'required',
                'string',
                'email',
                'unique:'.config('permission.table_names.users', 'users').',email,'.$userId,
            ],
            'name' => ['required'],
            'password' => ['confirmed'],
        ];
    }
}
