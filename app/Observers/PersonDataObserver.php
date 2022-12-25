<?php

namespace App\Observers;

use App\Mail\SponsorMailingListManager;
use App\Models\PersonData;

class PersonDataObserver
{
    private SponsorMailingListManager $mailingListManager;

    public function __construct(SponsorMailingListManager $mailingListManager)
    {
        $this->mailingListManager = $mailingListManager;
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    public function created(PersonData $personData): void
    {
//        $this->getEmailFromRelatedUser($personData);
        $this->mailingListManager->addToAllLists($personData);
    }

    public function updating(PersonData $personData): void
    {
        $this->updateMailingListProperties($personData);
    }

    public function updated(PersonData $personData): void
    {
//        if ($personData->user) {
//            $this->syncEmailWithUser($personData);
//        }
    }

    public function deleted(PersonData $personData): void
    {
        $this->mailingListManager->removeFromAllLists($personData);
    }

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    protected function getEmailFromRelatedUser(PersonData $personData): void
    {
        if ($personData->user) {
            $personData->update(['email' => $personData->user->email]);
        }
    }

    protected function syncEmailWithUser(PersonData $personData): void
    {
        if ($personData->user->email !== $personData->email) {
            $personData->user->update(['email' => $personData->email]);
        }
    }

    protected function updateMailingListProperties(PersonData $personData): void
    {
        $emailAssignedInMailingList = $personData->isDirty('email')
            ? $personData->getOriginal('email')
            : $personData->email;

        if (!$emailAssignedInMailingList) {
            return;
        }

        $this->mailingListManager->updateProperties($personData, $emailAssignedInMailingList);
    }
}
