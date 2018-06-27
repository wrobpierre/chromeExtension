var adress = "http://163.172.59.102"

var fileTypes = ['image/jpeg','image/pjpeg','image/png'];
var fileName = "";


/**
* The function “updateImageDisplay” check if an image is already display in parent <div>, if there is one, 
* the function will delete the actual image and replace it by the new image. Else the function will add an image.
*
* @param : void
* @return : void
*/
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

/**
* The function “validFileType” check if the format of the file can be supported.
* 
* @param : string file - The extension of the file it can be : image/jpeg, image/pjpeg, image/png
* @return : bool - Check if the file type is valid
*/
function validFileType(file) {
	for(var i = 0; i < fileTypes.length; i++) {
		if(file.type === fileTypes[i]) {
			return true;
		}
	}
	return false;
}

/**
* The function “returnFileSize”  look at the size of an element (here an image) and return it size. 
*
* @param : string number - The size of the element in octet convert as a string
* @return : string - Return a string of the size convert with a extension (examples : 20 octets 20Ko, 20Mo)
*/
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
	input.addEventListener('change', updateImageDisplay);

	//Show inputs for the answers if radio is on auto, else hide them
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

		// Change the color of the background in white or grey
		if(i%2){
			div.css('background', '#f5f6fa');
		}
		else{
			div.css('background', '#FFFFFF');
		}

		// Create the HTML elements for a new question
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

		// User choose the type of answer he want 
		var type_answer = $('<div class="type_answer"></div>'); 
		var lt = $('<label>Type of question : </label> <span class="error"></span><br>');
		var select = $('<select class="select_type w3-select" name="q['+i+'][type_ques]">'
			+'<option></option>'
			+'<option value="text">TEXT</option>'
			+'<option value="number">NUMBER</option>'
			+'<option value="interval">INTERVAL</option>'
			+'<option value="radio">RADIO</option>'
			+'</select>');

		// Generate the HTML elements depending of the value of the answer
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

		// If response manuel hide the response's HTML elements
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

// Check if the title of the questionnaire don't already exist in the dataBase (two title can't be the same)
$('input[name="title"]').change(function(){
	var title = $('input[name="title"]').val();
	var post = $.post(adress+'/webSite/questionnaires/src/management_questionnaire.php', { action:'check_title', title:title });
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

// All the error message for the generation of the questionnaire
$('form').submit(function(){
	$('span.error').text('');
	valid = true;

	// Empty title or title already exist in database
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

	// Image too big 
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

	// Radio correction empty
	if ( $('input[type="radio"]:checked').length == 0 ) {
		$('input[type="radio"][value="auto"]').prev().prev().text('Select the type of correction')
		valid = false;
	}

	// No question in the questionnaire
	if( $('.all_questions')[0].children.length == 0 ) {
		$('input[value="+"]').next().text('Please add at least one question');
		valid = false;
	}
	else {
		// Input question empty
		$('div.question').each(function(index, value){
			id = $(this).attr('id');
			if ($(this).find('input[name="q['+id+'][question]"]').val() == "") {
				$(this).find('input[name="q['+id+'][question]"]').prev().text('Missing question');
				valid = false;
			}

			// Question type empty
			if ( $('input[type="radio"]:checked').val() == "auto" ) {
				if ($(this).find('select').find(':selected').text() == "") {
					$(this).find('select[name="q['+id+'][type_ques]"]').prev().prev().text('Select the type of the question');
					valid = false;
				}
				else {

					// Answer empty
					if ($(this).find('select').find(':selected').text() == "TEXT" || $(this).find('select').find(':selected').text() == "NUMBER") {
						if ($(this).find('input[name="q['+id+'][answer]"]').val() == "") {
							$(this).find('input[name="q['+id+'][answer]"]').prev().text('Missing answer');
							valid = false;
						}
					}
					else if ($(this).find('select').find(':selected').text() == "INTERVAL") {
						// Value for interval empty
						if ($(this).find('input[name="q['+id+'][min]"]').val() == "" || $(this).find('input[name="q['+id+'][max]"]').val() == "") {
							$(this).find('input[name="q['+id+'][min]"]').prev().text('Missing min or max');
							valid = false;
						}	
					}
					else if ($(this).find('select').find(':selected').text() == "RADIO") {
						//Value for radio empty
						if ( $(this).find('.answer > .radios > ol > li').length == 0 ) {
							$(this).find('.answer > span.error').text('Please add at least one choice');
							valid = false;
						}
						else {
							// No good response for radio type
							if( $(this).find('.answer > .radios > ol > li').find(':checked').length == 0) {
								$(this).find('.answer > span.error').text('You need to choose the right choice');
								valid = false;
							}
							else if ($(this).find('.answer > .radios > ol > li').find(':checked').length > 1 ) {
								// More than one radio can't be the right answer
								$(this).find('.answer > span.error').text('There can only be one right choice');
								valid = false;
							}	

							$(this).find('.answer > .radios > ol > li').each(function(index,value){
								// One choice for radio is empty
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
})
});