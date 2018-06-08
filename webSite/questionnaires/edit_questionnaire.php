<?php
session_start();

if (!isset($_SESSION['user'])) {
	header("Location: ../index.php");
	exit;
}
else{
	$checkUser = $_SESSION['user'];
	$checkIdUser = $_SESSION['id'];
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
<!-- 	<link rel="stylesheet" type="text/css" href="../css/questionnaire.css">
-->	<meta charset="utf-8">
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
<?php include '../layout/includes.php'; ?>
</head>
<body>
	<?php include '../layout/header.php'; ?>
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

<?php include '../layout/footer.php'; ?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript">
	var adress = "http://163.172.59.102";
	var checkUser = '<?php echo $checkUser ;?>';
	var checkIdUser = '<?php echo $checkIdUser ;?>';
		// var adress = "http://localhost/chromeExtension";

		var fileTypes = ['image/jpeg','image/pjpeg','image/png'];
		var fileName = "";
		
		function updateImageDisplay() {
			input = $(this)[0];
			preview = $(this).parent().next()[0];
			while(preview.firstChild) {
				preview.removeChild(preview.firstChild);
			}

			var curFiles = input.files;
			if(curFiles.length === 0) {
				var para = document.createElement('p');
				para.textContent = 'No files currently selected for upload';
				preview.appendChild(para);
			} else {
				var list = document.createElement('ol');
				preview.appendChild(list);
				for(var i = 0; i < curFiles.length; i++) {
					var listItem = document.createElement('li');
					var para = document.createElement('p');
					if(validFileType(curFiles[i])) {
						fileName = curFiles[i].name;
						para.textContent = 'File name ' + curFiles[i].name + ', file size ' + returnFileSize(curFiles[i].size) + '.';
						var image = document.createElement('img');
						image.style.maxWidth = "15%";
						image.src = window.URL.createObjectURL(curFiles[i]);

						listItem.appendChild(image);
						listItem.appendChild(para);

					} else {
						para.textContent = 'File name ' + curFiles[i].name + ': Not a valid file type. Update your selection.';
						listItem.appendChild(para);
					}

					list.appendChild(listItem);
				}
			}
		}

		function validFileType(file) {
			for(var i = 0; i < fileTypes.length; i++) {
				if(file.type === fileTypes[i]) {
					return true;
				}
			}
			return false;
		}

		function returnFileSize(number) {
			if(number < 1024) {
				return number + ' octets';
			} else if(number >= 1024 && number < 1048576) {
				return (number/1024).toFixed(1) + ' Ko';
			} else if(number >= 1048576) {
				return (number/1048576).toFixed(1) + ' Mo';
			}
		}

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
		}

		var param = getUrlParameter('id');

		$('input[name="param_id"]').val(param);

		var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"get_questions_to_edit", id:param });

		post.done(function(data){
			if (data != '') {
				var dataParse = JSON.parse(data);
				console.log(dataParse);
				console.log(dataParse[0]);
				if(dataParse[0]['key_user'] != checkIdUser){
					document.location.href="/index.php"
				}

				$('input[name="id_questionnaire"]').val(dataParse[0]['key_questionnaires']);

				$('input[name="title"]').val(dataParse[0]['title']);

				$('textarea[name="statement"]').val(dataParse[0]['statement']);

				if (dataParse[0]['auto_correction'] == 0) {
					$('input[type="radio"][value="manuel"]').prop("checked", true);
				} 
				else {
					$('input[type="radio"][value="auto"]').prop("checked", true);
				}

				$('#image_upload')[0].addEventListener('change', updateImageDisplay);

				if (dataParse[0]['link_img'] != undefined) {
					$('#image_upload').parent().next().empty();
					$('#image_upload').parent().next().append('<label>Current image:</label> <img src="'+dataParse[0]['link_img']+'" alt="Error" style="max-width:15%;">');
					tmp = dataParse[0]['link_img'].split('/');
					$('input[name="current_img"]').val(tmp[tmp.length-1]);
				}

				$.each(dataParse, function(index, value){
					if(index%2){
						var div = $('<div class="w3-padding" style="background: #f5f6fa;"></div>').attr('id',value['id']);
					}
					else{
						var div = $('<div class="w3-padding" style="background: #FFFFFF;"></div>').attr('id',value['id']);
					}
					var lq = $('<label>Question&nbsp;:&nbsp;</label> <span class="error"></span>');
					var iq = $('<input class="w3-input" type="text" name="q['+value['id']+'][question]">').attr('value', value['question'].split('(/=/)')[0]);

					var div_img = $('<div class="w3-row w3-margin">'
						+'<div class="w3-col m3 w3-center">'
						+'<label for="image_question_'+value['id']+'">Select images to upload (PNG, JPG):</label>'
						+'<span class="error"></span>'
						+'</div>'
						+'<div class="w3-col m3 w3-center">'
						+'</div>'
						+'<div class="w3-row preview w3-center">'
						+'<p>No files selected</p>'
						+'</div>'
						+'</div>');

					var input_img = $('<input type="file" id="image_question_'+value['id']+'" name="question['+value['id']+']" accept=".jpg, .jpeg, .png" style="display: none;">');
					var current_img = $('<input type="hidden" name="q['+value['id']+'][current_img]" value="">');
					input_img[0].addEventListener('change', updateImageDisplay);
					div_img.find('.preview').prev().append(input_img,current_img);

					if (value['image'] != undefined) {
						div_img.find('.preview').empty();
						div_img.find('.preview').append('<label>Current image:</label> <img src="'+value['image']+'" alt="Error" style="max-width:15%;">');
						tmp = value['image'].split('/');
						current_img.val(tmp[tmp.length-1]);
					}

					var type_answer = $('<div class="type_answer w3-section"></div>'); 
					var lt = $('<br><label>Type of question : </label> <span class="error"></span>');
					var select = $('<select class="w3-select" name="q['+value['id']+'][type_ques]">'
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
							ia = $('<input class="w3-input" type="text" name="q['+id_parent+'][answer]"><br>');
							$(this).parent().next().append(la,ia);
						}
						else if ( $(this).find(':selected').text() == "NUMBER" ) {
							la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
							ia = $('<input class="w3-input" type="number" step="any" name="q['+id_parent+'][answer]"><br>');
							particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
							particule_input = $('<input type="text" class="w3-input" name="q['+id_parent+'][particule]">');
							$(this).parent().next().append(la,ia,particule_label,particule_input);
						}
						else if ( $(this).find(':selected').text() == "INTERVAL" ) {
							la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
							min = $('<input class="w3-input" type="number" step="any" name="q['+id_parent+'][min]"><label> to </label>')
							max = $('<input class="w3-input" type="number" step="any" name="q['+id_parent+'][max]">')
							$(this).parent().next().append(la,min,max);
						}
						else if ( $(this).find(':selected').text() == "RADIO" ) {
							la = $('<label class="w3-col l12">Enter the number of possible choices :</label><span class="error"></span>');
							number = $('<input class="w3-input w3-col m8 w3-margin-right" type="number">');
							valid = $('<input class="class="w3-button w3-blue" type="button" value="create">');
							valid.click(function(){
								$(this).next().find('ol').empty();
								n = $(this).prev().val();
								for (var j = 0; j < n; j++) {
									var choice = $('<li id="'+j+'" class="w3-col m12"></li>');
									var text = $('<input class="w3-input w3-col m8" type="text" name="q['+id_parent+'][choices]['+j+'][choice]"><span class="error"></span>');
									var answer = $('<input class="w3-check w3-section" type="checkbox" name="q['+id_parent+'][choices]['+j+'][answer]"> <label>answer</label>');
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

					var answer = $('<div class="answer" class="w3-section"></div>');

					if (value['type_ques'] == 'text') {
						select.val('text');
						la = $('<label>Answer&nbsp;(put a list of words separated by a comma without spaces, eg: apple,pear,banana,...):&nbsp;</label><span class="error"></span>');
						ia = $('<input class="w3-input" type="text" name="q['+value['id']+'][answer]">').attr('value', value['answer'].toLowerCase());
						answer.append(la,ia);
					}
					else if (value['type_ques'] == 'number') {
						select.val('number');
						la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
						ia = $('<input class="w3-input" type="number" step="any" name="q['+value['id']+'][answer]"><br>').val(value['answer'].split("/")[0]);
						particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
						particule_input = $('<input type="text" class="w3-input" name="q['+value['id']+'][particule]">').val(value['answer'].split("/")[1]);
						answer.append(la,ia,particule_label,particule_input);
					}
					else if (value['type_ques'] == 'interval') {
						select.val('interval');
						la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
						min = $('<input class="w3-input" type="number" step="any" name="q['+value['id']+'][min]">').val(value['answer'].split("/")[0]);
						to = $('<label> to </label>');
						max = $('<input class="w3-input" type="number" step="any" name="q['+value['id']+'][max]">').val(value['answer'].split("/")[1]);
						answer.append(la,min,to,max);
					}
					else if (value['type_ques'] == 'radio') {
						select.val('radio');
						la = $('<label class="w3-col l12">Enter the number of possible choices :</label><span class="error"></span>');
						number = $('<input class="w3-input w3-col m8 w3-margin-right" type="number">');
						valid = $('<input type="button" class="w3-button w3-blue" value="create">');
						valid.click(function(){
							$(this).next().find('ol').empty();
							n = $(this).prev().val();
							for (var j = 0; j < n; j++) {
								var choice = $('<li id="'+j+'" class="w3-col m12"></li>');
								var text = $('<input class="w3-input w3-col m8" type="text" name="q['+value['id']+'][choices]['+j+'][choice]"><span class="error"></span>');
								var answer = $('<input class="w3-check" type="checkbox" name="q['+value['id']+'][choices]['+j+'][answer]"> <label>answer</label>');
								choice.append(text,answer);
								$(this).next().children('ol').append(choice);
							}
						});
						radios = $('<div class="radios w3-section"> <ol type="A"></ol> </div>');
						$.each( value['question'].split('(/=/)').slice(1, value['question'].split('(/=/)').length) , function(id,v){
							var choice = $('<li id="'+id+'" class="w3-col m12"></li>');
							var text = $('<input class="w3-input w3-col m8" type="text" name="q['+value['id']+'][choices]['+id+'][choice]" value="'+v+'"> <span class="error"></span>');
							var answer = $('<input class="w3-check" type="checkbox" name="q['+value['id']+'][choices]['+id+'][answer]"> <label>answer</label>');
							if (id == value['answer']) {
								answer.prop("checked", true);	
							}
							choice.append(text,answer);
							radios.find('ol').append(choice);
						})
						answer.append(la,number,valid,radios);
					}

					var button = $('<input type="button" class="w3-button w3-red w3-margin" style="width: 150px;" value="delete question">');
					button.click(function(){
						var r = confirm('Are you sure you want to delete this question ? It will be permanently deleted.');
						if (r == true) {
							var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"delete_question", id:value['id'] });
							$(this).parent().remove();
						}
					});
					var error = $('<span class="error"></span>');
					div.append(lq,iq,div_img,type_answer,answer,button,error,'<hr>');
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
		var div = $('<div id="'+i+'" class="w3-section"></div>').attr('class','question w3-padding');
		if(i%2 == 0){
			div.attr("style","background: #FFFFFF;");
		}
		else{
			div.attr("style","background: #f5f6fa;");
		}
		var lq = $('<label>Question&nbsp;:&nbsp;</label> <span class="error"></span>');
		var iq = $('<input class="w3-input" type="text" name="nq['+i+'][question]"><br>');

		var div_img = $('<div class="w3-row w3-margin">'
			+'<div class="w3-col m3 w3-center">'
			+'<label for="image_new_question_'+i+'">Select images to upload (PNG, JPG):</label>'
			+'<span class="error"></span>'
			+'</div>'
			+'<div class="w3-col m3 w3-center">'
			+'</div>'
			+'<div class="w3-row preview w3-center">'
			+'<p>No files selected</p>'
			+'</div>'
			+'</div>');

		var input_img = $('<input type="file" id="image_new_question_'+i+'" name="new_question['+i+']" accept=".jpg, .jpeg, .png" style="display: none;">');
		input_img[0].addEventListener('change', updateImageDisplay);
		div_img.find('.preview').prev().append(input_img);

		var type_answer = $('<div class="type_answer w3-section"></div>'); 
		var lt = $('<label>Type of question : </label> <span class="error"></span>');
		var select = $('<select class="w3-select" name="nq['+i+'][type_ques]">'
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
				ia = $('<input class="w3-input" type="text" name="nq['+id_parent+'][answer]"><br>');
				$(this).parent().next().append(la,ia);
			}
			else if ( $(this).find(':selected').text() == "NUMBER" ) {
				la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
				ia = $('<input class="w3-input" type="number" step="any" name="nq['+id_parent+'][answer]"><br>');
				particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
				particule_input = $('<input class="w3-input" type="text" name="nq['+id_parent+'][particule]">');
				$(this).parent().next().append(la,ia,particule_label,particule_input);
			}
			else if ( $(this).find(':selected').text() == "INTERVAL" ) {
				la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
				min = $('<input class="w3-input" type="number" step="any" name="nq['+id_parent+'][min]"><label> to </label>')
				max = $('<input class="w3-input" type="number" step="any" name="nq['+id_parent+'][max]">')
				$(this).parent().next().append(la,min,max);
			}
			else if ( $(this).find(':selected').text() == "RADIO" ) {
				la = $('<label class="w3-col l12">Enter the number of possible choices :</label><span class="error"></span>');
				number = $('<input class="w3-input w3-col m8 w3-margin-right" type="number">');
				valid = $('<input type="button" class="w3-button w3-blue" value="create">');
				valid.click(function(){
					$(this).next().find('ol').empty();
					n = $(this).prev().val();
					for (var j = 0; j < n; j++) {
						var choice = $('<li id="'+j+'" class="w3-col m12"></li>');
						var text = $('<input class="w3-input w3-col m8" type="text" name="nq['+id_parent+'][choices]['+j+'][choice]"><span class="error"></span>');
						var answer = $('<input class="w3-check" type="checkbox" name="nq['+id_parent+'][choices]['+j+'][answer]"> <label>answer</label>');
						choice.append(text,answer);
						$(this).next().children('ol').append(choice);
					}
				});
				radios = $('<div class="radios" class="w3-section"> <ol type="A"></ol> </div>');
				$(this).parent().next().append(la,number,valid,radios);
			}
		});
		type_answer.append(lt,select);
		if ( $('input[type="radio"]:checked').val() == "manuel" ) {
			type_answer.hide();
			console.log('test');
		}

		var answer = $('<div class="answer w3-section"></div>');

		var button = $('<input class="w3-button w3-red w3-margin" style="width: 150px;" type="button" value="delete question"><br>');
		button.click(function(){
			$(this).parent().remove();
		});
		var error = $('<span class="error"></span>');
		div.append(lq,iq,div_img,type_answer,answer,button,error,'<hr>');
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

		var input = document.querySelector('input[type=file]');
		$('input[type=file]').each(function(){
			var curFiles = $(this)[0].files;	
			if (curFiles.length !== 0) {
				for(var i = 0; i < curFiles.length; i++) {
					if (curFiles[i].size > 500000) {
						$(this).parent().prev().find('span.error').text(' Your file is too large');
						valid = false;
					}
				}
			}
		})

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