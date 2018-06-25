<?php
session_start();
if(isset($_SESSION['user'])){
	$checkUser = $_SESSION['user'];
	$checkIdUser = $_SESSION['id'];
}
else{
	$checkUser = null;
	$checkIdUser = null;
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- <link rel="stylesheet" type="text/css" href="../css/questionnaire.css"> -->
	<meta charset="utf-8">
	<title></title>
	<?php include '../layout/includes.php'; ?>
</head>
<body>
	<?php include '../layout/header.php'; ?>
	<header class="parallax-window" data-parallax="scroll" style="height: 300px;" data-image-src="../img/select_question.jpg"></header>		
	<div id="form-questionnaire" class="w3-container w3-light-grey">
		<div class="w3-col l3 w3-padding"></div>
		<div id="questionnaire" class="w3-col l6 w3-col s12 w3-border w3-white w3-margin-bottom" style="margin-top:-80px;">
			<div id="content"  class="w3-center">
				<div id="title" class="w3-container w3-red">
					<h1></h1>
				</div>
				<div id="addQuestionnaire" class="w3-padding"></div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="../js/questionnaire.js"></script>
	<script src="../js/parallax.js-1.5.0/parallax.js" type="text/javascript"></script>
	<?php include '../layout/footer.php'; ?>
</body>
</html>