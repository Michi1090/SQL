<?php
$dsn = 'mysql:host=localhost;dbname=my_db;charset=utf8;';
session_start();

// Login Check
if (isset($_SESSION['login']) == false) {
  header('Location: ./login.php');
  exit;
}

// PDOの用意
$pdo = null;

try {
  $pdo = new PDO($dsn, 'root', '');
} catch (PDOException $e) {
  $message = $e->getMessage();
  exit;
}

$message = 'メッセージを送信してください。';
$data = [];

// 投稿データの保存
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
  $msg = $_POST['msg'];
  $user_id = $_SESSION['login_id'] * 1;
  $sql = 'INSERT INTO messages SET message = :msg, user_id = :user_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':msg', $msg);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $message = 'メッセージを投稿しました。';
}

// messageテーブルの読み込み
$sql = 'SELECT messages.id, message, username FROM messages JOIN users ON messages.user_id = users.id ORDER BY posted DESC';
$data = $pdo->query($sql);

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
      <th>Username</th>
    </tr>
    <?php foreach ($data as $item) : ?>
      <tr>
        <td><?= $item['message'] ?></td>
        <td><?= $item['username'] ?></td>
      </tr>
    <?php endforeach ?>
  </table>
  <hr>
  <p class="logout"><a href="./logout.php">Logout</a></p>
</body>

</html>
