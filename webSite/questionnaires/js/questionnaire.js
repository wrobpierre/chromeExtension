// The ID of the extension we want to talk to.
var editorExtensionId = "bfiomnfjhlbbmkncooahhdilkncgchmo";			

var adress = "http://163.172.59.102"


var checkUser = '<?php echo $checkUser ;?>';
var checkIdUser = '<?php echo $checkIdUser ;?>';
var param = document.URL.split('-')[1];
var formHasChanged = false;
var submitted = false;

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
					var li = $('<li class="list_question w3-col m12 w3-white w3-padding"></li>');
				}
				else{
					var li = $('<li class="list_question w3-col m12 w3-padding" style="background: #f5f6fa;"></li>');
				}
				var titleDiv = $('<div class="w3-row w3-center"></div>')
				var title = $('<h2 class=" w3-col m8 "></h2>').text(value['title']);

				var info = $('<div></div>');
				if(value['link_img'] != null){
					var img = $('<img src="'+value['link_img']+'" class="w3-round w3-col l3" style="max-width: 250px;">');
				}
				else{
					var img = $('<img src="../img/question-mark.png" class="w3-round w3-col l3" style="max-width: 300px;">');
				}
				var statement = $('<p class="w3-padding">'+value['statement']+'</p>')
				var always_visible = $('<div></div>');
				var do_ques = $('<input type="button" class="w3-button w3-blue w3-col l6 w3-section" style="white-space: normal; border: solid white;" onclick="window:location.href=\''+value['url']+'\'"></input>').attr('value', 'Answer the questionnaire');
				var graph_ques = $('<input type="button" class="w3-button w3-blue w3-col l6 w3-section" style="white-space: normal; border: solid white;" onclick="window:location.href=\''+adress+'/webSite/graph-'+value['url'].split('-')[1]+'\'"></input>').attr('value', 'Graph');

				if(checkUser != ""){
					var option = $('<div class="option w3-col m12"></div>')
					var edit = $('<input type="button" class="w3-button w3-blue w3-col l4 w3-section" style="white-space: normal; border: solid white;"onclick="window:location.href=\''+adress+'/webSite/questionnaires/edit_questionnaire-'+value['url'].split('-')[1]+'\'"></input>').attr('value', 'Edit');
					var del = $('<button class="w3-button w3-red w3-col l4 w3-section" style="white-space: normal; border: solid white;">Delete</button>').click(function(){
						var r = confirm("Are you sure to delete this questionnaire ?");
						if (r == true) {
							var postDel = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"delete", url:value['url'] });
							postDel.done(function(data){
								alert(data);
								location.reload();
							});
						}
					});
					var edit_result = $('<input type="button" class="w3-button w3-indigo w3-col l4 w3-section" style="white-space: normal; border: solid white;" style="white-space: normal;"  onclick="window:location.href=\''+adress+'/webSite/questionnaires/edit_results-'+value['url'].split('-')[1]+'\'"></input>').attr('value','Edit users\' results');

				}
				titleDiv.append(img, title);
				info.append(statement);


				always_visible.append(  do_ques,   graph_ques);
				if(checkUser != "" && checkIdUser == value['key_user']){

					if (value['auto_correction'] == 0) {
						option.append( edit, del, edit_result);
					}
					else {
						option.append(edit, del);	
					}

				}

				li.append(titleDiv,info,always_visible,option);
				ul.append(li);
				$('#content').append(ul);
			})
			if (checkUser != "") {
				var add = $('<input type="button" class="w3-button w3-green" onclick="window:location.href=\''+adress+'/webSite/questionnaires/add_questionnaire\'"></input>').attr('value', 'New questionnaire');
				add.css("background-color", "#00B16A");
				add.css("height", "40px");
				var statistic = $('<input type="button" class="w3-button w3-indigo w3-margin" style="width: 155px;" onclick="window:location.href=\''+adress+'/webSite/questionnaires/statistics\'"></input>').attr('value', 'statistics');
				$('#addQuestionnaire').append(add,statistic);
			}
		}
	});
}
else {
	if (checkUser == "") {
		document.location.href="../connexion/connect";
	}

	$(document).ready(function () {
	    // Warning
	    $(window).on('beforeunload', function(){
	    	return "Any changes will be lost";
	    });

	    // Form Submit
	    $(document).on("submit", "form", function(event){
	        // disable warning
	        $(window).off('beforeunload');
	    });
	});
	window.onunload = function() { chrome.runtime.sendMessage(editorExtensionId, {action: 'stop'}); }
	var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"get_questions", id:param });

	post.done(function(data){
		var dataParse = JSON.parse(data);
		console.log(dataParse);
		document.title = dataParse[0]['title'];
		$('h1').text(dataParse[0]['title']);
		rules = $('<p class="w3-center"></p>').text('If you are not sure of the answer, put that you don\'t know or don\'t answer')
		statement = $('<p class="w3-center"></p>').text(dataParse[0]['statement']);
		$('#content').append(rules,statement);

		var form = $('<form method="post" action="checkAnswers.php"></form>');
		var user_email = $('<input type="hidden" name="user_email" value="'+checkUser+'">');
		form.append(user_email);

		var start = $('<div class="w3-center"></div>');
		var valid = $('<button class="w3-button w3-blue w3-section">Start questionnaire</button>');
		valid.click(function(){
			var that = $(this);
			var already_done = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"already_done", user:checkUser, id_questionnaire:dataParse[0]['id_questionnaire'] })

			already_done.done(function(data){
				console.log(data);
				if (data != 0) {
					var r = confirm("You have already answered this questionnaire, if you continue all your previous data will be deleted");
					if (r == true) {
						var reset_user_research = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"reset_user_research", user:checkUser, id_questionnaire:dataParse[0]['id_questionnaire'], param:param });
						reset_user_research.done(function(data){
							console.log(data);
							that.parent().css('display','none');
							that.parent().next().css('display','block');

							if (dataParse[0]['link_img'] != "") {
								console.log(dataParse[0]['link_img']);
								link = $('<img class="w3-margin-top">').attr('src', dataParse[0]['link_img']).attr('alt', dataParse[0]['link_img']);
								link.css( "width", "90%" );
								link.insertAfter('#title');
							}

							var url = window.location.href; 
							chrome.runtime.sendMessage(editorExtensionId, {action: 'start', url: url, email: checkUser},
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
						console.log(dataParse[0]);
						link = $('<img class="w3-margin-top">').attr('src', dataParse[0]['link_img']).attr('alt', dataParse[0]['link_img']);
						link.css( "width", "90%" );
						link.insertAfter('#title');
					}

					var url = window.location.href; 
					chrome.runtime.sendMessage(editorExtensionId, {action: 'start', url: url, email: checkUser},
						function(response) {
							console.log(response);
						});
				}
			})
		});

		start.append(valid);
		form.append(start);

		for (var i = 0; i < dataParse.length; i++) {
			var div = $('<div class="question w3-center" id="'+(i+1)+'"></div>');
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
				var valid = $('<div class="w3-center"><input class="w3-button w3-blue w3-margin" style="width: 150px;" type="button"  value="valid"></div>');
				div.append(input,valid);
			}
			else if (dataParse[i]['type_ques'] == 'number') {
				var input = $('<input class="w3-input" step="any" type="number" name="q['+dataParse[i]['id']+'][answer]">');
				if (dataParse[i]['particule'] != '') {
					var particule = $('<label>'+dataParse[i]['particule']+'</label>')
				}
				var valid = $('<div class="w3-center"> <input class="w3-button w3-blue w3-margin" style="width: 150px;" type="button" value="valid"> </div>');
				div.append(input,particule,valid);
			}
			else if (dataParse[i]['type_ques'] == 'interval') {
				var input = $('<input class="w3-input" step="any" type="number" name="q['+dataParse[i]['id']+'][answer]">');
				var valid = $('<div class="w3-center"><input class="w3-button w3-blue w3-section" style="width: 150px;" type="button" value="valid"></div>');
				div.append(input,valid);
			}
			else if (dataParse[i]['type_ques'] == 'radio') {
				var ol = $('<ol type="A" style="text-align: left;"></ol>');
				var choices = dataParse[i]['question'].split('(/=/)');
				for (var j = 1; j < choices.length; j++) {
					var input = $('<li> <input type="radio" class="w3-radio" name="q['+dataParse[i]['id']+'][answer]" value="'+(j-1)+'"> <label>'+choices[j]+'</label> </li>');
					ol.append(input);
				}
				var valid = $('<div class="w3-center"> <input class="w3-button w3-blue w3-section " style="width: 150px;" type="button" value="valid"> </div>');
				div.append(ol,valid);
			}
			else if (dataParse[i]['type_ques'] == 'free') {
				var input = $('<input class="w3-input" type="text" name="q['+dataParse[i]['id']+'][answer]">');
				var valid = $('<div class="w3-center"> <input class="w3-button w3-blue w3-section" style="width: 150px;" type="button" value="valid"> </div>');
				div.append(input,valid);
			}

			div.css("margin", "5%");
			form.append(div);
		}

		var lastDiv = $('<div class="w3-center"></div>')
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

		var use_graph = $('<input class="w3-radio" type="checkbox" name="use_graph"> <label>Did you use the graph to answer the questionnaires?</label>');

		user_opinion.append(label_knowledge,knowledge,use_graph);

		var submit = $('<input class="w3-button w3-green w3-section w3-margin" style="width: 200px;" type="submit" value="send">');
		lastDiv.css("margin", "5%")

		lastDiv.append(p,user_opinion,submit);
		form.append(lastDiv);
		$('#form-questionnaire').css("width","100%");
		form.css("margin-top","0px");
		$('#content').append(form);

		$(document).ready(function(){

			$('div.question').css('display','none');
			$('form > div:last-child').css('display','none');

			$('div.question > div.w3-center > input[type="button"]').click(function(){
				$(this).parent().parent().css('display','none');
				$(this).parent().parent().next().css('display','block');
			})

			$('div.question > div.w3-center > input[type="button"][value="valid"]').click(function(){
				chrome.runtime.sendMessage(editorExtensionId, {action: 'change_question'},
					function(response) {
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
					chrome.runtime.sendMessage(editorExtensionId, {action: 'stop', email: checkUser},
						function(response) {
							submitted = true;
							console.log(response);
						});
				}

				return valid;
			});
		});
	});
}