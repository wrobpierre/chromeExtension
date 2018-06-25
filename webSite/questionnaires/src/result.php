<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Result</title>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/includes.php'; ?>
</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/header.php'; ?>
	<header class="w3-container w3-red w3-center" style="padding:128px 16px">
		<h1 class="w3-margin w3-jumbo">Thanks for your participation !</h1>
		<button class="w3-button w3-black w3-padding-large w3-large w3-margin-top" onclick="window:location.href='/webSite/questionnaires/questionnaire'">Return to questionnaires</button>
	</header>
	<?php include '../layout/footer.php'; ?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="../js/parallax.js-1.5.0/parallax.js"></script>
</body>
</html>