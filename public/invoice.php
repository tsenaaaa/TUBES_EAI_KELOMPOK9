<?php
require_once __DIR__ . '/../src/db.php';

$guestName = $_POST['guest_name'];
$roomNumber = $_POST['room_number'];
$menuItems = $_POST['menu_items'] ?? [];

$total = 0;
$itemList = [];

foreach ($menuItems as $item) {
    list($name, $price) = explode("|", $item);
    $total += (int)$price;
    $itemList[] = $name;
}

$menuString = implode(", ", $itemList);

// Simpan ke database
$stmt = $pdo->prepare("INSERT INTO room_orders (guest_name, room_number, menu_items, total_amount) VALUES (?, ?, ?, ?)");
$stmt->execute([$guestName, $roomNumber, $menuString, $total]);

// Tampilkan Invoice
echo "<h2>Invoice Room Service</h2>";
echo "<p>Nama Tamu: $guestName</p>";
echo "<p>Nomor Kamar: $roomNumber</p>";
echo "<ul>";
foreach ($itemList as $itemName) {
    echo "<li>$itemName</li>";
}
echo "</ul>";
echo "<strong>Total: Rp$total</strong>";
