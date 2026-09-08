<?php

namespace App\Enums;

enum PublicationConsentStatus: string
{
    case Pending = 'pending';
    case Granted = 'granted';
    case Denied = 'denied';
    case Revoked = 'revoked';
    case NotRequired = 'not_required';
}
