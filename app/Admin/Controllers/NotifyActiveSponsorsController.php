<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SponsorshipMessageHandler;
use App\Models\Cat;
use App\Models\SponsorshipMessage;
use App\Models\SponsorshipMessageType;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotifyActiveSponsorsController extends Controller
{
    private SponsorshipMessageHandler $sponsorshipMessageHandler;

    public function __construct(SponsorshipMessageHandler $sponsorshipMessageHandler)
    {
        $this->sponsorshipMessageHandler = $sponsorshipMessageHandler;
    }

    public function index(): View
    {
        $messageTypes = SponsorshipMessageType::all();
        $cats = Cat::withoutGlobalScopes()->get();

        return view('admin.notify-active-sponsors', [
            'messageTypes' => $messageTypes,
            'cats' => $cats,
        ]);
    }

    public function getActiveSponsorships(string $id): JsonResponse
    {
        $cat = Cat::withoutGlobalScopes()->where('id', $id)->first();
        $activeSponsorships = $cat->sponsorships->loadMissing('sponsor');

        return response()->json($activeSponsorships);
    }

    public function submit(Request $request): RedirectResponse
    {
        $request->validate([
            'messageType' => ['required', 'integer', Rule::exists('sponsorship_message_types', 'id')],
            'cat' => ['required', 'integer', Rule::exists('cats', 'id')],
        ]);

        $cat = Cat::withoutGlobalScopes()->find($request->input('cat'));
        $messageType = SponsorshipMessageType::find($request->input('messageType'));

        if (! $cat || ! $messageType) {
            return back()->with('error-message', 'Prišlo je do napake pri pridobivanju podatkov na strežniku.')->withInput();
        }

        if ($cat->sponsorships->isEmpty()) {
            return back()->with('error-message', 'Muca nima aktivnih botrov.')->withInput();
        }

        foreach ($cat->sponsorships as $sponsorship) {
            $message = new SponsorshipMessage;
            $message->sponsor_id = $sponsorship->sponsor_id;
            $message->cat_id = $cat->id;
            $message->message_type_id = $messageType->id;
            $message->save();
           
            try {
                $this->sponsorshipMessageHandler->send($message);
            } catch (Exception $e) {
                return back()->with('error-message', 'Prišlo je do napake, določeni botri morda niso dobili sporočila. Prosim javi napako na jan.hacin@gmail.com.');
            }
        }

        return back()->with('success-message', 'Pisma so bila poslana.');
    }
}
