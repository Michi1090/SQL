<?php
$message = 'データを送信してください。';
$data = [];

// 投稿データの保存
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
	$arr = [$_POST['name'], $_POST['mail'], $_POST['age'],];
	$f = fopen('data.csv', 'a');

	if ($f != false) {
		fputcsv($f, $arr);
		fclose($f);
	}

	$message = 'データを追加しました。';
}

// CSVデータの読み込み
$f = @fopen('data.csv', 'r');

if ($f != false) {
	while ($row = fgetcsv($f)) {
		array_unshift($data, $row);
	}

	fclose($f);
}
?>

<body>
	<h1>Index</h1>
	<p><?= $message ?></p>
	<table>
		<form method="post">
			<tr>
				<th><label>Name:</label></th>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<th><label>Mail:</label></th>
				<td><input type="text" name="mail"></td>
			</tr>
			<tr>
				<th><label>Age:</label></th>
				<td><input type="number" name="age"></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="追加"></td>
			</tr>
		</form>
	</table>
	<hr>
	<table>
		<?php foreach ($data as $item): ?>
			<tr>
				<td><?= $item[0] ?></td>
				<td><?= $item[1] ?></td>
				<td><?= $item[2] ?></td>
			</tr>
		<?php endforeach ?>
	</table>
</body>
