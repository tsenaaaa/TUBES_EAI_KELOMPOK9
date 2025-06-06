<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/db.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Reusable type for payments
$paymentType = new ObjectType([
    'name' => 'Payment',
    'fields' => [
        'id'             => Type::int(),
        'guest_name'     => Type::string(),
        'room_number'    => Type::string(),
        'service_type'   => Type::string(),
        'total_amount'   => Type::float(),
        'payment_method' => Type::string(),
        'notes'          => Type::string(),
        'created_at'     => Type::string()
    ]
]);

// Query
$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'ping' => [
            'type' => Type::string(),
            'resolve' => fn () => 'pong'
        ],
        'getPaymentById' => [
            'type' => $paymentType,
            'args' => [
                'id' => Type::nonNull(Type::int())
            ],
            'resolve' => function ($root, $args) {
                $pdo = connectDB();
                $stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
                $stmt->execute([$args['id']]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        ],
        'getAllPayments' => [
            'type' => Type::listOf($paymentType),
            'resolve' => function () {
                $pdo = connectDB();
                $stmt = $pdo->query("SELECT * FROM payments ORDER BY created_at DESC");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        ]
    ]
]);

// Mutation
$mutationType = new ObjectType([
    'name' => 'Mutation',
    'fields' => [
        'pay' => [
            'type' => Type::string(),
            'args' => [
                'guest_name'     => Type::nonNull(Type::string()),
                'room_number'    => Type::nonNull(Type::string()),
                'service_type'   => Type::nonNull(Type::string()),
                'total_amount'   => Type::nonNull(Type::float()),
                'payment_method' => Type::nonNull(Type::string()),
                'notes'          => Type::string(),
            ],
            'resolve' => function ($root, $args) {
                try {
                    $pdo = connectDB();
                    $stmt = $pdo->prepare("
                        INSERT INTO payments (guest_name, room_number, service_type, total_amount, payment_method, notes)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $args['guest_name'],
                        $args['room_number'],
                        $args['service_type'],
                        $args['total_amount'],
                        $args['payment_method'],
                        $args['notes'] ?? null
                    ]);

                    return "Pembayaran berhasil!";
                } catch (PDOException $e) {
                    return "Error: " . $e->getMessage();
                }
            }
        ]
    ]
]);

$schema = new Schema([
    'query'    => $queryType,
    'mutation' => $mutationType
]);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'] ?? null;
$variables = $input['variables'] ?? null;

if (!$query) {
    echo json_encode(['error' => 'Missing query parameter']);
    exit;
}

try {
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (Throwable $e) {
    $output = ['errors' => [['message' => $e->getMessage()]]];
}

echo json_encode($output);
