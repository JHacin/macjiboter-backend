<?php

namespace App\Admin\Traits;

trait ClearsModelGlobalScopes
{
    public function clearModelGlobalScopes(array $scopes): void
    {
        $this->crud->query = $this->crud->query->withoutGlobalScopes($scopes);
        $this->crud->model->withoutGlobalScopes($scopes);
    }
}
