<?php
require_once __DIR__ . '/src/db.php'; // pastikan file ini konek ke DB kamu

try {
    $stmt = $pdo->query("SELECT * FROM menu");
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($menus);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
