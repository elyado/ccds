<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case InReview = 'in_review';
    case Published = 'published';
    case SoldOut = 'sold_out';
    case Rescheduled = 'rescheduled';
    case Cancelled = 'cancelled';
    case Finished = 'finished';
    case Archived = 'archived';
}
