<?php
$message = '項目を選択してください';
$select = [];
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
	if (isset($_POST['select'])) {
		$data = implode(',', $_POST['select']);
		$message = '選択項目:[' . $data . ']';
		$select = $_POST['select'];
	} else {
		$message = '※何も選んでいません。';
		$select = "";
	}
}
?>

<body>
	<h1>Index</h1>
	<p><?= $message ?></p>
	<form method="post">
		<select name="select[]" size="4" multiple>
			<option value="One" <?= in_array('One', $select) ? 'selected' : '' ?>>One</option>
			<option value="Two" <?= in_array('Two', $select) ? 'selected' : '' ?>>Two</option>
			<option value="Three" <?= in_array('Three', $select) ? 'selected' : '' ?>>Three</option>
		</select>
		<input type="submit">
	</form>
</body>
