<?php
$dsn = 'mysql:host=localhost;dbname=my_db;charset=utf8;';

session_start();
$message = 'IDとパスワードを入力ください。';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  $id = $_POST['id'];
  $pass = $_POST['pass'];

  try {
    // username = :id のレコード数を取得
    $pdo = new PDO($dsn, 'root', '');
    $sql = 'SELECT COUNT(*) FROM users WHERE username = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $res = $stmt->fetch();

    if ($res['COUNT(*)'] == 0) {
      // 未登録ならusersに登録する
      $sql = 'INSERT INTO users SET username = :id, password = :pass';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $id);
      $stmt->bindValue(':pass', $pass);
      $stmt->execute();
    }

    // username = :id and password =: pass のレコード数を取得
    $sql = 'SELECT COUNT(*) FROM users where username = :id AND password = :pass';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':pass', $pass);
    $stmt->execute();
    $res = $stmt->fetch();

    if ($res['COUNT(*)'] == 1) {
      // レコードが見つかったらログイン処理
      $_SESSION['login'] = $id;
      $sql = 'SELECT id FROM users WHERE username = :id AND password = :pass';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $id);
      $stmt->bindValue(':pass', $pass);
      $stmt->execute();
      $res = $stmt->fetch();
      $_SESSION['login_id'] = $res['id'];
      header('Location: ./');
      exit;
    } else {
      $message = 'パスワードが違います。';
    }
  } catch (PDOException $e) {
    $message = $e->getMessage();
    exit;
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
