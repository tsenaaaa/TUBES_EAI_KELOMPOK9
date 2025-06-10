<?php
namespace App\GraphQL\Resolvers;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Types\UserType;
use App\GraphQL\TypeRegistry;

class MutationType extends ObjectType {
    public function __construct() {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'registerUser' => [
                    'type' => TypeRegistry::user(),
                    'args' => [
                        'name' => Type::nonNull(Type::string()),
                        'email' => Type::nonNull(Type::string()),
                        'phone' => Type::string()
                    ],
                    'resolve' => function ($root, $args) {
                        $usersPath = __DIR__ . '/../../db/users.json';
                        $users = json_decode(file_get_contents($usersPath), true);
                        $newUser = [
                            'id' => count($users) + 1,
                            'name' => $args['name'],
                            'email' => $args['email'],
                            'phone' => $args['phone'] ?? ''
                        ];
                        $users[] = $newUser;
                        file_put_contents($usersPath, json_encode($users, JSON_PRETTY_PRINT));
                        return $newUser;
                    }
                ]
            ]
        ]);
    }
}
