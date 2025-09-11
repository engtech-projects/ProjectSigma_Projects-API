<?php

namespace App\Enums;

enum SignatorySource: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';
}
