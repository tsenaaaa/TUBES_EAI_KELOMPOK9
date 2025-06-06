<?php
$response = file_get_contents("http://localhost:8080/fetch_menu.php");
$menus = json_decode($response, true);

echo "<h2>Menu Restoran</h2>";
foreach ($menus as $menu) {
    echo "<p>{$menu['item_name']} - Rp{$menu['price']}</p>";
}
