<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasSponsorshipForm;
use App\Http\Requests\SpecialSponsorshipRequest;
use App\Mail\SpecialSponsorshipMail;
use App\Models\PersonData;
use App\Models\SpecialSponsorship;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'gift_message' => $isGift ? $request->input('gift_message') : null,
            'gift_notes' => $isGift ? $request->input('gift_notes') : null,
            'gift_requested_activation_date' => $isGift ? $request->input('gift_requested_activation_date') : null,
        ];

        $sponsorship = SpecialSponsorship::create($params);

        $this->sponsorshipMail->sendInitialInstructionsEmail($sponsorship);

        return response()->json(['status' => 'success']);
    }

    public function getRecent(Request $request): JsonResponse
    {
        $request->validate([
            'types' => ['required', 'array', Rule::in(SpecialSponsorship::TYPES)],
        ]);

        $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();

        $sponsorships = SpecialSponsorship::whereNotNull('confirmed_at')
            ->whereIn('type', $request->input('types'))
            ->whereDate('confirmed_at', '>=', $startOfPreviousMonth)
            ->get()
            ->each(function (SpecialSponsorship $sponsorship) {
                if (! $sponsorship->is_anonymous) {
                    $sponsorship->load('sponsor');
                }
            });

        return response()->json($sponsorships);
    }
}
