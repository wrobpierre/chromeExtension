<?php
session_start();

if (!isset($_SESSION['user'])) {
	header("Location: ../connexion/connect");
	exit;
}
else{
	$checkUser = $_SESSION['user'];
	$checkIdUser = $_SESSION['id'];
	$inactive = 45*60; 
	$session_life = time() - $_SESSION['timeout'];
	if($session_life > $inactive){
		session_destroy(); 
		header("Location: ../connexion/connect");
		exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Edit questionnaire</title>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/includes.php'; ?>
</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/header.php'; ?>
	<header class="parallax-window" style="height: 300px" data-parallax="scroll" data-image-src="../img/edit_question.jpg"></header>
	<div id="form-questionnaire" class="w3-container w3-light-grey">
		<form id="form" action="management_questionnaire.php" method="post" enctype="multipart/form-data">
			<div class="w3-col l3 w3-margin"></div>
			<div id="questionnaire" class=" w3-row w3-col l6 w3-col s12 w3-border w3-white w3-margin-bottom" style="margin-top: -80px;">
				<div class="w3-container w3-red">
					<h1>Questionnaire informations</h1>
				</div>
				<div class="w3-padding">
					<input class="input_question" type="hidden" name="action" value="edit">
					<input class="input_question" type="hidden" name="id_questionnaire">
					<input class="input_question" type="hidden" name="param_id">

					<div class="w3-section">
						<label>Enter the title of your questionnaire&nbsp;:&nbsp;</label>
						<span class="error"></span>
						<input class="w3-input" type="text" name="title"><br>
					</div>

					<div class="w3-section">
						<label>Statement of the questionnaire&nbsp;:</label>
						<!--<span class="error"></span>-->
						<textarea form="form" style="width: 100%;" name="statement"></textarea><br><br>
					</div>

					<div class="w3-section">
						<label>Choose if you want correct questionnaires by yourself&nbsp;:</label>
						<span class="error"></span><br>
						<input type="radio" class="w3-radio" name="auto_correction" value="auto"><label>Automatic</label><br>
						<input type="radio" class="w3-radio" name="auto_correction" value="manuel"><label>Manuel</label><br><br>
					</div>

					<div class="w3-row w3-margin">
						<div class="w3-col m3 w3-center">
							<label for="image_upload">Select images to upload (PNG, JPG):</label>
							<span class="error"></span>
						</div>
						<div class="w3-col m3 w3-center">
							<input type="file" id="image_upload" name="questionnaire" accept=".jpg, .jpeg, .png" style="display: none;">
							<input type="hidden" name="current_img">
						</div>
						<div class="w3-row preview w3-center">
							<p>No files selected</p>
						</div>
					</div>
				</div>
			</div>
			<div class="w3-col l3 w3-margin"></div>
			<div class="w3-col m6 w3-border w3-white w3-margin-top">
				<div class="w3-container w3-red">
					<h1>Current questions</h1>
				</div>				
				<div id="current">
					<div class="all_questions">

					</div>	
				</div>
			</div>
			<div class="w3-col l3 w3-margin"></div>
			<div class="w3-col m6 w3-border w3-white w3-margin-top w3-margin-bottom">
				<div class="w3-container w3-red">
					<h1>Add questions</h1><span class="error"></span>
				</div>	
				<div id="new">
					<input class="w3-button w3-blue w3-margin" style="width: 150px;" type="button" value="add question">
					<span class="error"></span><br>
					<div class="all_questions">

					</div>	
				</div>
			</div>
			<div class="w3-col l3 w3-margin"></div>
			<div class="w3-col m6 w3-margin-top w3-margin-bottom w3-center">
				<input class="w3-button w3-green w3-margin w3-xlarge" style="width: 200px;" type="submit" value="send">
			</div>
		</form>
	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/footer.php'; ?>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="/webSite/questionnaires/js/edit_questionnaire.js"></script>
	<script src="/webSite/js/parallax.js-1.5.0/parallax.js"></script>
</body>
</html>