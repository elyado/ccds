<?php

namespace App\Enums;

enum RentalRequestStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Evaluation = 'evaluation';
    case Quoted = 'quoted';
    case Confirmed = 'confirmed';
    case Rejected = 'rejected';
    case Closed = 'closed';
}
