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
<body>
	<form method="post" action="management_questionnaire.php">
		<input type="hidden" name="action" value="edit_user_result">
		<ul class="users">
			
		</ul>
		
		<input type="submit" value="Send" class="button_valid">
	</form>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">
		var adress = "http://163.172.59.102"
		//var adress = "http://localhost/chromeExtension"

		var param = document.URL.split('-')[1];
		//console.log(param); 
		var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"get_user_result", id:param });
		post.done(function(data){
			if (data != "") {
				dataParse = JSON.parse(data);
				console.log(dataParse);
				$.each(dataParse, function(index,value){
					var user = $('<li>User '+index+'</li>');
					var ul = $('<ul></ul>');
					$.each(value, function(i,v){
						var li = $('<li></li>');
						var question = $('<p>'+v.question+'</p>');
						var answer = $('<p>'+v.answer+'</p>');
						var right = $('<input type="radio" name="res['+index+']['+v.key_question+']" value="right"> <label>Right</label>');
						var wrong = $('<input type="radio" name="res['+index+']['+v.key_question+']" value="wrong"> <label>Wrong</label>');
						if (v.result == "1") {
							right[0]['checked'] = true;
						}
						else if (v.result == "0") {
							wrong[0]['checked'] = true;
						}
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
</body>
</html>