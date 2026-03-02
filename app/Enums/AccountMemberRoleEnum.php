<?php

namespace App\Enums;

enum AccountMemberRoleEnum: string
{
    case Owner = 'owner';
    case Member = 'member';
    case Viewer = 'viewer';
}
