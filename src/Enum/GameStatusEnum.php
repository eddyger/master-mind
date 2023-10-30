<?php

namespace App\Enum;

enum GameStatusEnum : string
{
    case IN_PROGRESS = 'En cours';
    case HAS_WON = 'GAGNE';
    case HAS_LOOSED = 'PERDU';
}
