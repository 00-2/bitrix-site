<?php
require_once (__DIR__.'/crest.php');

function displayValue($value) {
	if (is_array($value)) {
		$result = '';
		foreach ($value as $item) $result .= $item.', ';
		return $result;

	} else return $value;
}

$placement_options = json_decode($_REQUEST['PLACEMENT_OPTIONS'], true);

$deal = CRest::call(
	'crm.deal.get',
	[
		'ID' => $placement_options['ID']
	]
);

require_once('crest.php');

$TestChange = CRest::call(
    'crm.deal.update',
    [
        'ID' => 123,
        'FIELDS' => [
            'TITLE' => 'Новое название сделки!',
            'TYPE_ID' => 'GOODS',
            'STAGE_ID' => 'WON',
            'IS_RECCURING' => 'Y',
            'IS_RETURN_CUSTOMER' => 'Y',
            'OPPORTUNITY' => 9999.99,
            'IS_MANUAL_OPPORTUNITY' => 'Y',
            'ASSIGNED_BY_ID' => 1,
            'UF_CRM_1725365197310' => 'Строка',
            'PARENT_ID_1032' => 1,
        ],
        'PARAMS' => [
            'REGISTER_SONET_EVENT' => 'N',
            'REGISTER_HISTORY_EVENT' => 'N',
        ],
    ]
);

$NameDeal = $deal['result']['TITLE'];
?>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/app.css">
	<!--link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"-->
	<script
		src="https://code.jquery.com/jquery-3.6.0.js"
		integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
		crossorigin="anonymous"></script>

	<title>Placement</title>
</head>
<body class="container-fluid">
<div class="alert alert-success" role="alert"><pre>
	<?php
	print_r($TestChange);
	?>
	</pre>
</div>
<?php


if ($deal['error'] == ''):
	?>
	<table class="table table-striped">
		<?php foreach ($deal['result'] as $field => $value):?>
			<tr>
				<td><?=$field;?></td>
				<td><?=displayValue($value);?></td>
			</tr>
		<?php endforeach;?>
	</table>
<?php endif;?>
</body>