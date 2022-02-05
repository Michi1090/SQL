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
  $mysqli = mysqli_connect('localhost', 'root', '', 'my_db');

  if (mysqli_connect_errno()) {
    $message = mysqli_connect_errno() . ":" . mysqli_connect_error();
  } else {
    mysqli_set_charset($mysqli, "utf8");
    $sql = 'select * from customers';

    if ($result = mysqli_query($mysqli, $sql)) {
      $data = mysqli_fetch_all($result);
    } else {
      $message = mysqli_connect_errno() . ":" .
        mysqli_connect_error();
    }

    mysqli_close($mysqli);
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
