var adress = "http://163.172.59.102"
var post = $.post(adress+'/dataBase.php', { key:"get_stat" });
var dataParse;
var param = document.URL.split('-')[1];
var dataPieChart = {'percent': [] , 'total': 0};
var dataPieChart2 = {'percent': [] , 'total': 0};
var dataBarChart = [];
var dataBarChart2 = [];
var dataSecondChart = [];
var questionnairesNames = [];
var nbQuestions = [];

var dataset = [
{ name: 'success percentage', percent: 0.00 },
{ name: 'failure percentage', percent: 100.00 }
];
var dataset2 = [
{ name: 'success percentage', percent: 0.00 },
{ name: 'failure percentage', percent: 100.00 }
];

// Create the HTML elements and generate graphics with datas
post.done(function(data){
	if (data != '') {
		dataParse = JSON.parse(data);

		var firstGraph = '#chart1'
		dataset[0].percent = percentPieChart(dataParse, dataPieChart, 0);
		dataset[1].percent = 100 - dataset[0].percent;
		generatePieCharts(dataset, firstGraph);

		var firstGraph2 = '#chart2'
		dataset2[0].percent = percentPieChart(dataParse, dataPieChart2, 1);
		dataset2[1].percent = 100 - dataset2[0].percent;
		generatePieCharts(dataset2, firstGraph2);

		barChart($('#barChart'), knowledgeBarChart(dataParse, 0), "Without the graph help");
		barChart($('#barChart3'), knowledgeBarChart(dataParse, 1), "With the graph help");
		barChart($('#barChart2'), rankBarChart(dataParse, 0), "Without graph help");
		barChart($('#barChart4'), rankBarChart(dataParse, 1), "With graph help");


		$("#nbParticipants p").append(nbParticipants(dataParse, 0));
		$("#nbParticipants2 p").append(nbParticipants(dataParse, 1));
		$("#avgSites p").append(avgSites(dataParse, 0));
		$("#avgSites2 p").append(avgSites(dataParse, 1));

		return questionnairesInput();
	}
});

