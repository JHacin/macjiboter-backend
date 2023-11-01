@extends(backpack_view('blank'))

@php
    $breadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        'Pošiljanje pošte aktivnim botrom' => false,
    ];
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            <span>Pošlji aktivnim botrom</span>
            <small>Pošlji pismo vsem aktivnim botrom za določeno muco.</small>
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 bold-labels">

            @if($errors->any())
                <div class="alert alert-danger pb-0">
                    <ul class="list-unstyled">
                        @foreach($errors->all() as $error)
                            <li><i class="la la-info-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success-message'))
                <div class="alert alert-success">
                    {{ session('success-message') }}
                </div>
            @endif

            @if(session('error-message'))
                <div class="alert alert-error">
                    {{ session('error-message') }}
                </div>
            @endif

            <form method="post" action="{{ backpack_url(config('routes.admin.notify_active_sponsors')) }}" class="notify-active-sponsors-form" data-message-type-selected="false">
                {!! csrf_field() !!}
                <div class="card">
                    <div class="card-body row">
                        <div class="form-group col-sm-12 required">
                            <label for="messageType-select">Vrsta pisma</label>
                            <select name="messageType" class="form-control select2_field" id="messageType-select" required="required" style="width: 100%">
                                <option value="">-</option>
                                @foreach($messageTypes as $messageType)
                                    @if(old("messageType") == $messageType->id)
                                        <option value="{{ $messageType->id }}" selected>{{ $messageType->name }}</option>
                                    @else
                                        <option value="{{ $messageType->id }}">{{ $messageType->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 required">
                            <label for="cat-select">Muca</label>
                            <select name="cat" class="form-control select2_field" id="cat-select" required="required" style="width: 100%">
                                <option value="">-</option>
                                @foreach($cats as $cat)
                                    @if(old("cat") == $cat->id)
                                        <option value="{{ $cat->id }}" selected>{{ $cat->name_and_id }}</option>
                                    @else
                                        <option value="{{ $cat->id }}">{{ $cat->name_and_id }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12">
                            <hr>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Aktivni botri</label>
                            <div class="no-cat-selected-msg">
                                <small>Izbrana še ni nobena muca.</small>
                            </div>
                            <div class="no-active-sponsors-msg">
                                <small>Muca nima aktivnih botrov.</small>
                            </div>
                            <div class="active-sponsors-loader spinner-border text-primary">
                                <span class="sr-only">Nalagam...</span>
                            </div>

                            <table class="active-sponsors-table table table-sm table-striped table-bordered table-hover mb-0">
                                <thead class="bg-primary">
                                    <tr>
                                        <td>ID</td>
                                        <td>Email</td>
                                        <td>Ime</td>
                                        <td>Priimek</td>
                                        <td>Botrstvo</td>
                                        <td>Dejanja</td>
                                    </tr>
                                </thead>
                                <tbody class="active-sponsors-table-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">
                        <span class="las la-paper-plane"></span> &nbsp;
                        <span>Pošlji</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('after_styles')
    @loadOnce('packages/select2/dist/css/select2.min.css')
    @loadOnce('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css')

    <style>
        .active-sponsors-loader,
        .active-sponsors-table,
        .no-active-sponsors-msg,
        .msg-preview-loader {
            display: none;
        }

        .notify-active-sponsors-form[data-message-type-selected="true"] .msg-preview-not-selected {
            display: none;
        }

        .notify-active-sponsors-form[data-message-type-selected="false"] .msg-preview-button {
            display: none;
        }
    </style>
@endpush


@push('after_scripts')
    @loadOnce('packages/select2/dist/js/select2.full.min.js')
    @loadOnce('packages/moment/min/moment-with-locales.min.js')
    @loadOnce('packages/clipboard-js/dist/clipboard.min.js')

    <script>
        $(document).ready(function() {
            moment.locale('{{app()->getLocale()}}');

            const $messageTypeSelect = $("#messageType-select");
            const $catSelect = $("#cat-select");
            const $emptyStateMsg = $(".no-cat-selected-msg");
            const $noActiveSponsorsMsg = $(".no-active-sponsors-msg");
            const $loader = $(".active-sponsors-loader");
            const $table = $(".active-sponsors-table");
            const $tableBody = $(".active-sponsors-table-body");
            const $form = $(".notify-active-sponsors-form");

            const select2Params = {
                theme: "bootstrap",
                dropdownParent: $(document.body),
                allowClear: true,
            }

            $messageTypeSelect.select2({ ...select2Params, placeholder: "Izberi vrsto pisma" });
            $catSelect.select2({ ...select2Params, placeholder: "Izberi muco" });

            $messageTypeSelect.on("change", event => {
                const messageTypeId = event.target.value;

                if (!messageTypeId) {
                    $form.attr("data-message-type-selected", "false");
                    return;
                }

                $form.attr("data-message-type-selected", "true");
            });

            $catSelect.on("change", event => {
                $table.hide();
                $tableBody.empty();
                $emptyStateMsg.hide();
                $loader.show();
                $noActiveSponsorsMsg.hide();

                const catId = event.target.value;

                if (!catId) {
                    $emptyStateMsg.show();
                    $loader.hide();
                    return;
                }

                const urlWithPlaceholder = "{{ route('admin.get_active_sponsorships_for_cat', ':catId') }}";
                const requestUrl = urlWithPlaceholder.replace(':catId', catId);

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    success: function(result) {
                        if (result.length === 0) {
                            $noActiveSponsorsMsg.show();
                            return;
                        }

                        result.forEach(sponsorship => {
                            const sponsor = sponsorship.sponsor;

                            const editUrlWithPlaceholder = "{{ route('botrstva.edit', ':sponsorshipId') }}";
                            const editUrl = editUrlWithPlaceholder.replace(":sponsorshipId", sponsorship.id);

                            const $tableRow = $("<tr></tr>")
                                .append(`<td>${sponsor.id}</td>`)
                                .append(`<td><a href="mailto:${sponsor.email}">${sponsor.email}</a></td>`)
                                .append(`<td>${sponsor.first_name}</td>`)
                                .append(`<td>${sponsor.last_name}</td>`)
                                .append(`<td>Vnešeno: ${moment(sponsorship.created_at).format("D. M. YYYY")} | <a href="${editUrl}" target="_blank">Povezava</a></td>`)
                                .append(`<td class="text-center">
                                  <span class="msg-preview-not-selected">Izberi vrsto pisma</span>
                                  <button type="button" class="msg-preview-button btn btn-link btn-sm" data-sponsor-id="${sponsor.id}">
                                    <i class="las la-search"></i> Predogled
                                  </button>
                                  <div class="msg-preview-loader spinner-border spinner-border-sm text-primary mx-auto">
                                    <span class="sr-only">Nalagam...</span>
                                  </div>
                                </td>`);

                            $tableBody.append($tableRow);
                        });

                        $table.show()
                    },
                    error: function() {
                        alert("Prišlo je do napake pri pridobivanju podatkov o aktivnih botrih za muco.");
                    },
                    complete: function() {
                        $loader.hide();
                    }
                });
            });

            $table.on("click", ".msg-preview-button", event => {
                const messageTypeId = $messageTypeSelect.val();
                const catId = $catSelect.val();
                const $button = $(event.target.closest(".msg-preview-button"));
                const $loader = $button.siblings(".msg-preview-loader");
                const sponsorId = $button.attr("data-sponsor-id");

                if (!messageTypeId || !catId || !sponsorId) {
                    alert("Prišlo je do napake, predogleda ni možno generirati.");
                    return;
                }

                const urlWithPlaceholder =
                    "{!! route(
                    'admin.get_parsed_template_preview',
                    ['message_type' => 'messageTypeId', 'sponsor' => 'sponsorId', 'cat' => 'catId'])
                !!}";

                const requestUrl = urlWithPlaceholder
                    .replace('messageTypeId', messageTypeId)
                    .replace('sponsorId', sponsorId)
                    .replace('catId', catId);

                $button.hide();
                $loader.show();

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    success: function(result) {
                        const parsedText = result.parsedTemplate;

                        const $modalContent = $("<div class='text-left'></div>");
                        $modalContent.append("<button class='copy-to-clipboard-button btn btn-primary btn-sm mb-3'>Kopiraj besedilo</button>");
                        const $previewContent = $("<div class='msg-preview-content' style='max-height: 40vh; overflow-y: auto;'></div>");
                        $previewContent.html($.parseHTML(parsedText));
                        $modalContent.append($previewContent);

                        const clipboard = new ClipboardJS('.copy-to-clipboard-button', {
                            target: () => $previewContent[0]
                        });

                        swal({
                            title: "Predogled pisma",
                            content: $modalContent[0],
                            buttons: {
                                cancel: {
                                    text: "Zapri",
                                    value: null,
                                    visible: true,
                                    className: "bg-secondary",
                                    closeModal: true,
                                },
                            },
                        }).then(() => {
                            clipboard.destroy();
                        })
                    },
                    error: function(data) {
                        alert(data.responseJSON.message);
                    },
                    complete: function() {
                        $button.show();
                        $loader.hide();
                    }
                });
            })
        });
    </script>
@endpush
