<?php
require_once (__DIR__.'/crest.php');

function displayValue($value) {
	if (is_array($value)) {
		$result = '';
		foreach ($value as $item) $result .= $item . ', ';
		return rtrim($result, ', ');
	} else {
		return $value;
	}
}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/app.css">
	<script
		src="https://code.jquery.com/jquery-3.6.0.js"
		integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
		crossorigin="anonymous"></script>
	<title>Placement</title>
</head>
<body class="container-fluid">

<div class="alert alert-success" role="alert">
	<pre>
<?php print_r($_REQUEST); ?>
	</pre>
</div>

<?php
if (!empty($_REQUEST['PLACEMENT_OPTIONS'])) {
	$placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true);

	if (!empty($placement_options['ID'])) {
		$deal = CRest::call('crm.deal.get', [
			'ID' => $placement_options['ID']
		]);

		if (isset($deal['result']) && is_array($deal['result'])) {
			echo '<table class="table table-striped">';
			foreach ($deal['result'] as $field => $value) {
				echo '<tr>';
				echo '<td>' . htmlspecialchars($field) . '</td>';
				echo '<td>' . htmlspecialchars(displayValue($value)) . '</td>';
				echo '</tr>';
			}
			echo '</table>';
		} else {
			echo '<div class="alert alert-danger">Ошибка получения сделки: ' . htmlspecialchars($deal['error_description'] ?? 'Неизвестная ошибка') . '</div>';
		}
	} else {
		echo '<div class="alert alert-warning">Не передан ID сделки</div>';
	}
} else {
	echo '<div class="alert alert-warning">Нет PLACEMENT_OPTIONS</div>';
}
?>

</body>
</html>
