<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public const ROOT_ID = 1;
    public const ROOT_NAME = 'admin.role.superadmin';

    public const ADMIN_ID = 2;
    public const ADMIN_NAME = 'admin.role.admin';

    public const MODERATOR_ID = 3;
    public const MODERATOR_NAME = 'admin.role.moderator';

    public const MAPPING_BY_ID = [
        self::ROOT_ID => self::ROOT_NAME,
        self::ADMIN_ID => self::ADMIN_NAME,
        self::MODERATOR_ID => self::MODERATOR_NAME
    ];

    public const MAPPING_BY_NAME = [
        self::ROOT_NAME => self::ROOT_ID,
        self::ADMIN_NAME => self::ADMIN_ID,
        self::MODERATOR_NAME => self::MODERATOR_ID
    ];

    public static function getRoleName($id) : string {
        return trans(self::MAPPING_BY_ID[$id]);
    }
}
