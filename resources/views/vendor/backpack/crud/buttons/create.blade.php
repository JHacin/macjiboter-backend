@if ($crud->hasAccess('create'))
    <a
        href="{{ url($crud->route . '/create') }}"
        class="btn btn-primary crud-create-button"
        data-style="zoom-in"
    >
        <span class="ladda-label">
            <i class="la la-plus"></i> {{ trans('backpack::crud.add') }}
        </span>
    </a>
@endif
