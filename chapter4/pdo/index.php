<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
</head>

<?php

$join_sql = <<< __JOIN_SQL__
SELECT orders.id, name, price, GROUP_CONCAT(DISTINCT corp)
FROM orders
JOIN products JOIN customers
ON orders.product_id = products.id
AND orders.customer_id = customers.id
GROUP BY orders.product_id
__JOIN_SQL__;

$dsn = 'mysql:host=localhost;dbname=my_db;charset=utf8;';
$message = '発注書';
$data = [];
$customer_list = [];
$product_list = [];

if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];
  $join_sql .= ' ORDER BY ' . $sort . ' asc';
}

$pdo = null;

try {
  $pdo = new PDO($dsn, 'root', '');
} catch (PDOException $e) {
  $message = $e->getMessage();
  exit;
}

if ($pdo != null) {
  // 表示処理
  $data = $pdo->query($join_sql);

  // 登録処理
  $customer_list = $pdo->query('select * from customers');
  $product_list = $pdo->query('select * from products');

  if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    try {
      $product = $_POST['product'];
      $customer = $_POST['customer'];
      $quantity = $_POST['quantity'];
      $sql = 'INSERT INTO orders (product_id, customer_id, quantity) VALUES(:product, :customer, :quantity)';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':product', $product);
      $stmt->bindValue(':customer', $customer);
      $stmt->bindValue(':quantity', $quantity);
      $stmt->execute();
    } catch (PDOException $e) {
      $message = $e->getMessage();
    }
  }
}

?>

<body>
  <h1>Index</h1>
  <p><?= $message ?></p>

  <form method="post">
    <table>
      <tr>
        <th>製品</th>
        <td>
          <select name="product">
            <option>--</option>
            <?php foreach ($product_list as $item) : ?>
              <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
            <?php endforeach ?>
          </select>
        </td>
      </tr>

      <tr>
        <th>顧客</th>
        <td>
          <select name="customer">
            <option>--</option>
            <?php foreach ($customer_list as $item) : ?>
              <option value="<?= $item['id'] ?>"><?= $item['corp'] ?></option>
            <?php endforeach ?>
          </select>
        </td>
      </tr>

      <tr>
        <th>注文数</th>
        <td><input type="number" name="quantity"></td>
      </tr>

      <tr>
        <th></th>
        <td><input type="submit"></td>
      </tr>
    </table>
  </form>
  <hr>
  <h3>Orders</h3>
  <table>
    <tr>
      <th><a href=".?sort=orders.id">ID</a></th>
      <th><a href=".?sort=products.name">Name</a></th>
      <th><a href=".?sort=products.price">Price</a></th>
      <th><a href=".?sort=customers.corp">Corp.</a></th>
    </tr>
    <?php foreach ($data as $item) : ?>
      <tr>
        <th><?= $item['id'] ?></th>
        <td><?= $item['name'] ?></td>
        <td><?= $item['price'] ?></td>
        <td>
          <ul>
            <?php foreach(explode(',', $item['GROUP_CONCAT(DISTINCT corp)']) as $cp): ?>
              <li><?= $cp ?></li>
            <?php endforeach ?>
          </ul>
        </td>
      </tr>
    <?php endforeach ?>
  </table>
</body>

</html>
