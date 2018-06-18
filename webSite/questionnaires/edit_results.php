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
	<link rel="stylesheet" type="text/css" href="../css/questionnaire.css">
	<meta charset="utf-8">
	<title>Edit users' results</title>
</head>
<?php include '../layout/includes.php'; ?>
<body>
	<?php include '../layout/header.php'; ?>
	<header class="parallax-window" data-parallax="scroll" style="height: 300px;" data-image-src="../img/list_user.jpg"></header>	
	<div id="form-questionnaire" class="w3-container w3-light-grey">
		<div class="w3-col l3 w3-padding"></div>
		<div class="w3-col l6 w3-col s12 w3-border w3-white w3-margin-bottom" style="margin-top:-80px;">
			<form method="post" action="management_questionnaire.php">
				<h1 class="w3-red w3-padding" style="margin:0;">Rate user responses</h1>
				<input type="hidden" name="action" value="edit_user_result">
				<ul class="users" style="list-style: none; padding: 0;">

				</ul>
				<div class="w3-col l12 w3-center w3-section">
					<input type="submit" value="Send" class="w3-button w3-green w3-large">
					
				</div>
			</form>
		</div>
	</div>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">
		var adress = "http://163.172.59.102"
		//var adress = "http://localhost/chromeExtension"

		var param = document.URL.split('-')[1];
		var nbUsers = 0;
		//console.log(param); 
		var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"get_user_result", id:param });
		post.done(function(data){
			if (data != "") {
				dataParse = JSON.parse(data);
				console.log(dataParse);
				$.each(dataParse, function(index,value){
					if(nbUsers%2 == 0){
						var user = $('<li class="w3-section w3-white w3-padding"><h2>User '+index+'</h2></li>');
					}
					else{
						var user = $('<li class="w3-section" style="background: #f5f6fa;"><h2 class="w3-padding">User '+index+'</h2></li>');

					}
					nbUsers++;
					var ul = $('<ul></ul>');
					$.each(value, function(i,v){
						var li = $('<li></li>');
						var question = $('<p>'+v.question+'</p>');
						var answer = $('<p>'+v.answer+'</p>');
						var right = $('<input type="radio" class="w3-radio w3-margin" name="res['+index+']['+v.key_question+']" value="right"> <label>Right</label>');
						var wrong = $('<input type="radio" class="w3-radio w3-margin" name="res['+index+']['+v.key_question+']" value="wrong"> <label>Wrong</label>');
						li.append(question,answer,right,wrong);
						ul.append(li);
					})
					user.append(ul);
					$('.users').append(user);
				})
			}
		});

		$('form').submit(function(){
			if ( $('input[type="radio"]:checked').length != $('input[type="radio"]').length/2 ) {
				return false;
			};
		});
	</script>
	<script src="../js/parallax.js-1.5.0/parallax.js" type="text/javascript"></script>
	<?php include '../layout/footer.php'; ?>
</body>
</html>