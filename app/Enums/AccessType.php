<?php

namespace App\Enums;

enum AccessType: string
{
    case General = 'general';
    case Zones = 'zones';
    case Tables = 'tables';
    case Numbered = 'numbered';
    case Free = 'free';
    case Uncontrolled = 'uncontrolled';
}
