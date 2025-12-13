<?php
$order_id = 22208;
$exec_vk_id = 305852380;
$backend_site = "production.startproj.ru";
$url = "https://$backend_site.ru/order_card/formal?order_id=$order_id&exec_vk_id=$exec_vk_id";
?>
<!DOCTYPE html>
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

	<style>
		body, html {
			margin: 0;
			padding: 0;
			height: 100%;
			background-color: #f5f5f5;
		}
		iframe {
			border: none;
			width: 100%;
			height: 100vh;
		}
	</style>

	<title>Order Card Viewer</title>
</head>
<body>
	<iframe src="<?php echo htmlspecialchars($url); ?>"></iframe>
</body>
</html>
