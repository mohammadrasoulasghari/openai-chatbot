<?php

namespace App\Enums;

enum SupportedServices: string
{
    case OPENAI = 'openai';
    case AVALAI = 'avalai';
}
