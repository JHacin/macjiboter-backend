@php
    use App\Models\SponsorshipMessageType;

    $messageTypes = SponsorshipMessageType::all()
@endphp

<label>Poslana pisma izbranemu botru:</label>

<div class="sent-messages-none-selected-msg" dusk="sent-messages-none-selected-msg">
   <small>Izbran še ni noben boter.</small>
</div>

<div class="sent-messages-loader spinner-border text-primary" role="status" dusk="sent-messages-loader">
    <span class="sr-only">Nalagam...</span>
</div>

<div class="row sent-messages-table-wrapper" dusk="sent-messages-table-wrapper">
    <div class="col-12 col-sm-6">
        <table class="sent-messages-table table table-sm table-striped table-bordered mb-0">
            <thead class="bg-primary">
                <tr>
                    <td>Vrsta pisma</td>
                    <td class="text-center">Poslano?</td>
                </tr>
            </thead>
            <tbody >
            @foreach($messageTypes as $messageType)
                <tr class="sent-message-row" data-message-type-id="{{ $messageType->id }}" data-status="">
                    <td>
                        <em>{{ $messageType->name }}</em>
                    </td>
                    <td class="text-center font-xl">
                        <i class="las la-check-circle sent-icon text-success"></i>
                        <i class="las la-times-circle not-sent-icon text-danger"></i>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="already-sent-warning text-danger mt-2" dusk="sent-messages-already-sent-warning">
    Pozor! Izbrano pismo je že bilo poslano temu botru.
</div>

@push('crud_fields_styles')
    <!--suppress CssUnusedSymbol -->
    <style>
        .sent-messages-table-wrapper,
        .sent-messages-loader,
        .already-sent-warning {
            display: none;
        }

        .sent-message-row[data-status="sent"] .sent-icon { display: block; }
        .sent-message-row[data-status="sent"] .not-sent-icon { display: none; }
        .sent-message-row[data-status="not-sent"] .sent-icon { display: none; }
        .sent-message-row[data-status="not-sent"] .not-sent-icon { display: block; }
    </style>
@endpush

@push('crud_fields_scripts')
    <script>
      (function (){
        const $messageTypeSelect = $('select[name="messageType"]');
        const $sponsorSelect = $('select[name="sponsor"]');
        const $emptyStateMsg = $('.sent-messages-none-selected-msg');
        const $table = $('.sent-messages-table-wrapper');
        const $loader = $('.sent-messages-loader');
        const $alreadySentWarning = $('.already-sent-warning');

        function toggleStatusIconsVisibility(sentMessageIds) {
          $('.sent-message-row').attr('data-status', 'not-sent');

          sentMessageIds.forEach(function(messageId) {
            const $associatedRow = $(`.sent-message-row[data-message-type-id="${messageId}"]`);
            $associatedRow.attr('data-status', 'sent');
          });
        }

        function checkIfMessageWasAlreadySent(sentMessageIds) {
          if (sentMessageIds.includes(Number($messageTypeSelect.val()))) {
            $alreadySentWarning.show();
          } else {
            $alreadySentWarning.hide();
          }
        }

        $sponsorSelect.on('change', function(e) {
          $table.hide();
          $emptyStateMsg.hide();
          $loader.show();

          if (!e.target.value) {
            $emptyStateMsg.show();
            $loader.hide();
            return;
          }

          const urlWithPlaceholder = "{{ route('admin.get_messages_sent_to_sponsor', ':sponsorId') }}";
          const requestUrl = urlWithPlaceholder.replace(':sponsorId', e.target.value);

          $.ajax({
            url: requestUrl,
            type: 'GET',
            success: function(result) {
              const sentMessageIds = result.map(function(message) {
                return message.message_type.id;
              });

              toggleStatusIconsVisibility(sentMessageIds)
              checkIfMessageWasAlreadySent(sentMessageIds)

              $table.show();
            },
            error: function() {
              alert('Prišlo je do napake pri pridobivanju podatkov o poslanih pismih.');
            },
            complete: function() {
              $loader.hide();
            }
          });
        });
        // if selects already have values, e.g. there's an error when submitting
        if ($sponsorSelect.val()) {
          $sponsorSelect.change()
        }

        $messageTypeSelect.on('change', function(e) {
          const $associatedRow = $(`.sent-message-row[data-message-type-id="${e.target.value}"]`);

          if ($associatedRow.attr('data-status') === 'sent') {
            $alreadySentWarning.show();
          } else {
            $alreadySentWarning.hide();
          }
        });
        // if selects already have values, e.g. there's an error when submitting
        if ($messageTypeSelect.val()) {
          $messageTypeSelect.change()
        }
      })()
    </script>
@endpush
