<?php
session_start();
if(isset($_SESSION['user'])){
	$checkUser = $_SESSION['user'];
}
else{
	$checkUser = null;
}

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/questionnaire.css">
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<header class="parallax-window" data-parallax="scroll" data-image-src="../img/select_question.jpg"></header>
	<div id="form-questionnaire">
		<div id="questionnaire">
			<div id="content">
				
				<h1></h1>


			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">

		// The ID of the extension we want to talk to.
		var editorExtensionId = "hndmaeoonglghdojdmbieedjgkgmmhck";
		/*var test = 'https://developer.chrome.com/extensions/messaging';

		chrome.runtime.sendMessage(editorExtensionId, {openUrlInEditor: test},
			function(response) {
				console.log(response);
			});*/

		var adress = "http://163.172.59.102"
		//var adress = "http://localhost/chromeExtension"

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
		var checkUser = '<?php echo $checkUser ;?>';
		console.log(checkUser);
		var param = getUrlParameter('id');

		if (param == undefined) {
			var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"all" });

			post.done(function(data){
				if (data != '') {
					var dataParse = JSON.parse(data);
					console.log(dataParse);
					document.title = 'Questionnaires';
					$('h1').text('List of questionnaires');
					var ul = $('<ul></ul>');
					ul.css("list-style", "none")
					$.each(dataParse, function(index, value){
						var li = $('<li class="list_question"></li>');
						var title = $('<h2></h2>').text(value['title']);
						
						var info = $('<div></div>');
						var img = $('<img src="'+value['link_img']+'">').css('width', '50px');
						var statement = $('<p>'+value['statement']+'</p>')

						var always_visible = $('<div></div>');

						var do_ques = $('<input type="button" class="button_link" onclick="window:location.href=\''+value['url']+'\'"></input>').attr('value', 'Answer the questionnaire');

						var graph_ques = $('<input type="button" class="button_link" onclick="window:location.href=\''+adress+'/webSite/graph.html?id='+value['url'].split('=')[1]+'\'"></input>').attr('value', 'Graph');
						
						if(checkUser != ""){
						var option = $('<div class="option"></div>')
							var edit = $('<input type="button" class="button_link" onclick="window:location.href=\''+adress+'/webSite/questionnaires/edit_questionnaire.php?id='+value['url'].split('=')[1]+'\'"></input>').attr('value', 'Edit');
							var del = $('<button class="button_delete">Delete</button>').click(function(){
								var r = confirm("Are you sure to delete this questionnaire ?");
								if (r == true) {
									var postDel = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"delete", url:value['url'] });
									postDel.done(function(data){
										alert(data);
										location.reload();
									});
								}
							});
							var edit_result = $('<input type="button" class="button_link" onclick="window:location.href=\''+adress+'/webSite/questionnaires/edit_results.html?id='+value['url'].split('=')[1]+'\'"></input>').attr('value','Edit users\' results');

						}
						if (value['link_img'] != null) {
							info.append(img,statement);
						}
						else {
							info.append(statement);
						}

						always_visible.append(do_ques,graph_ques);
						if(checkUser != ""){

							if (value['auto_correction'] == 0) {
								option.append(edit,del,edit_result);
							}
							else {
								option.append(edit,del);	
							}

						}


						li.append(title,info,always_visible,option);
						ul.append(li);
					})

					var add = $('<input type="button" class="button_link" onclick="window:location.href=\''+adress+'/webSite/questionnaires/add_questionnaire.php\'"></input>').attr('value', 'Add a questionnaire');
					add.css("background-color", "#00B16A");
					add.css("height", "40px");

					$('#content').append(ul,add);
				}
			});
		}
		else {
			if (checkUser == "") {
				document.location.href="../connexion/connexion.html"
			}
			//console.log(param);
			var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"get_questions", id:param });

			post.done(function(data){
				var dataParse = JSON.parse(data);
				console.log(dataParse);
				document.title = dataParse[0]['title'];
				$('h1').text(dataParse[0]['title']);
				statement = $('<p></p>').text(dataParse[0]['statement']);
				$('#content').append(statement);

				if (dataParse[0]['link_img'] != "") {
					link = $('<img>').attr('src', dataParse[0]['link_img']).attr('alt', dataParse[0]['link']);
					$('#content').append(link);
				}
				var form = $('<form method="post" action="checkAnswers.php"></form>');
				var user_id = $('<input type="hidden" name="user_id">');
				form.append(user_id);	

				var dataUser = $('<div id="dataUser"></div>');
				var method = $('<input type="hidden" name="method">');

				var button_sign_up = $('<button class="sign_up button">Sign up</button>');
				button_sign_up.prop('disabled',true);
				button_sign_up.click(function(){
					$('div.sign_up').css('display', 'block');
					$('div.sign_in').css('display', 'none');
					$(this).prop('disabled',true);
					$('button.sign_in').prop("disabled",false);
				});

				var button_sign_in = $('<button class="sign_in button">Sign in</button>');
				button_sign_in.click(function(){
					$('div.sign_in').css('display', 'block');
					$('div.sign_up').css('display', 'none');
					$(this).prop('disabled',true);
					$('button.sign_up').prop('disabled',false);
				});

				var sign_up = $('<div class="sign_up"></div>');
				var name = $('<div> <label>Your name :</label> <input class="input_question" type="text" name="name" style="margin:0"> <span class="error"></span> </div>');
				var job = $('<div> <label>Your job/domain :</label> <input class="input_question" type="text" name="job" style="margin:0"> <span class="error"></span> </div>');
				var login = $('<div> <label>Your email :</label> <input class="input_question" type="email" name="login" style="margin:0"> <span class="error"></span> </div>');
				var valid = $('<input class="button" type="button" value="valid">');
				valid.click(function(){
					$('span.error').text('');
					valid = true;

					if ($('input[name="name"]').val() == "") {
						valid = false;
						$('input[name="name"] + span.error').text('missing name');
					}

					if ($('input[name="job"]').val() == "") {
						valid = false;
						$('input[name="job"] + span.error').text('missing job');
					}

					if ($('input[name="login"]').val() == "") {
						valid = false;
						$('input[name="login"] + span.error').text('missing email');
					}
					else {
						var email = $('input[name="login"]').val();
						var post = $.post(adress+'/dataBase.php', { key:'check_user_email', email:email });
						post.done(function(data){
							if (data != 0) {
								valid = false;
								$('input[name="login"] + span.error').text('Already exist');
							} 
							else {
								$('input[name="login"] + span.error').text('');	
								if (valid) {
									$('input[name="method"]').val('sign_up');
									$('div.sign_up > input.button').parent().parent().css('display','none');
									$('div.sign_up > input.button').parent().parent().next().css('display','block');
								}
							}
						});
					}
				});
				sign_up.append(name,job,login,valid);

				var sign_in = $('<div class="sign_in"></div>');
				var register = $('<div> <label>Your email :</label> <input class="input_question" type="email" name="register" style="margin:0"> <span class="error"></span> </div>');
				var valid = $('<input class="button" type="button" value="valid">');
				valid.click(function(){
					if ($('input[name="register"]').val() == "") {
						$('input[name="register"] + span.error').text('missing email');
					}
					else {
						var email = $('input[name="register"]').val();
						var post = $.post(adress+'/dataBase.php', { key:'check_user_email', email:email, method:'sign_in' });
						post.done(function(data){
							var dp = JSON.parse(data);
							if (dp.length == 0) {
								$('input[name="register"] + span.error').text('Doesn\'t exist');
							} 
							else {
								$('input[name="register"] + span.error').text('');
								$('input[name="user_id"]').val(dp[0]['check_id']);

								$('input[name="method"]').val('sign_in');
								$('div.sign_in > input.button').parent().parent().css('display','none');
								$('div.sign_in > input.button').parent().parent().next().css('display','block');
							}
						});
					}
				});
				sign_in.css('display', 'none');
				sign_in.append(register,valid);

				var info = $('<p>*if you wish to have your personal data deleted, contact us at this email address: stageosakadawin@gmail.com</p>')

				dataUser.append(method,button_sign_up,button_sign_in,sign_up,sign_in,info);
				form.append(dataUser);

				for (var i = 0; i < dataParse.length; i++) {
					var div = $('<div class="question" id="'+(i+1)+'"></div>');
					var number = $('<p>'+(i+1)+'/'+dataParse.length+'</p>');
					var p = $('<p>'+dataParse[i]['question'].split('(/=/)')[0]+'</p>');
					if (dataParse[i]['type_ques'] == 'text') {
						var input = $('<input class="input_question" type="text" name="q['+dataParse[i]['id']+'][answer]">');
						var valid = $('<input class="button" type="button" value="valid">');
						div.append(number,p,input,valid);
					}
					else if (dataParse[i]['type_ques'] == 'number') {
						var input = $('<input class="input_question" step="any" type="number" name="q['+dataParse[i]['id']+'][answer]">');
						if (dataParse[i]['particule'] != '') {
							var particule = $('<label>'+dataParse[i]['particule']+'</label>')
						}
						var valid = $('<input class="button" type="button" value="valid">');
						div.append(number,p,input,particule,valid);
					}
					else if (dataParse[i]['type_ques'] == 'interval') {
						var input = $('<input class="input_question" step="any" type="number" name="q['+dataParse[i]['id']+'][answer]">');
						var valid = $('<input class="button" type="button" value="valid">');
						div.append(number,p,input,valid);
					}
					else if (dataParse[i]['type_ques'] == 'radio') {
						var ol = $('<ol type="A"></ol>');
						var choices = dataParse[i]['question'].split('(/=/)');
						for (var j = 1; j < choices.length; j++) {
							var input = $('<li> <input type="radio" name="q['+dataParse[i]['id']+'][answer]" value="'+(j-1)+'"> <label>'+choices[j]+'</label> </li>');
							ol.append(input);
						}
						var valid = $('<input class="button" type="button" value="valid">');
						div.append(number,p,ol,valid);
					}
					else if (dataParse[i]['type_ques'] == 'free') {
						var input = $('<input class="input_question" type="text" name="q['+dataParse[i]['id']+'][answer]">');
						var valid = $('<input class="button" type="button" value="valid">');
						div.append(number,p,input,valid);
					}

					div.css("margin", "5%");
					form.append(div);
				}


				var lastDiv = $('<div></div>')
				var p = $('<p>Did you have any difficulty answering these questions?</p>');
				var user_opinion = $('<div></div>');
				for (var i = 0; i < dataParse.length; i++) {
					var question = $('<p>'+dataParse[i]['question'].split('(/=/)')[0]+'</p>');
					var rank = $('<select name="q['+dataParse[i]['id']+'][rank]">'
						+'<option value=""></option>'
						+'<option value="easy">EASY</option>'
						+'<option value="medium">MEDIUM</option>'
						+'<option value="hard">HARD</option>'
						+'</select>');

					user_opinion.append(question,rank);
				}
				var submit = $('<input class="button_valid" type="submit" value="send">');
				lastDiv.css("margin", "5%")

				lastDiv.append(p,user_opinion,submit);
				form.append(lastDiv);
				$('#form-questionnaire').css("width","100%");
				$('#content').css("text-align", "center")
				form.css("margin-top","0px");
				//$('#content').append(link);
				$('#content').append(form);

				$(document).ready(function(){

					$('div.question').css('display','none');
					//$('div#dataUser').css('display','none');
					$('form div:last-child').css('display','none');

					$('div.question > input[type="button"]').click(function(){
						$(this).parent().css('display','none');
						$(this).parent().next().css('display','block');
					})

					$('form').submit(function(){
						valid = true;

						$('select').each(function(){
							if( $(this).find(':selected').val() == "") {
								valid = false;
							}
						})

						if (valid) {
							chrome.runtime.sendMessage(editorExtensionId, {action: 'stop'},
								function(response) {
									console.log(response);
								});
						}

						return valid;
					});
				});
			});
}
</script>
<script src="../js/parallax.js-1.5.0/parallax.js"></script>
</body>
</html>