<?php

namespace App\Enums;

enum RoleEnum: string
{
    use EnumToArray;

    case Admin = 'ADMIN';
    case Client = 'CLIENT';
    case Owner = 'OWNER';
}
