<?php

namespace App\Enums;

enum RoleType: string
{
    case ADMIN = 'Admin';
    case MANAGER = 'Manager';
    case STAFF = 'Staff';
    case CLIENT = 'Client';
}
