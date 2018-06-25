<?php
session_start();

if (!isset($_SESSION['user'])) {
	header("Location: /webSite/connexion/connect");
	exit;
}
else{
	$checkUser = $_SESSION['user'];
	$inactive = 45*60; 
	$session_life = time() - $_SESSION['timeout'];
	if($session_life > $inactive){
		session_destroy(); 
		header("Location: /webSite/connexion/connect");
		exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add a questionnaire</title>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/includes.php'; ?>
</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/header.php'; ?>
	<header class="parallax-window" data-parallax="scroll" style="height: 300px;" data-image-src="../img/header_question.jpg"></header>

	<div id="form-questionnaire" class="w3-light-grey w3-container w3-padding-64">
		<form id="form" action="management_questionnaire.php" method="post" enctype="multipart/form-data">
			<div class="w3-col m3 w3-margin"></div>
			<div class="questionnaire">
				<div class="w3-col m6 w3-border w3-white" style="margin-top:-140px;">
					<input type="hidden" name="action" value="add">
					<input type="hidden" name="user_email" value="<?php echo $checkUser ;?>">
					<div class="w3-container w3-red">
						<h2>Questionnaire informations</h2>
					</div>
					<div class="w3-row w3-margin">
						<div class="w3-section">
							<label>Title of the questionnaire&nbsp;:</label>
							<span class="error"></span>	
							<input class="input_question w3-input" type="text" name="title"><br>							
						</div>
					</div>
					<div class="w3-row w3-margin">
						<div class="w3-section">
							<label>Statement of the questionnaire&nbsp;:</label><br>
							<textarea form="form" name="statement" style="width: 100%; max-width: 100%;"></textarea>							
						</div>

					</div>
					<div class="w3-row w3-margin">
						<div class="w3-section">
							<label>Choose if you want correct questionnaires by yourself&nbsp;:</label>
							<span class="error"></span><br>
							<div>
								<input type="radio" name="auto_correction" value="auto" checked="checked" class="w3-radio"><label>Automatic</label><br>
								<input type="radio" name="auto_correction" value="manuel" class="w3-radio"><label>Manual</label>
							</div>
						</div>
					</div>

					<div class="w3-row w3-margin">
						<div class="w3-col m3 w3-center">
							<label for="image_upload">Select images to upload (PNG, JPG):</label>
							<span class="error"></span>
						</div>
						<div class="w3-col m3 w3-center">
							<input type="file" id="image_upload" name="questionnaire" accept=".jpg, .jpeg, .png" style="display: none;">
						</div>
						<div class="w3-row preview w3-center">
							<p>No files selected</p>
						</div>
					</div>

					<div class="w3-row w3-margin"></div>
				</div>
				<div class="w3-col m3 w3-margin"></div>
				<div class="w3-col m6 w3-border w3-white w3-margin-top">
					<div class="w3-container w3-red">
						<h2>Your Questions</h2>
					</div>
					<div class="w3-row">
						<div class="w3-row">
							<input class="w3-input w3-blue w3-margin" style="width: 150px;" type="button" value="Add a question">
							<span class="error"></span><br>
							<div class="all_questions">

							</div>
						</div>
					</div>
				</div>
				<div class="w3-col m3 w3-margin"></div>
				<div class="w3-row w3-center">
					
					<input class="w3-btn w3-green w3-round w3-xlarge" type="submit" value="send">
				</div>
			</div>
		</form>
	</div>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="/webSite/questionnaires/js/add_questionnaire.js"></script>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/footer.php'; ?>
	<script src="/webSite/js/parallax.js-1.5.0/parallax.js" type="text/javascript"></script>
</body>
</html>