function questionnairesInput(){

	// Create input to choose the questionnaire you want to see and give him title of questionnaires
	$.each(dataParse, function(index, value){
		var alreadyExist = false;
		$.each(questionnairesNames, function(index, valueChart){
			if(value.title == valueChart.title){
				alreadyExist = true;
			}
		});
		if(!alreadyExist){
			questionnairesNames.push(value);
			$('#questionnaires').append('<input class="nav w3-button w3-block w3-left-align" type="button" name="" value="'+value.title+'">');
		}
	});

	// When a questionnaire is selected
	$('input').on('click', function(){
		var firstGraph = '#chart1'
		if(this.value != "All questionnaires"){
			var tabQuestionnaires = chooseQuestionnaires(dataParse, this.value);
			$('#title').text(this.value);

		}
		else{
			var tabQuestionnaires = dataParse;
			$('#title').text("all questionnaires");
		}

		// Object to create the data pie chart
		dataPieChart = {'percent': [] , 'total': 0};
		dataPieChart2 = {'percent': [] , 'total': 0};
		dataPieChart3 = {'percent': [] , 'total': 0};
		dataPieChart4 = {'percent': [] , 'total': 0};

		// Generate the pie chart for users who don't used help graph
		$('#chart1').children().remove();
		dataset[0].percent = percentPieChart(tabQuestionnaires, dataPieChart, 0);
		dataset[1].percent = 100 - dataset[0].percent;
		generatePieCharts(dataset, firstGraph);

		// Generate the pie chart for users who used help graph
		$('#chart2').children().remove();
		var firstGraph2 = '#chart2'
		dataset2[0].percent = percentPieChart(tabQuestionnaires, dataPieChart2, 1);
		dataset2[1].percent = 100 - dataset2[0].percent;
		generatePieCharts(dataset2, firstGraph2);

		var parentBarChart = $('#barChart').parent();
		var parentBarChart2 = $('#barChart2').parent();
		var parentBarChart3 = $('#barChart3').parent();
		var parentBarChart4 = $('#barChart4').parent();

		$('#barChart').remove();
		$('#barChart2').remove();
		$('#barChart3').remove();
		$('#barChart4').remove();

		parentBarChart.append('<canvas id="barChart" class="w3-col l12"></canvas>');
		parentBarChart2.append('<canvas id="barChart2" class="w3-col l12"></canvas>');
		parentBarChart3.append('<canvas id="barChart3" class="w3-col l12"></canvas>');
		parentBarChart4.append('<canvas id="barChart4" class="w3-col l12"></canvas>');

		// Generate the bar chart for the knowledge for users who don't used help graph
		barChart($('#barChart'), knowledgeBarChart(tabQuestionnaires, 0), "Number of participants according to their level of knowledge in the area of ​​the questionnaire without graph help");
		
		// Generate the bar chart for the knowledge for users who used help graph
		barChart($('#barChart3'), knowledgeBarChart(tabQuestionnaires, 1), "Number of participants according to their level of knowledge in the area of ​​the questionnaire with graph help");

		// Generate the bar chart for the rank for users who don't used help graph
		barChart($('#barChart2'), rankBarChart(tabQuestionnaires, 0), "Number of participants based on difficulty in answering questions without graph help");
		
		// Generate the bar chart for the rank for users who used help graph
		barChart($('#barChart4'), rankBarChart(tabQuestionnaires, 1), "Number of participants based on difficulty in answering questions with graph help");

		$('#nbParticipants p').remove();
		$("#nbParticipants").append('<p>Number of participants without graph help: '+nbParticipants(tabQuestionnaires, 0)+'</p>');
		$('#nbParticipants2 p').remove();
		$("#nbParticipants2").append('<p>Number of participants without graph help: '+nbParticipants(tabQuestionnaires, 1)+'</p>');
		$('#avgSites p').remove();
		$("#avgSites").append('<p>Number of sites visited on average without graph help: '+avgSites(tabQuestionnaires, 0)+'</p>');
		$('#avgSites2 p').remove();
		$("#avgSites2").append('<p>Number of sites visited on average without graph help: '+avgSites(tabQuestionnaires, 1)+'</p>');

		$('#questions').children().remove();

		// push only the value of the selected question into this tab
		var questions = createQuestions(tabQuestionnaires);
		if(this.value != "All questionnaires"){
			$('#questions').append('<h3>Look at the statistics for each questions</h3>');
			$.each(questions, function(index, value){
				$('#questions').append('<input class="w3-check" type="checkbox" checked="checked" value="'+value.key_question+'">');
				$('#questions').append('<label>'+value.question.split('(/=/)')[0]+'</label>');
				$('#questions').append('<br>');

			});

			// When user select a question on the checkbox
			$('#questions input').on('click', function(){
				var choosedQuestion = chooseQuestion(dataParse, questions);
				if( choosedQuestion != undefined && choosedQuestion.length>0){
					var tabQuestionnaires = choosedQuestion;

					dataPieChart = {'percent': [] , 'total': 0};
					dataPieChart2 = {'percent': [] , 'total': 0};

					// Generate the pie chart for users who don't used help graph
					$('#chart1').children().remove();
					dataset[0].percent = percentPieChart(tabQuestionnaires, dataPieChart, 0);
					dataset[1].percent = 100 - dataset[0].percent;
					generatePieCharts(dataset, firstGraph);

					// Generate the pie chart for users who used help graph
					$('#chart2').children().remove();
					var firstGraph2 = '#chart2'
					dataset2[0].percent = percentPieChart(tabQuestionnaires, dataPieChart2, 1);
					dataset2[1].percent = 100 - dataset2[0].percent;
					generatePieCharts(dataset2, firstGraph2);

					// Generate the pie chart for users who don't used help graph
					$('#chart3').children().remove();
					var firstGraph3 = '#chart3'
					dataset2[0].percent = percentPieChart(tabQuestionnaires, dataPieChart2, 0);
					dataset2[1].percent = 100 - dataset2[0].percent;
					generatePieCharts(dataset2, firstGraph3);

					// Generate the pie chart for users who used help graph
					$('#chart4').children().remove();
					var firstGraph4 = '#chart4'
					dataset2[0].percent = percentPieChart(tabQuestionnaires, dataPieChart2, 1);
					dataset2[1].percent = 100 - dataset2[0].percent;
					generatePieCharts(dataset2, firstGraph4);

					var parentBarChart = $('#barChart').parent();
					var parentBarChart2 = $('#barChart2').parent();
					var parentBarChart3 = $('#barChart3').parent();
					var parentBarChart4 = $('#barChart4').parent();

					$('#barChart').remove();
					$('#barChart2').remove();
					$('#barChart3').remove();
					$('#barChart4').remove();

					parentBarChart.append('<canvas id="barChart" class="w3-col l12"></canvas>');
					parentBarChart2.append('<canvas id="barChart2" class="w3-col l12"></canvas>');
					parentBarChart3.append('<canvas id="barChart3" class="w3-col l12"></canvas>');
					parentBarChart4.append('<canvas id="barChart4" class="w3-col l12"></canvas>');

					// Generate the bar chart for users who don't used help graph
					barChart($('#barChart'), knowledgeBarChart(tabQuestionnaires, 0), "Number of participants according to their level of knowledge in the area of ​​the questionnaire without graph help");
					
					// Generate the bar chart for users who used help graph
					barChart($('#barChart3'), knowledgeBarChart(tabQuestionnaires, 1), "Number of participants according to their level of knowledge in the area of ​​the questionnaire with graph help");

					barChart($('#barChart2'), rankBarChart(tabQuestionnaires, 0), "Number of participants based on difficulty in answering questions without graph help");
					barChart($('#barChart4'), rankBarChart(tabQuestionnaires, 1), "Number of participants based on difficulty in answering questions with graph help");

					//Generate the global informations
					$('#nbParticipants p').remove();
					$("#nbParticipants").append('<p>Number of participants : '+nbParticipants(tabQuestionnaires, 0)+'</p>');
					$('#nbParticipants2 p').remove();
					$("#nbParticipants2").append('<p>Number of participants : '+nbParticipants(tabQuestionnaires, 1)+'</p>');
					$('#avgSites p').remove();
					$("#avgSites").append('<p>Number of sites visited on average without graph help: '+avgSites(tabQuestionnaires, 0)+'</p>');
					$('#avgSites2 p').remove();
					$("#avgSites2").append('<p>Number of sites visited on average with graph help: '+avgSites(tabQuestionnaires, 1)+'</p>');
				}
				// If no datas detected
				else{
					var parentBarChart = $('#barChart').parent();
					var parentBarChart2 = $('#barChart2').parent();
					var parentBarChart3 = $('#barChart3').parent();
					var parentBarChart4 = $('#barChart4').parent();

					$('#chart1').children().remove();
					$('#chart1').append('<p>No Data</p>');
					$('#chart2').children().remove();
					$('#chart2').append('<p>No Data</p>');
					$('#barChart').remove();
					parentBarChart.append('<p id="barChart">No Data</p>');
					$('#barChart2').remove();
					parentBarChart2.append('<p id="barChart2">No Data</p>');
					$('#barChart3').remove();
					parentBarChart3.append('<p id="barChart3">No Data</p>');
					$('#barChart4').remove();
					parentBarChart4.append('<p id="barChart4">No Data</p>');
					$('#nbParticipants p').remove();
					$("#nbParticipants").append('<p>No Data</p>');
					$('#avgSites p').remove();
					$("#avgSites").append('<p>No Data</p>');
					$('#avgSites2 p').remove();
					$("#avgSites2").append('<p>No Data</p>');

				}
			});
		}

	});
}

