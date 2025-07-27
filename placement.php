<?php
require_once (__DIR__.'/crest.php');

function displayValue($value) {
	if (is_array($value)) {
		$result = '';
		foreach ($value as $item) $result .= $item.', ';
		return $result;
	} else return $value;
}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="css/app.css">
	<script src="https://code.jquery.com/jquery-3.6.0.js"
		integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
		crossorigin="anonymous"></script>

	<title>Placement</title>
	<style>
		iframe {
			width: 100%;
			height: 95vh;
			border: none;
		}
	</style>
</head>
<body class="container-fluid">

<?php
// вывод debug
echo '<div class="alert alert-success" role="alert"><pre>';
print_r($_REQUEST);
echo '</pre></div>';

// Получение ID сделки из PLACEMENT_OPTIONS
$placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'] ?? '', true);
//$deal_id = $placement_options['ID'] ?? null;
$deal_id = 22125
if ($deal_id):
	$url = "http://51.250.13.43:5000/order_card?order_id={$deal_id}";
	echo "<iframe src=\"{$url}\"></iframe>";
else:
	echo "<div class=\"alert alert-danger\">Не удалось определить ID сделки</div>";
endif;
?>

</body>
</html>