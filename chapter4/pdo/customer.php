<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer</title>
</head>

<?php

$join_sql = <<< __JOIN_SQL__
SELECT orders.id, name, price, corp, staff, mail, tel, address, quantity
FROM orders
JOIN products JOIN customers
ON orders.product_id = products.id
AND orders.customer_id = customers.id
__JOIN_SQL__;

$dsn = 'mysql:host=localhost;dbname=my_db;charset=utf8;';
$message = '注文管理';
$data = [];
$product_list = [];
$pdo = null;

try {
  $pdo = new PDO($dsn, 'root', '');
} catch (PDOException $e) {
  $message = $e->getMessage();
  exit;
}

if ($pdo != null) {
  $product_list = $pdo->query('SELECT * FROM products');

  if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $id = $_POST['product'];
    $product = $pdo->query('SELECT * FROM products WHERE id =' . $id)->fetch();
    $message = $product['name'] . 'の注文';
    $sql = 'SELECT DISTINCT customers.id, customers.corp, customers.staff, customers.tel
            FROM orders
            JOIN customers on orders.customer_id = customers.id
            WHERE orders.product_id =' . $id;

    try {
      $data = $pdo->query($sql);
    } catch (PDOException $e) {
      $message = $e->getMessage();
    }
  }
}

?>

<body>
  <h1>Index</h1>
  <p><?= $message ?></p>
  <hr>
  <form method="post">
    <select name="product">
      <option>--</option>
      <?php foreach ($product_list as $item) : ?>
        <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
      <?php endforeach ?>
    </select>
    <input type="submit">
  </form>

  <h3>Customers</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Corp.</th>
      <th>Staff</th>
      <th>Tel</th>
    </tr>
    <?php foreach ($data as $item) : ?>
      <tr>
        <th><?= $item['id'] ?></th>
        <td><?= $item['corp'] ?></td>
        <td><?= $item['staff'] ?></td>
        <td><?= $item['tel'] ?></td>
      </tr>
    <?php endforeach ?>
  </table>
</body>

</html>
