<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasSponsorshipForm;
use App\Http\Requests\SpecialSponsorshipRequest;
use App\Mail\SpecialSponsorshipMail;
use App\Models\PersonData;
use App\Models\SpecialSponsorship;
use Illuminate\Http\JsonResponse;

class SpecialSponsorshipController extends Controller
{
    use HasSponsorshipForm;

    private SpecialSponsorshipMail $sponsorshipMail;

    public function __construct(SpecialSponsorshipMail $sponsorshipMail)
    {
        $this->sponsorshipMail = $sponsorshipMail;
    }

    public function submitForm(SpecialSponsorshipRequest $request): JsonResponse
    {
        $payer = $this->getPayerFromFormData($request);
        $giftee = $this->getGifteeFromFormData($request);

        $isGift = $giftee instanceof PersonData;

        $params = [
            'type' => $request->input('type'),
            'sponsor_id' => $isGift ? $giftee->id : $payer->id,
            'payer_id' => $isGift ? $payer->id : null,
            'is_gift' => $isGift,
            'is_anonymous' => $request->input('is_anonymous'),
            'amount' => $request->input('amount'),
        ];

        $sponsorship = SpecialSponsorship::create($params);

        $this->sponsorshipMail->sendInitialInstructionsEmail($sponsorship);

        return response()->json(['status' => 'success']);
    }
}
