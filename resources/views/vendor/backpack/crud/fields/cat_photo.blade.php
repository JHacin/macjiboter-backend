@php
    /** @var array $field */
    $value = old($field['name']) ?? $field['default'] ?? '';
@endphp

@include('crud::fields.inc.wrapper_start')
<div class="wrapper" data-init-function="bpFieldInitImageAjax" data-state="">
    <div>
        <label>{!! $field['label'] !!}</label>
    </div>

    <img
        class="preview-image mb-2"
        data-handle="previewImage"
        src="{{ $value }}"
        alt="{{ $field['label'] }}"
        data-field-name="{{ $field['name'] }}"
    >

    <div class="btn-group align-items-center">
        <div class="btn btn-light btn-sm btn-file">
            {{ trans('backpack::crud.choose_file') }}
            <input
                type="file"
                accept="image/*"
                data-handle="uploadImage"
                data-field-name="{{ $field['name'] }}"
                @include('crud::fields.inc.attributes')
            >
            <input
                type="hidden"
                data-handle="hiddenImage"
                name="{{ $field['name'] }}"
                value="{{ $value }}"
            >
        </div>
        <button
            class="delete-button btn btn-light btn-sm"
            type="button"
            data-handle="remove"
        >
            <i class="la la-trash"></i>
        </button>
    </div>

    <div data-handle="crop-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Priprava slike</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        Sliko obreži na želeno velikost, nato klikni na gumb <strong>Potrdi</strong>.
                        Zaradi konsistentnosti morajo vse slike imeti enako razmerje dimenzij.
                    </div>
                    <div>
                        <img data-handle="mainImage" src="" alt="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Prekliči</button>
                    <button type="button" class="btn btn-primary" data-handle="modalSubmit">Potrdi</button>
                </div>
            </div>
        </div>
    </div>
</div>
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

@if ($crud->fieldTypeNotLoaded($field))
    @php
        /** @var Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud */
        /** @var array $field */
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    @push('crud_fields_styles')
        <link href="{{ asset('packages/cropperjs/dist/cropper.min.css') }}" rel="stylesheet" type="text/css" />
        <style>
            .preview-image {
                display: block;
                width: 200px;
            }

            img {
                max-width: 100%;
            }

            .btn-file {
                position: relative;
                overflow: hidden;
            }

            .btn-file input[type=file] {
                position: absolute;
                top: 0;
                right: 0;
                min-width: 100%;
                min-height: 100%;
                font-size: 100px;
                text-align: right;
                filter: alpha(opacity=0);
                opacity: 0;
                outline: none;
                background: white;
                cursor: inherit;
                display: block;
            }
        </style>
    @endpush

    @push('crud_fields_scripts')
        <script src="{{ asset('packages/cropperjs/dist/cropper.min.js') }}"></script>
        <script src="{{ asset('packages/jquery-cropper/dist/jquery-cropper.min.js') }}"></script>

        <script>
            function bpFieldInitImageAjax($element) {
                const $mainImage = $element.find('[data-handle=mainImage]');
                const $uploadImage = $element.find("[data-handle=uploadImage]");
                const $hiddenImage = $element.find("[data-handle=hiddenImage]");
                const $previewImage = $element.find("[data-handle=previewImage]");
                const $deleteBtn = $element.find("[data-handle=remove]");
                const $cropModal = $element.find("[data-handle=crop-modal]");
                const $cropModalSubmit = $cropModal.find('[data-handle=modalSubmit]');

                // https://github.com/fengyuanchen/cropperjs#options
                const cropperOptions = {
                    viewMode: 1,
                    aspectRatio: 1,
                }

                if (!$hiddenImage.val()){
                    $previewImage.hide();
                    $deleteBtn.hide();
                }

                $mainImage.attr('src', $hiddenImage.val());

                $uploadImage.on('change', function(event) {
                    $mainImage.cropper('destroy').attr('src', '');
                    $cropModal.appendTo('body').modal('show');

                    $cropModal.on('shown.bs.modal', function() {
                        const file = event.target.files[0];
                        const fr = new FileReader();
                        fr.readAsDataURL(file);

                        fr.onload = function() {
                            $uploadImage.val('');

                            $mainImage
                                .cropper(cropperOptions)
                                .cropper('reset', true)
                                .cropper('replace', this.result);
                        }
                    })

                    $cropModalSubmit.on('click', function () {
                        const imageURL = $mainImage.cropper('getCroppedCanvas').toDataURL('image/jpeg');
                        $hiddenImage.val(imageURL);
                        $previewImage.attr('src', imageURL);
                        $previewImage.show();
                        $deleteBtn.show();
                        $cropModal.modal('hide');
                    });
                });

                $deleteBtn.on('click', function () {
                    $hiddenImage.val('');
                    $previewImage.hide();
                    $mainImage.cropper('destroy').attr('src', '');
                    $previewImage.attr('src', '');
                    $deleteBtn.hide();
                });
            }
        </script>
    @endpush
@endif
