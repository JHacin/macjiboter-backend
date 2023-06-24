@php
    /** @var array $field */
    $data = json_encode($field["data"], JSON_PRETTY_PRINT);
@endphp

@include('crud::fields.inc.wrapper_start')
    <pre style="white-space: break-spaces;">{{ $data }}</pre>
@include('crud::fields.inc.wrapper_end')
