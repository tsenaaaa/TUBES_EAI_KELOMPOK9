<?php
require_once __DIR__ . '/../src/db.php';

$stmt = $pdo->query("SELECT * FROM room_orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Riwayat Room Service</h2>";

foreach ($orders as $order) {
    echo "<div style='border:1px solid #ccc; margin:10px; padding:10px'>";
    echo "<p><strong>{$order['guest_name']}</strong> (Kamar {$order['room_number']})</p>";
    echo "<p>Menu: {$order['menu_items']}</p>";
    echo "<p>Total: Rp{$order['total_amount']}</p>";
    echo "<p><small>{$order['created_at']}</small></p>";
    echo "</div>";
}
