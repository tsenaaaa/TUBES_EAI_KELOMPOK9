<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Models\Booking;

class BookingQuery extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'bookings' => [
                    'type' => Type::listOf(\App\GraphQL\Types\BookingType::class),
                    'resolve' => function ($root, $args) {
                        return Booking::all();
                    },
                ],
            ],
        ];

        parent::__construct($config);
    }
}
