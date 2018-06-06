<?php
session_start();
if(isset($_SESSION['user'])){
	$checkUser = $_SESSION['user'];
}
else{
	$checkUser = null;
}

function getOS() { 
	$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
	$os_platform    =   "Unknown OS Platform";
	$os_array       =   array(
		'/windows nt/i'     =>  'Windows',
		'/macintosh|mac os|mac_powerpc/i' =>  'Mac',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
	);

	foreach ($os_array as $regex => $value) { 

		if (preg_match($regex, $user_agent)) {
			$os_platform    =   $value;
		}
	}   
	return $os_platform;
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- <link rel="stylesheet" type="text/css" href="../css/questionnaire.css"> -->
	<meta charset="utf-8">
	<title></title>
	<?php include '../layout/includes.php'; ?>
</head>
<body>
	<?php include '../layout/header.php'; ?>
	<!-- <header class="parallax-window" data-parallax="scroll" data-image-src="../img/select_question.jpg"></header> -->
	<header class="w3-container w3-red w3-center" style="padding:128px 16px;">
		<h1></h1>
	</header>
	<div id="form-questionnaire" class="w3-light-grey w3-container">
		<div class="w3-col m3 w3-margin"></div>
		<div id="questionnaire" class="w3-col m6 w3-border w3-white w3-margin">
			<div id="content" >
				<div >
					<div class="w3-container w3-red">
						<h1></h1>
					</div>
						<div id="addQuestionnaire"></div>
					
				</div>


			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">

		// The ID of the extension we want to talk to.
		var editorExtensionId = null;
		var os = '<?php echo getOS(); ?>';
		console.log(os);
		if (os == 'Windows') {
			editorExtensionId = "ocknihfeinjoffpckboadjhgenhojpgk";			
		}
		else if (os == 'Mac') {
			editorExtensionId = "fkjmjhcdmdgenkkabahncdhapanknlcl";
		}
		else if (os == 'Linux') {
			editorExtensionId = "";
		}
		else if (os == 'Ubuntu') {
			editorExtensionId = "";
		}
		console.log(editorExtensionId)
		
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
					$('h1').text('The questionnaires');
					var ul = $('<ul></ul>');
					ul.css("list-style", "none")
					ul.css("padding", "0")
					ul.css("margin", "0")
					$.each(dataParse, function(index, value){
						if (index%2 == 0){
							var li = $('<li class="list_question w3-col m12 w3-white"></li>');
							
						}
						else{
							var li = $('<li class="list_question w3-col m12" style="background: #f5f6fa;"></li>');
						}
						var titleDiv = $('<div class="w3-row "></div>')
						var title = $('<h2 class="w3-col s12 w3-col m8 "></h2>').text(value['title']);
						
						var info = $('<div></div>');
						if(value['link_img'] != null){
							var img = $('<img src="'+value['link_img']+'" class="w3-round w3-col s12 w3-col m3">');
						}
						else{
							var img = $('<img src="../img/question-mark.png" class="w3-rounded w3-col s12 w3-col m3">');
						}
						var statement = $('<p class="">'+value['statement']+'</p>')

						var always_visible = $('<div></div>');
						var space = '<div class="w3-col l1 w3-col m1 w3-white"></div>';

						var do_ques = $('<input type="button" class="w3-btn w3-ripple w3-blue  w3-col m12 w3-col l5" onclick="window:location.href=\''+value['url']+'\'"></input>').attr('value', 'Answer the questionnaire');

						var graph_ques = $('<input type="button" class="w3-btn w3-ripple w3-blue  w3-col m12 w3-col l5" onclick="window:location.href=\''+adress+'/webSite/graph.php?id='+value['url'].split('=')[1]+'\'"></input>').attr('value', 'Graph');
						
						if(checkUser != ""){
							var option = $('<div class="option w3-col m12"></div>')
							var edit = $('<input type="button" class="w3-btn w3-ripple w3-blue w3-col l3 w3-col m12 " onclick="window:location.href=\''+adress+'/webSite/questionnaires/edit_questionnaire.php?id='+value['url'].split('=')[1]+'\'"></input>').attr('value', 'Edit');
							var del = $('<button class="w3-btn w3-ripple w3-red w3-col l3 w3-col s12 ">Delete</button>').click(function(){
								var r = confirm("Are you sure to delete this questionnaire ?");
								if (r == true) {
									var postDel = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"delete", url:value['url'] });
									postDel.done(function(data){
										alert(data);
										location.reload();
									});
								}
							});
							var edit_result = $('<input type="button" class="w3-btn w3-ripple w3-indigo w3-col l3 w3-col m12  " style="white-space: normal;"  onclick="window:location.href=\''+adress+'/webSite/questionnaires/edit_results.html?id='+value['url'].split('=')[1]+'\'"></input>').attr('value','Edit users\' results');

						}
						titleDiv.append(img, title);
						info.append(statement);


						always_visible.append(do_ques,graph_ques);
						if(checkUser != ""){

							if (value['auto_correction'] == 0) {
								option.append(space, edit, space, del, space, edit_result);
							}
							else {
								option.append(space, edit, space,del);	
							}

						}

						li.append(titleDiv,info,always_visible,option);
						ul.append(li);
						$('#content').append(ul);
					})
					if (checkUser != "") {
						var add = $('<div class="w3-col l1 w3-col m1 w3-white"></div><input type="button" class="w3-btn w3-ripple w3-green w3-section w3-col l10 w3-col m10 w3-center" onclick="window:location.href=\''+adress+'/webSite/questionnaires/add_questionnaire.php\'"></input>').attr('value', 'New questionnaire');
						add.css("background-color", "#00B16A");
						add.css("height", "40px");
						$('#addQuestionnaire').append(add);
					}
				}
			});
		}
		else {
			if (checkUser == "") {
				document.location.href="../connexion/connect.php"
			}
			//console.log(param);
			var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"get_questions", id:param });

			post.done(function(data){
				var dataParse = JSON.parse(data);
				console.log(dataParse);
				document.title = dataParse[0]['title'];
				$('h1').text(dataParse[0]['title']);
				rules = $('<p></p>').text('If you are not sure of the answer, put that you don\'t know or don\'t answer')
				statement = $('<p></p>').text(dataParse[0]['statement']);
				$('#content').append(rules,statement);

				var form = $('<form method="post" action="checkAnswers.php"></form>');
				var user_email = $('<input type="hidden" name="user_email" value="'+checkUser+'">');
				form.append(user_email);

				var start = $('<div></div>');
				var valid = $('<button class="w3-btn w3-ripple w3-blue ">Start questionnaire</button>');
				valid.click(function(){
					var that = $(this);
					var already_done = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"already_done", user:checkUser, id_questionnaire:dataParse[0]['id_questionnaire'] })

					already_done.done(function(data){
						console.log(data);
						if (data != 0) {
							var r = confirm("You have already answered this questionnaire, if you continue all your previous data will be deleted");
							if (r == true) {
								var reset_user_research = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"reset_user_research", user:checkUser, id_questionnaire:dataParse[0]['id_questionnaire'] });
								reset_user_research.done(function(data){
									console.log(data);
									that.parent().css('display','none');
									that.parent().next().css('display','block');

									if (dataParse[0]['link_img'] != "") {
										link = $('<img>').attr('src', dataParse[0]['link_img']).attr('alt', dataParse[0]['link_img']);
										link.insertAfter('#content > h1 + p');
									}

									var url = window.location.href; 
									chrome.runtime.sendMessage(editorExtensionId, {action: 'start', url: url},
										function(response) {
											console.log(response);
										});
								});
							}
						}
						else {
							that.parent().css('display','none');
							that.parent().next().css('display','block');

							if (dataParse[0]['link_img'] != "") {
								link = $('<img>').attr('src', dataParse[0]['link_img']).attr('alt', dataParse[0]['link_img']);
								link.insertAfter('#content > h1 + p');
							}

							var url = window.location.href; 
							chrome.runtime.sendMessage(editorExtensionId, {action: 'start', url: url},
								function(response) {
									console.log(response);
								});
						}
					})
				});

				start.append(valid);
				form.append(start);

				for (var i = 0; i < dataParse.length; i++) {
					var div = $('<div class="question" id="'+(i+1)+'"></div>');
					if (dataParse.length != 1) {
						var number = $('<p>'+(i+1)+'/'+dataParse.length+'</p>');
						div.append(number);
					}
					
					if (dataParse[i]['image'] != '') {
						var img = $('<img>').attr('src', dataParse[i]['image']).attr('alt', dataParse[i]['image']);
						div.append(img);
					}

					var p = $('<p>'+dataParse[i]['question'].split('(/=/)')[0]+'</p>');
					div.append(p);
					
					if (dataParse[i]['type_ques'] == 'text') {
						var input = $('<input class="w3-input" type="text" name="q['+dataParse[i]['id']+'][answer]">');
						var valid = $('<div class="w3-center"><input class="w3-btn w3-ripple w3-blue w3-col l3 w3-col s12 " type="button" value="valid"></div>');
						div.append(input,valid);
					}
					else if (dataParse[i]['type_ques'] == 'number') {
						var input = $('<input class="w3-input" step="any" type="number" name="q['+dataParse[i]['id']+'][answer]">');
						if (dataParse[i]['particule'] != '') {
							var particule = $('<label>'+dataParse[i]['particule']+'</label>')
						}
						var valid = $('<div class="w3-center"> <input class="w3-btn w3-ripple w3-blue w3-col l3 w3-col s12  w3-center" type="button" value="valid"> </div>');
						div.append(input,particule,valid);
					}
					else if (dataParse[i]['type_ques'] == 'interval') {
						var input = $('<input class="w3-input" step="any" type="number" name="q['+dataParse[i]['id']+'][answer]">');
						var valid = $('<div class="w3-center"><input class="w3-btn w3-ripple w3-blue w3-col l3 w3-col s12 " type="button" value="valid"></div>');
						div.append(input,valid);
					}
					else if (dataParse[i]['type_ques'] == 'radio') {
						var ol = $('<ol type="A"></ol>');
						var choices = dataParse[i]['question'].split('(/=/)');
						for (var j = 1; j < choices.length; j++) {
							var input = $('<li> <input type="radio" class="w3-radio" name="q['+dataParse[i]['id']+'][answer]" value="'+(j-1)+'"> <label>'+choices[j]+'</label> </li>');
							ol.append(input);
						}
						var valid = $('<div class="w3-center"> <input class="w3-btn w3-ripple w3-blue w3-col l3 w3-col s12 " type="button" value="valid"> </div>');
						div.append(ol,valid);
					}
					else if (dataParse[i]['type_ques'] == 'free') {
						var input = $('<input class="w3-input" type="text" name="q['+dataParse[i]['id']+'][answer]">');
						var valid = $('<div class="w3-center"> <input class="w3-btn w3-ripple w3-blue w3-col l3 w3-col s12" type="button" value="valid"> </div>');
						div.append(input,valid);
					}

					div.css("margin", "5%");
					form.append(div);
				}


				var lastDiv = $('<div></div>')
				var p = $('<p>Did you have any difficulty answering these questions?</p>');
				var user_opinion = $('<div></div>');
				for (var i = 0; i < dataParse.length; i++) {
					var question = $('<p>'+dataParse[i]['question'].split('(/=/)')[0]+'</p>');
					var rank = $('<select class="w3-select" name="q['+dataParse[i]['id']+'][rank]">'
						+'<option value=""></option>'
						+'<option value="easy">EASY</option>'
						+'<option value="medium">MEDIUM</option>'
						+'<option value="hard">HARD</option>'
						+'</select>');

					user_opinion.append(question,rank);
				}
				var label_knowledge = $('<p>What is your level of knowledge on the subject of this questionnaire?</p>');
				var knowledge = $('<select class="w3-select" name="knowledge">'
					+'<option value=""></option>'
					+'<option value="novice">NOVICE</option>'
					+'<option value="medium">MEDIUM</option>'
					+'<option value="expert">EXPERT</option>'
					+'</select>');
				user_opinion.append(label_knowledge,knowledge);

				var submit = $('<input class="w3-btn w3-ripple w3-blue w3-col l3 w3-col s12 " type="submit" value="send">');
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
					$('form div:last-child').css('display','none');

					$('div.question > input[type="button"]').click(function(){
						$(this).parent().parent().css('display','none');
						$(this).parent().parent().next().css('display','block');
					})

					$('div.question > div.w3-center > input[type="button"][value="valid"]').click(function(){
						chrome.runtime.sendMessage(editorExtensionId, {action: 'change_question'},
							function(response) {
								//alert(response.result);
							});
					})

					$('form').submit(function(){
						valid = true;

						$('select').each(function(){
							if( $(this).find(':selected').val() == "") {
								valid = false;
							}
						})

						if (valid) {
							chrome.runtime.sendMessage(editorExtensionId, {action: 'stop', email:checkUser},
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
</body>
</html>