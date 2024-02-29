<?php

namespace App\Observers;

use App\Models\PersonData;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        //        $this->createOrAssociatePersonData($user);
    }

    protected function createOrAssociatePersonData(User $user): void
    {
        $connectedPersonData = PersonData::firstWhere('email', $user->email);

        if ($connectedPersonData instanceof PersonData) {
            $connectedPersonData->user()->associate($user);
            $connectedPersonData->save();
        } else {
            $user->personData()->updateOrCreate([]);
        }
    }

    public function updated(User $user): void
    {
        //        $this->syncEmailWithPersonData($user);
    }

    protected function syncEmailWithPersonData(User $user): void
    {
        if ($user->personData->email !== $user->email) {
            $user->personData->update(['email' => $user->email]);
        }
    }
}
