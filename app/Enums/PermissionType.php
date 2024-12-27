<?php

namespace App\Enums;

enum PermissionType: string
{
    case CREATE = 'Create';
    case READ = 'Read';
    case UPDATE = 'Update';
    case DELETE = 'Delete';
}
