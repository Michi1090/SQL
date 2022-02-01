<?php
session_start();

// Login Check
if (isset($_SESSION['login']) == false) {
  header('Location: ./login.php');
  exit;
} else {
  if ($_SESSION['login'] != 'admin') {
    header('Location: ./login.php');
    exit;
  }
}

$message = '登録するIDとパスワードを記入ください。';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  $id = $_POST['id'];
  $pass = $_POST['pass'];

  $f = @fopen('id_and_password.csv', 'a');
  fputcsv($f, [$id, $pass]);
  fclose($f);
  $message = 'ID=' . $id . 'を登録しました。';
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
  <h1>Admin</h1>
  <p><?= $message ?></p>
  <table>
    <form action="./admin.php" method="post">
      <tr>
        <th><label>id:</label></th>
        <td><input type="text" name="id"></td>
      </tr>
      <tr>
        <th><label>password:</label></th>
        <td><input type="password" name="pass"></td>
      </tr>
      <tr>
        <th></th>
        <td><input type="submit" value="登録"></td>
      </tr>
    </form>
  </table>
</body>
</html>