/**
* The function “chooseQuestion” allow the user to choose the statistic of which question he want to see.
* It check if the questions’ checkbox are checked or not.
* 
* @param : object dataParse- Datas of questionnaires to generate the statistics
* @return : array - Array of objects which are sort by checked questions
*/
function chooseQuestion(dataParse){
	var checkedQuestions = [];
	var checked = $('#questions input');
	if(checked.length){
		$.each(dataParse, function(index, value){
			$.each(checked, function(indexChecked, valueChecked){
				if(value.key_question== valueChecked.value && valueChecked.checked){
					checkedQuestions.push(value);
				}
			});
		})
	}
	return checkedQuestions;
}

/**
* The function “createQuestions” initialize the array questions which will contain the questions than a user want to see on the graphics 
* (in this array you can’t have the same question twice).
* 
* @param : object dataParse-  Datas of questionnaires to generate the statistics
* @return : array - Array of objects containing the datas of the questions 
*/
function createQuestions(dataParse){
	var questions = []
	var questionAlreadyExist = false;
	$.each(dataParse, function(index, value){
		$.each(questions, function(indexQuestions, valueQuestions){
			if(valueQuestions.question == value.question){
				questionAlreadyExist = true;
			}
		});
		if(questionAlreadyExist == false){
			questions.push(value);
		}
	});
	return questions;
}

