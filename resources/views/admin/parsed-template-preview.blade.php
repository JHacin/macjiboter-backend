<button
    type="button"
    class="msg-preview-generate-btn btn btn-primary btn-sm mb-2"
    dusk="msg-preview-generate-btn"
    disabled
>
    Pokaži predogled pisma
</button>

<div class="msg-preview-disabled-text" dusk="msg-preview-disabled-text">
    <small>Preden se lahko generira predogled, je treba izbrati vrsto pisma, botra in muco.</small>
</div>

<div class="msg-preview-loader spinner-border text-primary" role="status" dusk="msg-preview-loader">
    <span class="sr-only">Nalagam...</span>
</div>

<div class="msg-preview-content" dusk="msg-preview-content">
    <div class="d-flex align-items-center mb-2">
        <div class="font-weight-bolder mr-2">Predogled poslanega besedila:</div>
        <button
            type="button"
            class="msg-preview-copy-to-clipboard btn btn-secondary btn-sm"
            data-clipboard-target=".msg-preview-content-body"
        >
            Kopiraj
        </button>
    </div>

    <div class="msg-preview-content-body bg-light p-2"></div>
</div>

@push('crud_fields_styles')
    <style>
        .msg-preview-loader,
        .msg-preview-content {
            display: none;
        }
    </style>
@endpush

@push('crud_fields_scripts')
    <script src="{{ asset('packages/clipboard-js/dist/clipboard.min.js') }}"></script>
    <!--suppress JSUnresolvedVariable -->
    <script>
        (function() {
          new ClipboardJS('.msg-preview-copy-to-clipboard');

          const $messageTypeSelect = $('select[name="messageType"]');
          const $sponsorSelect = $('select[name="sponsor"]');
          const $catSelect = $('select[name="cat"]');
          const $generateBtn = $('.msg-preview-generate-btn');
          const $btnDisabledText = $('.msg-preview-disabled-text');

          function enableGeneratingPreviewIfPossible() {
            if ($messageTypeSelect.val() && $sponsorSelect.val() && $catSelect.val()) {
              $generateBtn.removeAttr('disabled');
              $btnDisabledText.hide();
            } else {
              $generateBtn.attr('disabled', true);
              $btnDisabledText.show();
            }
          }

          enableGeneratingPreviewIfPossible() // if selects already have values, e.g. there's an error when submitting
          $messageTypeSelect.on('change', enableGeneratingPreviewIfPossible);
          $sponsorSelect.on('change', enableGeneratingPreviewIfPossible);
          $catSelect.on('change', enableGeneratingPreviewIfPossible);

          $generateBtn.on('click', function() {
            const $loader = $('.msg-preview-loader');
            const $content = $('.msg-preview-content');

            $loader.show();
            $content.hide();
            $generateBtn.attr('disabled', true);

            const urlWithPlaceholder =
              "{!! route(
                    'admin.get_parsed_template_preview',
                    ['message_type' => 'messageTypeId', 'sponsor' => 'sponsorId', 'cat' => 'catId'])
                !!}";

            const requestUrl = urlWithPlaceholder
              .replace('messageTypeId', $messageTypeSelect.val())
              .replace('sponsorId', $sponsorSelect.val())
              .replace('catId', $catSelect.val());

            $.ajax({
              url: requestUrl,
              type: 'GET',
              success: function(result) {
                const parsedText = result.parsedTemplate;
                $('.msg-preview-content-body').html($.parseHTML(parsedText));
                $content.show();
              },
              error: function(data) {
                alert(data.responseJSON.message);
              },
              complete: function() {
                $generateBtn.text('Osveži predogled pisma');
                $generateBtn.removeClass('btn-primary');
                $generateBtn.addClass('btn-info');
                $generateBtn.removeAttr('disabled');
                $loader.hide();
              }
            });
          });
        })()
    </script>
@endpush
