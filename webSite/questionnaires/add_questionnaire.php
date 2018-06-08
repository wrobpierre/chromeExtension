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
	<meta charset="utf-8">
	<title>Add a questionnaire</title>
	<?php include '../layout/includes.php'; ?>
</head>
<body>
	<?php include '../layout/header.php'; ?>
	<body>
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
								<textarea form="form" name="statement" style="width: 100%;"></textarea>							
							</div>

						</div>
						<div class="w3-row w3-margin">
							<div class="w3-section">
								<label>Choose if you want correct questionnaires by yourself&nbsp;:</label>
								<span class="error"></span><br>
								<div>
									<input type="radio" name="auto_correction" value="auto" checked="checked" class="w3-radio"><label>Automatic</label><br>
									<input type="radio" name="auto_correction" value="manuel" class="w3-radio"><label>Manuel</label>
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
								<input class="w3-input w3-blue" style="width: 150px;" type="button" value="Add a question">
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
		<script type="text/javascript">
			var adress = "http://163.172.59.102"
		//var adress = "http://localhost/chromeExtension"

		var checkUser = '<?php echo $checkUser ;?>';

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

		$(document).ready(function(){
			var i = 0

			var input = document.querySelector('#image_upload');
			//var preview = document.querySelector('.preview');

			//input.style.opacity = 0;
			input.addEventListener('change', updateImageDisplay);

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

			$('input[value="Add a question"]').click(function(){
				var div = $('<div id="'+i+'"></div>').attr('class','question w3-section w3-col m12 w3-padding');
				if(i%2){
						div.css('background', '#f5f6fa');
					}
					else{
						div.css('background', '#FFFFFF');
					}
				var lq = $('<label>Question&nbsp;:&nbsp;</label> <span class="error"></span>');
				var iq = $('<input class="input_question w3-input" type="text" name="q['+i+'][question]"><br>');

				var div_img = $('<div class="w3-row w3-margin">'
					+'<div class="w3-col m3 w3-center">'
					+'<label for="image_question_'+i+'">Select images to upload (PNG, JPG):</label>'
					+'<span class="error"></span>'
					+'</div>'
					+'<div class="w3-col m3 w3-center">'
					+'</div>'
					+'<div class="w3-row preview w3-center">'
					+'<p>No files selected</p>'
					+'</div>'
					+'</div>');
				
				var input_img = $('<input type="file" id="image_question_'+i+'" name="question['+i+']" accept=".jpg, .jpeg, .png" style="display: none;">');
				input_img[0].addEventListener('change', updateImageDisplay);
				div_img.find('.preview').prev().append(input_img);

				var type_answer = $('<div class="type_answer"></div>'); 
				var lt = $('<label>Type of question : </label> <span class="error"></span><br>');
				var select = $('<select class="select_type w3-select" name="q['+i+'][type_ques]">'
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
						ia = $('<input class="input_question w3-input" type="text" name="q['+id_parent+'][answer]"><br>');
						$(this).parent().next().append(la,ia);
					}
					else if ( $(this).find(':selected').text() == "NUMBER" ) {
						la = $('<label>Answer&nbsp;(put a number):&nbsp;</label><span class="error"></span>');
						ia = $('<input class="input_question w3-input" type="number" step="any" name="q['+id_parent+'][answer]"><br>');
						particule_label = $('<label>Particule (eg:3 million instead of 3 000 000):&nbsp;</label>');
						particule_input = $('<input class="w3-input" type="text" name="q['+id_parent+'][particule]">');
						$(this).parent().next().append(la,ia,particule_label,particule_input);
					}
					else if ( $(this).find(':selected').text() == "INTERVAL" ) {
						la = $('<label>Answer&nbsp;(put the minimum in the first area and the max in the other. The values are include):&nbsp;</label><span class="error"></span>');
						min = $('<input class="input_question w3-input" type="number" step="any" name="q['+id_parent+'][min]"><label> to </label>')
						max = $('<input class="input_question w3-input" type="number" step="any" name="q['+id_parent+'][max]">')
						$(this).parent().next().append(la,min,max);
					}
					else if ( $(this).find(':selected').text() == "RADIO" ) {
						la = $('<label>Enter the number of possible choices :</label><span class="error"></span><div class="w3-row"></div>');
						number = $('<input class="w3-input w3-col m8 w3-margin-right" type="number">');
						valid = $('<input class="w3-btn w3-red w3-round w3-large" type="button" value="create">');
						valid.click(function(){
							$(this).next().find('ol').empty();
							n = $(this).prev().val();
							for (var j = 0; j < n; j++) {
								var choice = $('<li id="'+j+' class="w3-col m12"></li>');
								var text = $('<input class="w3-input w3-col m8" type="text" name="q['+id_parent+'][choices]['+j+'][choice]"><span class="error"></span>');
								var answer = $('<input class="w3-radio" type="checkbox" name="q['+id_parent+'][choices]['+j+'][answer]"> <label>answer</label><div class="w3-row"></div>');
								choice.append(text,answer);
								$(this).next().children('ol').append(choice);
							}
						});
						radios = $('<div class="radios w3-col m12"> <ol type="A"></ol> </div>');
						$(this).parent().next().append(la,number,valid,radios);
					}
				});
				type_answer.append(lt,select);
				if ( $('input[type="radio"]:checked').val() == "manuel" ) {
					type_answer.hide();
				}

				var answer = $('<div class="answer"></div>');

				var button = $('<div class="w3-row"><input class="w3-input w3-red w3-section" type="button" value="Delete" style="width: 150px;"></div>');
				button.click(function(){
					$(this).parent().remove();
				});
				var error = $('<span class="error"></span>');
				div.append(lq,iq,div_img,type_answer,answer,button,error,'<hr>');
				$('.all_questions').append(div);
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

				if( $('.all_questions')[0].children.length == 0 ) {
					$('input[value="+"]').next().text('Please add at least one question');
					valid = false;
				}
				else {
					$('div.question').each(function(index, value){
						id = $(this).attr('id');
						if ($(this).find('input[name="q['+id+'][question]"]').val() == "") {
							$(this).find('input[name="q['+id+'][question]"]').prev().text('Missing question');
							valid = false;
						}
						if ( $('input[type="radio"]:checked').val() == "auto" ) {
							if ($(this).find('select').find(':selected').text() == "") {
								$(this).find('select[name="q['+id+'][type_ques]"]').prev().prev().text('Select the type of the question');
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
				}
				//alert(valid);
				return valid;
			})
});
</script>
<?php include '../layout/footer.php'; ?>
<script src="../js/parallax.js-1.5.0/parallax.js" type="text/javascript"></script>
</body>
</html>