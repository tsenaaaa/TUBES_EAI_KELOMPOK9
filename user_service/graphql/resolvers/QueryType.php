<?php
namespace App\GraphQL\Resolvers;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Types\UserType;
use App\GraphQL\TypeRegistry;

class QueryType extends ObjectType {
    public function __construct()
    {
        $config = [
            'fields' => [
                'users' => [
                    'type' => Type::listOf(TypeRegistry::user()),
                    'resolve' => function () {
                        $users = file_get_contents(__DIR__ . '/../../db/users.json');
                        return json_decode($users, true);
                    }
                ]
            ]
        ];

        parent::__construct($config); // HARUS ADA INI
    }
}

