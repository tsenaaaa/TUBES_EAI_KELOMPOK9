<?php

require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\GraphQL\Resolvers\QueryType;
use App\GraphQL\Resolvers\MutationType;


$schema = new Schema([
    'query' => new QueryType(),
    'mutation' => new MutationType()
]);

// Tampilkan GraphiQL saat buka via browser (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>GraphiQL</title>
        <link href="https://unpkg.com/graphiql/graphiql.min.css" rel="stylesheet" />
    </head>
    <body style="margin: 0;">
        <div id="graphiql" style="height: 100vh;"></div>
        <script crossorigin src="https://unpkg.com/react/umd/react.production.min.js"></script>
        <script crossorigin src="https://unpkg.com/react-dom/umd/react-dom.production.min.js"></script>
        <script crossorigin src="https://unpkg.com/graphiql/graphiql.min.js"></script>
        <script>
            const graphQLFetcher = async graphQLParams => {
                const response = await fetch('/index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(graphQLParams)
                });
                return response.json().catch(() => response.text());
            };

            ReactDOM.render(
                React.createElement(GraphiQL, { fetcher: graphQLFetcher }),
                document.getElementById('graphiql'),
            );
        </script>

    </body>
    </html>
    <?php
    exit;
}

// Untuk POST request
try {
    $input = json_decode(file_get_contents('php://input'), true);
    $query = $input['query'] ?? null;
    $variables = $input['variables'] ?? null;
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage(),
            ]
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($output);