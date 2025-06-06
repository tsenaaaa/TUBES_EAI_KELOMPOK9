<!-- public/form.php -->
<!DOCTYPE html>
<html>
<head><title>Room Service Order</title></head>
<body>

<h2>Order Room Service</h2>

<form action="invoice.php" method="POST">
  <label>Nama Tamu:</label><br>
  <input type="text" name="guest_name" required><br><br>

  <label>Nomor Kamar:</label><br>
  <input type="text" name="room_number" required><br><br>

  <label>Pilih Menu:</label><br>

  <?php
  $response = file_get_contents("http://localhost:8080/fetch_menu.php");
  $menus = json_decode($response, true);
  foreach ($menus as $menu) {
      echo '<input type="checkbox" name="menu_items[]" value="' . $menu['item_name'] . '|' . $menu['price'] . '"> ';
      echo $menu['item_name'] . ' - Rp' . $menu['price'] . '<br>';
  }
  ?>

  <br>
  <button type="submit">Pesan Sekarang</button>
</form>

</body>
</html>
