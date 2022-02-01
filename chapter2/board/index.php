<?php
session_start();

// Login Check
if (isset($_SESSION['login']) == false) {
  header('Location: ./login.php');
  exit;
}

$message = 'メッセージを送信してください。';
$data = [];

// 投稿データの保存
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  $arr = [$_POST['msg'], $_SESSION['login']];
  $f = fopen('messages.csv', 'a');

  if ($f != false) {
    fputcsv($f, $arr);
    fclose($f);
  }

  $message = 'メッセージを投稿しました。';
}

// CSVデータの読み込み
$f = @fopen('messages.csv', 'r');

if ($f != false) {
  while ($row = fgetcsv($f)) {
    array_unshift($data, $row);
  }

  fclose($f);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>掲示板</title>
</head>

<body>
  <h1>Index</h1>
  <h2>"<?= $_SESSION['login'] ?>" logined.</h2>
  <p><?= $message ?></p>
  <table>
    <form method="post">
      <tr>
        <th><label>Message:</label></th>
        <td><input type="text" name="msg" size="60"></td>
        <td><input type="submit" value="投稿"></td>
      </tr>
    </form>
  </table>
  <h3>※投稿されたメッセージ</h3>
  <table>
    <tr>
      <th>Message</th>
      <th>ID</th>
    </tr>
    <?php foreach ($data as $item) : ?>
      <tr>
        <td><?= $item[0] ?></td>
        <td><?= $item[1] ?></td>
      </tr>
    <?php endforeach ?>
  </table>
  <hr>
  <p class="logout"><a href="./logout.php">Logout</a></p>
</body>

</html>
