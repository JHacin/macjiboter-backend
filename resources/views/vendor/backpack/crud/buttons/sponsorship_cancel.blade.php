@if ($crud->hasAccess('update') && $entry->is_active)
    <form
        class="d-inline"
        action="{{ route('admin.sponsorship_cancel', $entry) }}"
        method="POST"
        dusk="sponsorship-cancel-form"
    >
        @csrf
        <button type="submit" class="btn btn-sm btn-link" dusk="sponsorship-cancel-form-button">
            <i class="la la-times"></i>
            Prekini
        </button>
    </form>

    <script>
        if (typeof handleSponsorshipCancel !== 'function') {
            function handleSponsorshipCancel() {
                $(document).on('submit', 'form[dusk="sponsorship-cancel-form"]', function (event) {
                    event.preventDefault();
                    const form = $(this)[0];

                    swal({
                        title: "{!! trans('backpack::base.warning') !!}",
                        text: 'Ali res želite prekiniti botrstvo?',
                        icon: 'warning',
                        buttons: {
                            cancel: {
                                text: "{!! trans('backpack::crud.cancel') !!}",
                                value: null,
                                visible: true,
                                className: "bg-secondary",
                                closeModal: true,
                            },
                            delete: {
                                text: "{!! trans('forms.confirm') !!}",
                                value: true,
                                visible: true,
                                className: 'bg-danger',
                            }
                        },
                    }).then(function(value) {
                        if (value) {
                            form.submit();
                        }
                    })
                });
            }

            handleSponsorshipCancel();
        }
    </script>
@endif
