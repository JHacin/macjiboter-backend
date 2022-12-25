<?php

namespace App\Models;

interface BankTransferFields
{
    public function getPaymentPurposeAttribute(): string;

    public function getPaymentReferenceNumberAttribute(): string;
}
