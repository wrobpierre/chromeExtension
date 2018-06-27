var adress = "http://163.172.59.102"

var param = document.URL.split('-')[1];
var nbUsers = 0;

// Get the users responses for a questionnaire 
var post = $.post(adress+'/webSite/questionnaires/src/management_questionnaire.php', { action:"get_user_result", id:param });
post.done(function(data){
	if (data != "") {
		dataParse = JSON.parse(data);
		console.log(dataParse);

		//For each user adding radio to correct their answer (true or false)
		$.each(dataParse, function(index,value){

			//Change the color of the background as white or grey
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