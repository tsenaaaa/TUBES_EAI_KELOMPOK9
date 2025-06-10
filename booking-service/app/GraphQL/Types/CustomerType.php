<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Models\Customer;

class CustomerType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Customer',
            'fields' => function() {
                return [
                    'id' => Type::nonNull(Type::int()),
                    'name' => Type::string(),
                    'email' => Type::string(),
                    'phone' => Type::string(),
                    'bookings' => [
                        'type' => Type::listOf(BookingType::class),
                        'resolve' => function ($root) {
                            return $root->bookings()->get();
                        }
                    ],
                ];
            },
        ];

        parent::__construct($config);
    }
}
