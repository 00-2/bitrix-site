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
<?php
$placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true);

$deal = CRest::call(
	'crm.deal.get',
	[
		'ID' => $placement_options['ID']
	]
);
;?>
<body class="container-fluid">
<div class="alert alert-success" role="alert"><pre>
	<?
	displayValue($value);
	?>
	</pre>
</div>

</body>