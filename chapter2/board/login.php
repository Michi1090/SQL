<?php
session_start();
$message = 'IDとパスワードを入力ください。';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  $id = $_POST['id'];
  $pass = $_POST['pass'];

  $f = @fopen('id_and_password.csv', 'r');
  if ($f != false) {
    $flg = false;
    $message = 'IDまたはパスワードが違います。';

    while ($row = fgetcsv($f)) {
      if ($row[0] == $id and $row[1] == $pass) {
        $flg = true;
        break;
      }
    }

    fclose($f);

    if ($flg) {
      $_SESSION['login'] = $id;
      header('Location: ./');
      exit;
    }
  }
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
  <h1>Login</h1>
  <?php if (isset($_SESSION['login'])) : ?>
    <h2><?= $_SESSION['login'] ?></h2>
  <?php else : ?>
    <h2>Not login</h2>
  <?php endif ?>
  <p><?= $message ?></p>
  <table>
    <form action="./login.php" method="post">
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
        <td><input type="submit" value="Login"></td>
      </tr>
    </form>
  </table>
  <hr>
  <p class="logout"><a href="./logout.php">Logout</a></p>
</body>

</html>
