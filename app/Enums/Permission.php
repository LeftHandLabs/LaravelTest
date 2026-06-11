<?php

namespace App\Enums;

enum Permission: string
{
    case USER = 'USER';
    case ADD_GAME = 'ADD_GAME';
    case API = 'API';
    case STAFF = 'STAFF';
    case ADMIN = 'ADMIN';
}
