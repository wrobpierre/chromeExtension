<?php
session_start();

if (!isset($_SESSION['user'])) {
	header("Location: ../index.php");
	exit;
}
else{
	$checkUser = $_SESSION['user'];
	$inactive = 45*60; 
	$session_life = time() - $_SESSION['timeout'];
	if($session_life > $inactive){
		session_destroy(); 
		header("Location: ../index.php");
		exit;
	}
}

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
	<form id="form" action="management_questionnaire.php" method="post">
		<div id="form-questionnaire">
			<div id="questionnaire">
				<h1>Edit questionnaire</h1>
				<input class="input_question" type="hidden" name="action" value="edit">
				<input class="input_question" type="hidden" name="id_questionnaire">

				<label>Enter the title of your questionnaire&nbsp;:&nbsp;</label>
				<span class="error"></span>
				<input class="input_question" type="text" name="title"><br>
				
				<label>Statement of the questionnaire&nbsp;:</label>
				<!--<span class="error"></span>-->
				<textarea form="form" name="statement"></textarea><br><br>

				<label>Choose if you want correct questionnaires by yourself&nbsp;:</label>
				<span class="error"></span><br>
				<input type="radio" name="auto_correction" value="auto"><label>Automatic</label><br>
				<input type="radio" name="auto_correction" value="manuel"><label>Manuel</label><br><br>
				
				<label for="image_uploads">Select images to upload (PNG, JPG):</label>
				<span class="error"></span>
				<input type="file" name="image_uploads" accept=".jpg, .jpeg, .png">
				<div class="preview">
					<p>Aucun fichier sélectionné pour le moment</p>
				</div>

				<!--<label>Choose the type of your questionnaire&nbsp;:&nbsp;</label>
				<select class="select_question" name="type">
					<option value="article">Article</option>
					<option value="image">Image</option>
				</select>
				<div class="selector">
					<label></label>
					<span class="error"></span>
					<input class="input_question" type="text" name="data">
				</div>-->
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
		var checkUser = '<?php echo $checkUser ;?>';
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

				$('textarea[name="statement"]').val(dataParse[0]['statement']);

				if (dataParse[0]['auto_correction'] == 0) {
					$('input[type="radio"][value="manuel"]').prop("checked", true);
				} 
				else {
					$('input[type="radio"][value="auto"]').prop("checked", true);
				}

				$.each(dataParse, function(index, value){
					var div = $('<div></div>').attr('class','question').attr('id',value['id']);
					var lq = $('<label>Question&nbsp;:&nbsp;</label> <span class="error"></span>');
					var iq = $('<input class="input_question" type="text" name="q['+value['id']+'][question]">').attr('value', value['question'].split('(/=/)')[0]);

					var type_answer = $('<div class="type_answer"></div>'); 
					var lt = $('<br><label>Type of question : </label> <span class="error"></span>');
					var select = $('<select class="select_type" name="q['+value['id']+'][type_ques]">'
						+'<option></option>'
						+'<option value="text">TEXT</option>'
						+'<option value="number">NUMBER</option>'
						+'<option value="interval">INTERVAL</option>'
						+'<option value="radio">RADIO</option>'
						+'</select>');
					select.change(function(){
						$(this).parent().next().empty();
						var id_parent = $(this).parent().parent().attr('id');
						if ( $(this).find(':selected').text() == "" ) {
							$(this).parent().next().empty();
						}
						else if ( $(this).find(':selected').text() == "TEXT" ) {
							la = $('<label>Answer&nbsp;(put a list of words separated by a comma without spaces, eg: apple,pear,banana,...):&nbsp;</label><span class="error"></span>');
							ia = $('<input class="input_question" type="text" name="q['+id_parent+'][answer]"><br>');
							$(this).parent().next().append(la,ia);
						}
						else if ( $(this).find(':selected').text() == "NUMBER" ) {
							la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
							ia = $('<input class="input_question" type="number" step="any" name="q['+id_parent+'][answer]"><br>');
							particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
							particule_input = $('<input type="text" name="q['+id_parent+'][particule]">');
							$(this).parent().next().append(la,ia,particule_label,particule_input);
						}
						else if ( $(this).find(':selected').text() == "INTERVAL" ) {
							la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
							min = $('<input class="input_question" type="number" step="any" name="q['+id_parent+'][min]"><label> to </label>')
							max = $('<input class="input_question" type="number" step="any" name="q['+id_parent+'][max]">')
							$(this).parent().next().append(la,min,max);
						}
						else if ( $(this).find(':selected').text() == "RADIO" ) {
							la = $('<label>Enter the number of possible choices :</label><span class="error"></span>');
							number = $('<input type="number">');
							valid = $('<input type="button" value="create">');
							valid.click(function(){
								$(this).next().find('ol').empty();
								n = $(this).prev().val();
								for (var j = 0; j < n; j++) {
									var choice = $('<li id="'+j+'"></li>');
									var text = $('<input type="text" name="q['+id_parent+'][choices]['+j+'][choice]"><span class="error"></span>');
									var answer = $('<input type="checkbox" name="q['+id_parent+'][choices]['+j+'][answer]"> <label>answer</label>');
									choice.append(text,answer);
									$(this).next().children('ol').append(choice);
								}
							});
							radios = $('<div class="radios"> <ol type="A"></ol> </div>');
							$(this).parent().next().append(la,number,valid,radios);
						}
					});
					type_answer.append(lt,select);
					if ( $('input[type="radio"]:checked').val() == "manuel" ) {
						type_answer.hide();
						console.log('test');
					}

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
					else if (value['type_ques'] == 'radio') {
						select.val('radio');
						la = $('<label>Enter the number of possible choices :</label><span class="error"></span>');
						number = $('<input type="number">');
						valid = $('<input type="button" value="create">');
						valid.click(function(){
							$(this).next().find('ol').empty();
							n = $(this).prev().val();
							for (var j = 0; j < n; j++) {
								var choice = $('<li id="'+j+'"></li>');
								var text = $('<input type="text" name="q['+value['id']+'][choices]['+j+'][choice]"><span class="error"></span>');
								var answer = $('<input type="checkbox" name="q['+value['id']+'][choices]['+j+'][answer]"> <label>answer</label>');
								choice.append(text,answer);
								$(this).next().children('ol').append(choice);
							}
						});
						radios = $('<div class="radios"> <ol type="A"></ol> </div>');
						$.each( value['question'].split('(/=/)').slice(1, value['question'].split('(/=/)').length) , function(id,v){
							var choice = $('<li id="'+id+'"></li>');
							var text = $('<input type="text" name="q['+value['id']+'][choices]['+id+'][choice]" value="'+v+'"> <span class="error"></span>');
							var answer = $('<input type="checkbox" name="q['+value['id']+'][choices]['+id+'][answer]"> <label>answer</label>');
							if (id == value['answer']) {
								answer.prop("checked", true);	
							}
							choice.append(text,answer);
							radios.find('ol').append(choice);
						})
						answer.append(la,number,valid,radios);
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
					div.append(lq,iq,type_answer,answer,button,error,'<hr>');
					$('div#current > div.all_questions').append(div);
				});


$(document).ready(function(){
	var i = 0;
	$('input[type=radio][name=auto_correction]').change(function(){
		if (this.value == 'auto') {
			$('div.type_answer').show();
			$('div.answer').show();
		}
		else if (this.value == 'manuel') {
			$('div.type_answer').hide();
			$('div.answer').hide();
		}
	})

	$('input[value="add question"]').click(function(){
		var div = $('<div id="'+i+'"></div>').attr('class','question');
		var lq = $('<label>Question&nbsp;:&nbsp;</label> <span class="error"></span>');
		var iq = $('<input class="input_question" type="text" name="nq['+i+'][question]"><br>');

		var type_answer = $('<div class="type_answer"></div>'); 
		var lt = $('<label>Type of question : </label> <span class="error"></span>');
		var select = $('<select class="select_type" name="nq['+i+'][type_ques]">'
			+'<option></option>'
			+'<option value="text">TEXT</option>'
			+'<option value="number">NUMBER</option>'
			+'<option value="interval">INTERVAL</option>'
			+'<option value="radio">RADIO</option>'
			+'</select>');
		select.change(function(){
			$(this).parent().next().empty();
			var id_parent = $(this).parent().parent().attr('id');
			if ( $(this).find(':selected').text() == "" ) {
				$(this).parent().next().empty();
			}
			else if ( $(this).find(':selected').text() == "TEXT" ) {
				la = $('<label>Answer&nbsp;(put a list of words separated by a comma without spaces, eg: apple,pear,banana,...):&nbsp;</label><span class="error"></span>');
				ia = $('<input class="input_question" type="text" name="nq['+id_parent+'][answer]"><br>');
				$(this).parent().next().append(la,ia);
			}
			else if ( $(this).find(':selected').text() == "NUMBER" ) {
				la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
				ia = $('<input class="input_question" type="number" step="any" name="nq['+id_parent+'][answer]"><br>');
				particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
				particule_input = $('<input type="text" name="nq['+id_parent+'][particule]">');
				$(this).parent().next().append(la,ia,particule_label,particule_input);
			}
			else if ( $(this).find(':selected').text() == "INTERVAL" ) {
				la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
				min = $('<input class="input_question" type="number" step="any" name="nq['+id_parent+'][min]"><label> to </label>')
				max = $('<input class="input_question" type="number" step="any" name="nq['+id_parent+'][max]">')
				$(this).parent().next().append(la,min,max);
			}
			else if ( $(this).find(':selected').text() == "RADIO" ) {
				la = $('<label>Enter the number of possible choices :</label><span class="error"></span>');
				number = $('<input type="number">');
				valid = $('<input type="button" value="create">');
				valid.click(function(){
					$(this).next().find('ol').empty();
					n = $(this).prev().val();
					for (var j = 0; j < n; j++) {
						var choice = $('<li id="'+j+'"></li>');
						var text = $('<input type="text" name="nq['+id_parent+'][choices]['+j+'][choice]"><span class="error"></span>');
						var answer = $('<input type="checkbox" name="nq['+id_parent+'][choices]['+j+'][answer]"> <label>answer</label>');
						choice.append(text,answer);
						$(this).next().children('ol').append(choice);
					}
				});
				radios = $('<div class="radios"> <ol type="A"></ol> </div>');
				$(this).parent().next().append(la,number,valid,radios);
			}
		});
		type_answer.append(lt,select);
		if ( $('input[type="radio"]:checked').val() == "manuel" ) {
			type_answer.hide();
			console.log('test');
		}

		var answer = $('<div class="answer"></div>');

		var button = $('<input class="button_delete" type="button" value="delete question"><br>');
		button.click(function(){
			$(this).parent().remove();
		});
		var error = $('<span class="error"></span>');
		div.append(lq,iq,type_answer,answer,button,error,'<hr>');
		$('div#new > div.all_questions').append(div);
		i += 1;
	})

	var check_title = true;

	$('input[name="title"]').change(function(){
		var title = $('input[name="title"]').val();
		var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:'check_title', title:title });
		post.done(function(data){
			if (data != 0) {
				$('input[name="title"]').prev().text('Already exist');
				check_title = false;
			}
			else {
				$('input[name="title"]').prev().text('');
				check_title = true;	
			}		
		});
	})

	$('form').submit(function(){
		$('span.error').text('');
		valid = true;

		if ($('input[name="title"]').val() == "") {
			$('input[name="title"]').prev().text(' Missing title');
			valid = false;
		}
		else {
			if (!check_title) {
				$('input[name="title"]').prev().text('Already exist');
				valid = false;
			}
		}

		/*if ($('textarea[name="statement"]').val() == "") {
			$('textarea[name="statement"]').prev().text(' Missing statement');
			valid = false;
		}*/

		if ( $('input[type="radio"]:checked').length == 0 ) {
			$('input[type="radio"][value="auto"]').prev().prev().text('Select the type of correction')
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
				if ( $('input[type="radio"]:checked').val() == "auto" ) {
					if ($(this).find('select').find(':selected').text() == "") {
						$(this).find('select[name="q['+id+'][type_ques]"]').prev().text('Select the type of the question');
						valid = false;
					}
					else {
						if ($(this).find('select').find(':selected').text() == "TEXT" || $(this).find('select').find(':selected').text() == "NUMBER") {
							if ($(this).find('input[name="q['+id+'][answer]"]').val() == "") {
								$(this).find('input[name="q['+id+'][answer]"]').prev().text('Missing answer');
								valid = false;
							}
						}
						else if ($(this).find('select').find(':selected').text() == "INTERVAL") {
							if ($(this).find('input[name="q['+id+'][min]"]').val() == "" || $(this).find('input[name="q['+id+'][max]"]').val() == "") {
								$(this).find('input[name="q['+id+'][min]"]').prev().text('Missing min or max');
								valid = false;
							}	
						}
						else if ($(this).find('select').find(':selected').text() == "RADIO") {
							if ( $(this).find('.answer > .radios > ol > li').length == 0 ) {
								$(this).find('.answer > span.error').text('Please add at least one choice');
								valid = false;
							}
							else {
								if( $(this).find('.answer > .radios > ol > li').find(':checked').length == 0) {
									$(this).find('.answer > span.error').text('You need to choose the right choice');
									valid = false;
								}
								else if ($(this).find('.answer > .radios > ol > li').find(':checked').length > 1 ) {
									$(this).find('.answer > span.error').text('There can only be one right choice');
									valid = false;
								}	

								$(this).find('.answer > .radios > ol > li').each(function(index,value){
									if ( $(this).find('input[type=text]').val() == "" ) {
										$(this).find('span.error').text('Missing choice');
										valid = false;
									}
								});
							}
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
				if ( $('input[type="radio"]:checked').val() == "auto" ) {
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
						else if ($(this).find('select').find(':selected').text() == "RADIO") {
							if ( $(this).find('.answer > .radios > ol > li').length == 0 ) {
								$(this).find('.answer > span.error').text('Please add at least one choice');
								valid = false;
							}
							else {
								if( $(this).find('.answer > .radios > ol > li').find(':checked').length == 0) {
									$(this).find('.answer > span.error').text('You need to choose the right choice');
									valid = false;
								}
								else if ($(this).find('.answer > .radios > ol > li').find(':checked').length > 1 ) {
									$(this).find('.answer > span.error').text('There can only be one right choice');
									valid = false;
								}	

								$(this).find('.answer > .radios > ol > li').each(function(index,value){
									if ( $(this).find('input[type=text]').val() == "" ) {
										$(this).find('span.error').text('Missing choice');
										valid = false;
									}
								});
							}
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
<script src="../js/parallax.js-1.5.0/parallax.js"></script>
</body>
</html>