/**
* The function “chooseQuestionnaires” allow the user to choose the statistic of which questionnaire he want to see.  
* 
* @param : object dataParse-  Datas of questionnaires to generate the statistics
* @param : string title - Datas of questionnaires to generate the statistics
* @return : array - Array of objects containing the datas of the questions  
*/
function chooseQuestionnaires(dataParse, title){
	var tabQuestionnaires = []
	$.each(dataParse, function(index, value){
		if(value.title == title){
			tabQuestionnaires.push(value);
		}
	});
	return tabQuestionnaires;
}

/**
* The function “knowledgeBarChart” count the number of person who said they had a knowledge as novice, medium or expert.  
* checkGraph determine if they had used the graph help.
*
* @param : object data Datas of questionnaires to generate the statistics.
* @param : int checkGraph- if 1 the user has used the graphics help to answer questionnaires, else he don’t use the graphics help.
* @return : array - Array of objects containing their knowledges 
*/
function knowledgeBarChart(data, checkGraph){
	var keyUser;
	var keyQuestionnaires;
	var useGraph;
	var knowledgeTab = [{
		'value1': {'labels':'novice', 'nbData': 0},
		'value2': {'labels':'medium', 'nbData': 0},
		'value3': {'labels':'expert', 'nbData': 0}
	}];

	$.each(data, function(index, value){
		if (index == 0) {
			keyUser = value.key_user;
			keyQuestionnaires = value.key_questionnaires;
			useGraph = value.use_graph;
			knowledge = value.knowledge;
		}

		if((keyUser != value.key_user || keyQuestionnaires != value.key_questionnaires) && checkGraph == parseInt(useGraph)){
			if (knowledgeTab[0].value1.labels == knowledge) {
				knowledgeTab[0].value1.nbData += 1;
			}
			else if (knowledgeTab[0].value2.labels == knowledge) {
				knowledgeTab[0].value2.nbData += 1;
			}
			else{
				knowledgeTab[0].value3.nbData += 1;
			}
		}
		keyUser = value.key_user;
		keyQuestionnaires = value.key_questionnaires;
		useGraph = value.use_graph;
		knowledge = value.knowledge;
		if(index == (data.length-1)  && checkGraph == parseInt(value.use_graph)){
			if (knowledgeTab[0].value1.labels == value.knowledge) {
				knowledgeTab[0].value1.nbData += 1;
			}
			else if (knowledgeTab[0].value2.labels == value.knowledge) {
				knowledgeTab[0].value2.nbData += 1;
			}
			else{
				knowledgeTab[0].value3.nbData += 1;
			}
		}
	});
	return knowledgeTab[0];
}

/**
* The function “rankBarChart” count the number of person who find the questions as easy, medium or hard. 
* checkGraph determine if they had used the graph help.
*
* @param : object dataPrase Datas of questionnaires to generate the statistics.
* @param : int checkGraph- if 1 the user has used the graphics help to answer questionnaires, else he don’t use the graphics help.
* @return : array - Array of objects containing the difficulties 
*/
function rankBarChart(dataParse, checkGraph){
	var keyUser;
	var keyQuestionnaires;
	var rankTab = [{
		'value1': {'labels':'easy', 'nbData': 0},
		'value2': {'labels':'medium', 'nbData': 0},
		'value3': {'labels':'hard', 'nbData': 0}
	}];

	$.each(dataParse, function(index, value){
		if (checkGraph == value.use_graph) {
			if (rankTab[0].value1.labels == value.rank) {
				rankTab[0].value1.nbData += 1;
			}
			else if (rankTab[0].value2.labels == value.rank) {
				rankTab[0].value2.nbData += 1;
			}
			else {
				rankTab[0].value3.nbData += 1;
			}

		}

	});
	return rankTab[0];
}

