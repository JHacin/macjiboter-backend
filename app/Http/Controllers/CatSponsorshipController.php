<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasSponsorshipForm;
use App\Http\Requests\CatSponsorshipRequest;
use App\Mail\SponsorshipMail;
use App\Models\Cat;
use App\Models\PersonData;
use App\Models\Sponsorship;
use Illuminate\Http\JsonResponse;

class CatSponsorshipController extends Controller
{
    use HasSponsorshipForm;

    private SponsorshipMail $sponsorshipMail;

    public function __construct(SponsorshipMail $sponsorshipMail)
    {
        $this->sponsorshipMail = $sponsorshipMail;
    }

    public function submitForm(Cat $cat, CatSponsorshipRequest $request): JsonResponse
    {
        if ($cat->status !== Cat::STATUS_SEEKING_SPONSORS) {
            abort(403);
        }

        $payer = $this->getPayerFromFormData($request);
        $giftee = $this->getGifteeFromFormData($request);

        $isGift = $giftee instanceof PersonData;

        $sponsorship = Sponsorship::create([
            'sponsor_id' => $isGift ? $giftee->id : $payer->id,
            'payer_id' => $isGift ? $payer->id : null,
            'cat_id' => $cat->id,
            'monthly_amount' => $request->input('monthly_amount'),
            'payment_type' => $request->input('wants_direct_debit') === true
                ? Sponsorship::PAYMENT_TYPE_DIRECT_DEBIT
                : Sponsorship::PAYMENT_TYPE_BANK_TRANSFER,
            'is_gift' => $isGift,
            'requested_duration' => $isGift ? $request->input('requested_duration') : null,
            'is_anonymous' => $request->input('is_anonymous'),
            'is_active' => false,
        ]);

        $this->sponsorshipMail->sendInitialInstructionsEmail($sponsorship);

        return response()->json(['status' => 'success']);
    }
}
