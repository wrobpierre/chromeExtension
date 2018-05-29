<?php
session_start();
if (!isset($_SESSION['user'])) {
	header("Location: ../index.php");
	exit;
}
$inactive = 60; 
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive){
	session_destroy(); 
	header("Location: ../index.php");
	exit;
}
$_SESSION['timeout']=time();
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/questionnaire.css">
	<meta charset="utf-8">
	<title>Edit questionnaire</title>
<!-- 	<style type="text/css">
	.all_questions {
		margin: 0 0 30px 0;
	}

	.error {
		color:red;
	}

	select {
		display: block;
		margin: 20px 0 20px 0;
	}

	div.selector + p {
		display: inline;
	}
</style> -->
</head>
<body>
	<header class="parallax-window" data-parallax="scroll" data-image-src="../img/edit_question.jpg"></header>
	<form action="management_questionnaire.php" method="post">
		<div id="form-questionnaire">
			<div id="questionnaire">
				<h1>Edit questionnaire</h1>
				<input class="input_question" type="hidden" name="action" value="edit">
				<input class="input_question" type="hidden" name="id_questionnaire">
				<label>Enter the title of your questionnaire&nbsp;:&nbsp;</label>
				<span class="error"></span>
				<input class="input_question" type="text" name="title"><br>
				<label>Choose the type of your questionnaire&nbsp;:&nbsp;</label>
				<select class="select_question" name="type">
					<option value="article">Article</option>
					<option value="image">Image</option>
				</select>
				<div class="selector">
					<label></label>
					<span class="error"></span>
					<input class="input_question" type="text" name="data">
				</div>
				<h2>Current questions :</h2>
				<div id="current">
					<div class="all_questions">

					</div>	
				</div>

				<h2>Add questions :</h2><span class="error"></span>
				<div id="new">
					<input class="button" type="button" value="add question">
					<span class="error"></span><br>
					<div class="all_questions">

					</div>	
				</div>
				<input class="button_valid" type="submit" value="send">
			</div>
		</div>
	</form>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">
		var adress = "http://163.172.59.102";
		// var adress = "http://localhost/chromeExtension";

		function getUrlParameter(sParam) {
			var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');

				if (sParameterName[0] === sParam) {
					return sParameterName[1] === undefined ? true : sParameterName[1];
				}
			}
		};
		var param = getUrlParameter('id');

		var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"get_questions_to_edit", id:param });

		post.done(function(data){
			if (data != '') {
				var dataParse = JSON.parse(data);
				console.log(dataParse);

				$('input[name="id_questionnaire"]').val(dataParse[0]['key_questionnaires']);

				$('input[name="title"]').val(dataParse[0]['title']);

				if (dataParse[0]['type'] == 0) {
					$('select[name="type"]').val('article');
					$('.selector').find('label').text('Link to your Article : ');
					$('input[name="data"]').attr('value', dataParse[0]['link']);
				} 
				else {
					$('select[name="type"]').val('image');
					$('.selector').find('label').text('Link to your Image : ');
					$('input[name="data"]').attr('value', dataParse[0]['link']);
				}

				$.each(dataParse, function(index, value){
					var div = $('<div></div>').attr('class','question').attr('id',value['id']);
					var lq = $('<label>Question&nbsp;:&nbsp;</label> <span class="error"></span>');
					var iq = $('<input class="input_question" type="text" name="q['+value['id']+'][question]">').attr('value', value['question']);

					var lt = $('<br><label>Type of question : </label> <span class="error"></span>');
					var select = $('<select class="select_type" name="q['+value['id']+'][type_ques]">'
						+'<option></option>'
						+'<option value="text">TEXT</option>'
						+'<option value="number">NUMBER</option>'
						+'<option value="interval">INTERVAL</option>'
						+'</select>');
					select.change(function(){
						$(this).next().empty();
						var id_parent = $(this).parent().attr('id');
						if ( $(this).find(':selected').text() == "" ) {
							$(this).next().empty();
						}
						else if ( $(this).find(':selected').text() == "TEXT" ) {
							la = $('<label>Answer&nbsp;(put a list of words separated by a comma without spaces, eg: apple,pear,banana,...):&nbsp;</label><span class="error"></span>');
							ia = $('<input class="input_question" type="text" name="q['+id_parent+'][answer]"><br>');
							$(this).next().append(la,ia);
						}
						else if ( $(this).find(':selected').text() == "NUMBER" ) {
							la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
							ia = $('<input class="input_question" type="number" step="any" name="q['+id_parent+'][answer]"><br>');
							particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
							particule_input = $('<input type="text" name="q['+id_parent+'][particule]">');
							$(this).next().append(la,ia,particule_label,particule_input);
						}
						else if ( $(this).find(':selected').text() == "INTERVAL" ) {
							la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
							min = $('<input class="input_question" type="number" step="any" name="q['+id_parent+'][min]"><label> to </label>')
							max = $('<input class="input_question" type="number" step="any" name="q['+id_parent+'][max]">')
							$(this).next().append(la,min,max);
						}
					});

					var answer = $('<div class="answer"></div>');

					if (value['type_ques'] == 'text') {
						select.val('text');
						la = $('<label>Answer&nbsp;(put a list of words separated by a comma without spaces, eg: apple,pear,banana,...):&nbsp;</label><span class="error"></span>');
						ia = $('<input class="input_question" type="text" name="q['+value['id']+'][answer]">').attr('value', value['answer'].toLowerCase());
						answer.append(la,ia);
					}
					else if (value['type_ques'] == 'number') {
						select.val('number');
						la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
						ia = $('<input class="input_question" type="number" step="any" name="q['+value['id']+'][answer]"><br>').val(value['answer'].split("/")[0]);
						particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
						particule_input = $('<input type="text" name="q['+value['id']+'][particule]">').val(value['answer'].split("/")[1]);
						answer.append(la,ia,particule_label,particule_input);
					}
					else if (value['type_ques'] == 'interval') {
						select.val('interval');
						la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
						min = $('<input class="input_question" type="number" step="any" name="q['+value['id']+'][min]">').val(value['answer'].split("/")[0]);
						to = $('<label> to </label>');
						max = $('<input class="input_question" type="number" step="any" name="q['+value['id']+'][max]">').val(value['answer'].split("/")[1]);
						answer.append(la,min,to,max);
					}

					var button = $('<input class="button_delete" type="button" value="delete question">');
					button.click(function(){
						var r = confirm('Are you sure you want to delete this question ? It will be permanently deleted.');
						if (r == true) {
							var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"delete_question", id:value['id'] });
							$(this).parent().remove();
						}
					});
					var error = $('<span class="error"></span>');
					div.append(lq,iq,lt,select,answer,button,error,'<hr>');
					$('div#current > div.all_questions').append(div);
				});


$(document).ready(function(){
	var i = 0;
	$('select').change(function(){
		if( $('select option:selected').text() == "Article" ) {
			$('.selector').find('label').text('Link to your Article : ');
		}
		else if ( $('select option:selected').text() == "Image" ) {
			$('.selector').find('label').text('Link to your Image : ');
		}
	});

	$('input[value="add question"]').click(function(){
		var div = $('<div id="'+i+'"></div>').attr('class','question');
		var lq = $('<label>Question&nbsp;:&nbsp;</label> <span class="error"></span>');
		var iq = $('<input class="input_question" type="text" name="nq['+i+'][question]"><br>');

		var lt = $('<label>Type of question : </label> <span class="error"></span>');
		var select = $('<select class="select_type" name="nq['+i+'][type_ques]">'
			+'<option></option>'
			+'<option value="text">TEXT</option>'
			+'<option value="number">NUMBER</option>'
			+'<option value="interval">INTERVAL</option>'
			+'</select>');
		select.change(function(){
			$(this).next().empty();
			var id_parent = $(this).parent().attr('id');
			if ( $(this).find(':selected').text() == "" ) {
				$(this).next().empty();
			}
			else if ( $(this).find(':selected').text() == "TEXT" ) {
				la = $('<label>Answer&nbsp;(put a list of words separated by a comma without spaces, eg: apple,pear,banana,...):&nbsp;</label><span class="error"></span>');
				ia = $('<input class="input_question" type="text" name="nq['+id_parent+'][answer]"><br>');
				$(this).next().append(la,ia);
			}
			else if ( $(this).find(':selected').text() == "NUMBER" ) {
				la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
				ia = $('<input class="input_question" type="number" step="any" name="nq['+id_parent+'][answer]"><br>');
				particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
				particule_input = $('<input type="text" name="nq['+id_parent+'][particule]">');
				$(this).next().append(la,ia,particule_label,particule_input);
			}
			else if ( $(this).find(':selected').text() == "INTERVAL" ) {
				la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
				min = $('<input class="input_question" type="number" step="any" name="nq['+id_parent+'][min]"><label> to </label>')
				max = $('<input class="input_question" type="number" step="any" name="nq['+id_parent+'][max]">')
				$(this).next().append(la,min,max);
			}
		});
		var answer = $('<div class="answer"></div>');

		var button = $('<input class="button_delete" type="button" value="delete question"><br>');
		button.click(function(){
			$(this).parent().remove();
		});
		var error = $('<span class="error"></span>');
		div.append(lq,iq,lt,select,answer,button,error,'<hr>');
		$('div#new > div.all_questions').append(div);
		i += 1;
	})

	$('form').submit(function(){
		$('span.error').text('');
		valid = true;

		if ($('input[name="title"]').val() == "") {
			$('input[name="title"] + span.error').text(' Missing title');
			valid = false;
		}

		if ($('input[name="data"]').val() == "") {
			$('div.selector span.error').text(' Missing link');
			valid = false;
		}

		if($('div#current > div.all_questions').children().length == 0 && $('div#new > div.all_questions').children().length == 0) {
			$('input[value="add question"]').next().text('Please add at least one question');
			valid = false;
		}
		else {
			$('div#current div.question').each(function(index, value){
				id = $(this).attr('id');
				if ($(this).find('input[name="q['+id+'][question]"]').val() == "") {
					$(this).find('input[name="q['+id+'][question]"]').prev().text('Missing question');
					valid = false;
				}
				if ($(this).find('select').find(':selected').text() == "") {
					console.log('2')
					$(this).find('select[name="q['+id+'][type_ques]"]').prev().text('Select the type of the question');
					valid = false;
				}
				else {
					if ($(this).find('select').find(':selected').text() == "TEXT" || $(this).find('select').find(':selected').text() == "NUMBER") {
						if ($(this).find('input[name="q['+id+'][answer]"]').val() == "") {
							console.log('3')
							$(this).find('input[name="q['+id+'][answer]"]').prev().text('Missing answer');
							valid = false;
						}
					}
					else if ($(this).find('select').find(':selected').text() == "INTERVAL") {
						if ($(this).find('input[name="q['+id+'][min]"]').val() == "" || $(this).find('input[name="q['+id+'][max]"]').val() == "") {
							console.log('4')
							$(this).find('input[name="q['+id+'][min]"]').prev().text('Missing min or max');
							valid = false;
						}	
					}
				}
			});

			$('div#new div.question').each(function(index, value){
				id = $(this).attr('id');
				if ($(this).find('input[name="nq['+id+'][question]"]').val() == "") {
					$(this).find('input[name="nq['+id+'][question]"]').prev().text('Missing question');
					valid = false;
				}
				if ($(this).find('select').find(':selected').text() == "") {
					$(this).find('select[name="nq['+id+'][type_ques]"]').prev().text('Select the type of the question');
					valid = false;
				}
				else {
					if ($(this).find('select').find(':selected').text() == "TEXT" || $(this).find('select').find(':selected').text() == "NUMBER") {
						if ($(this).find('input[name="nq['+id+'][answer]"]').val() == "") {
							$(this).find('input[name="nq['+id+'][answer]"]').prev().text('Missing answer');
							valid = false;
						}
					}
					else if ($(this).find('select').find(':selected').text() == "INTERVAL") {
						if ($(this).find('input[name="nq['+id+'][min]"]').val() == "" || $(this).find('input[name="nq['+id+'][max]"]').val() == "") {
							$(this).find('input[name="nq['+id+'][min]"]').prev().text('Missing min or max');
							valid = false;
						}	
					}
				}
			});
		}
		return valid;
	});
});
}
});
</script>
<script type="text/javascript">
	setInterval("deco()", 10000);

	function deco()
	{
		var timeNow = new Date().getTime();
		timeNow = timeNow * 0.001;
		timeNow = Math.floor(timeNow);

		var timeExpire = '<?php echo $_SESSION['timeout']; ?>' ; 
		if (timeNow >= timeExpire)
		{
			alert("You have been idle for a while, please reconnect..");
			window.location='../index.php';
		}
	}
</script>
<script src="../js/parallax.js-1.5.0/parallax.js"></script>
</body>
</html>