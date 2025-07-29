<?php
$deal = CRest::call(
	'crm.deal.get',
	[
		'ID' => $placement_options['ID']
	]
);
print_r($deal);
?>