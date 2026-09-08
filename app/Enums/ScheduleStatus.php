<?php

namespace App\Enums;

enum ScheduleStatus: string
{
    case Available = 'available';
    case LowAvailability = 'low_availability';
    case SoldOut = 'sold_out';
    case Cancelled = 'cancelled';
    case Rescheduled = 'rescheduled';
    case Finished = 'finished';
}
