<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>データベース</title>
</head>

<body>
  <?php
  $message = 'Customersテーブルの表示';
  $data = [];
  $mysqli = new mysqli('localhost', 'root', '', 'my_db');

  if ($mysqli->connect_errno) {
    $message = $mysqli->connect_errno . ":" . $mysqli->connect_error;
  } else {
    $mysqli->set_charset("utf8");
    $sql = 'select * from customers';

    if ($result = $mysqli->query($sql)) {
      $data = $result->fetch_all();
    } else {
      $message = mysqli_connect_errno() . ":" . mysqli_connect_error();
    }

    $mysqli->close();
  }
  ?>

  <h1>Index</h1>
  <p><?= $message ?></p>
  <table>
    <tr>
      <th>ID</th>
      <th>CORP.</th>
      <th>STAFF</th>
    </tr>
    <?php foreach ($data as $item) : ?>
      <tr>
        <th><?= $item[0] ?></th>
        <td><?= $item[1] ?></td>
        <td><?= $item[2] ?></td>
      </tr>
    <?php endforeach ?>
  </table>
</body>

</html>
