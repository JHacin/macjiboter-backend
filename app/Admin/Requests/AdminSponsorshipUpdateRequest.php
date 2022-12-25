<?php

namespace App\Admin\Requests;

class AdminSponsorshipUpdateRequest extends AdminSponsorshipRequest
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'ended_at' => ['nullable', 'date', 'before_or_equal:now'],
            ]
        );
    }
}
