<?php
namespace App\GraphQL;

use App\GraphQL\Types\UserType;

class TypeRegistry {
    private static $userType;

    public static function user(): UserType {
        if (!self::$userType) {
            self::$userType = new UserType();
        }
        return self::$userType;
    }
}
