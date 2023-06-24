<?php

namespace App\Admin\Traits;

trait ClearsModelGlobalScopes
{
    public function clearModelGlobalScopes(array $scopes): void
    {
        $this->crud->query = clone $this->crud->totalQuery = $this->crud->query->withoutGlobalScopes($scopes);
        $this->crud->model->clearGlobalScopes($scopes);
    }
}