/**
* The function “percentPieChart” Calculate the percent of persons who answers right to list of questions.
*
* @param : object dataParse Datas of questionnaires to generate the statistics.
* @param : object dataChart An object containing  a tab for the percent of right answer and an int for the total of questions.
* @return : float- The percent of right answer for some questions
*/
function percentPieChart(dataParse, dataChart, checkGraph){
	$.each(dataParse, function(index, value){
		if(parseInt(value.result) && checkGraph == value.use_graph){
			dataChart.percent.push(value.key_user);
		}
		dataChart.total++;

	});

	return ((dataChart.percent.length/dataChart.total)*100).toFixed(2);
}

/**
* The function “nbParticipants” Calculate the number of participants depending of their uses of the help graphics.
*
* @param : object dataParse Datas of questionnaires to generate the statistics.
* @param : int checkGraph - if 1 the user has used the graphics help to answer questionnaires, else he don’t use the graphics help.
* @return : int - The number of participants
*/
function nbParticipants(dataParse, checkGraph){
	var count = 0;
	var keyUser;
	var useGraph ;
	var keyQuestionnaires;
	$.each(dataParse, function(index, value){
		if (index == 0) {
			keyUser = value.key_user;
			keyQuestionnaires = value.key_questionnaires;
			useGraph = value.use_graph;
		}
		console.log(checkGraph);

		if((keyUser != value.key_user || keyQuestionnaires != value.key_questionnaires) && checkGraph == parseInt(useGraph)){
			count ++;
		}

		keyUser = value.key_user;
		keyQuestionnaires = value.key_questionnaires;
		useGraph = value.use_graph;
		
		if(index == (dataParse.length-1)  && checkGraph == parseInt(value.use_graph)){
			count ++;
		}
	});
	return count;
}

/**
* The function “avgSites” Calculate the notes average of users for some questions.
*
* @param : object dataParse Datas of questionnaires to generate the statistics.
* @param : int checkGraph - if 1 the user has used the graphics help to answer questionnaires, else he don’t use the graphics help.
* @return : int - The average of notes.
*/
function avgSites(dataParse, checkGraph){
	var count = 0;
	var keyUser;
	var keyQuestionnaires;
	var useGraph;
	var avg = 0;
	var avgSite = 0;

	$.each(dataParse, function(index, value){
		if (index == 0) {
			keyUser = value.key_user;
			keyQuestionnaires = value.key_questionnaires;
			useGraph = parseInt(value.use_graph);
			avg_site = parseInt(value.avg_sites);
		}

		console.log( checkGraph == parseInt(useGraph));
		if((keyUser != value.key_user || keyQuestionnaires != value.key_questionnaires)  && checkGraph == useGraph){
			avg += avg_site;
			count ++;
		}
		keyUser = value.key_user;
		keyQuestionnaires = value.key_questionnaires;
		useGraph = parseInt(value.use_graph);
		avg_site = parseInt(value.avg_sites);

		if(index == (dataParse.length-1)  && checkGraph == parseInt(value.use_graph)){
			avg += avg_site;
			count ++;
		}
	});
	if(count == 0){
		return 0;
	}
	return (avg/count).toFixed(2);
}

/**
* The function “avgSites” Calculate the notes average of users for some questions.
*
* @param : The function “showElem” Show the slide’s bar elements.
* @param : object id- The id of an element of the DOM.
*/
function showElem(id) {
	var x = document.getElementById(id);
	if (x.className.indexOf("w3-show") == -1) {
		x.className += " w3-show";
	} else { 
		x.className = x.className.replace(" w3-show", "");
	}
}