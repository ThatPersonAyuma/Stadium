<?php

namespace App\Enums;

enum AccountStatus:string
{
    case WAITING = 'waiting';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
