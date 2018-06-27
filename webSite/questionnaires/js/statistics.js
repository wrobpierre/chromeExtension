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


			post.done(function(data){
				if (data != '') {
					dataParse = JSON.parse(data);
					console.log(dataParse);

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

					dataPieChart = {'percent': [] , 'total': 0};
					dataPieChart2 = {'percent': [] , 'total': 0};
					dataPieChart3 = {'percent': [] , 'total': 0};
					dataPieChart4 = {'percent': [] , 'total': 0};

					$('#chart1').children().remove();
					dataset[0].percent = percentPieChart(tabQuestionnaires, dataPieChart, 0);
					dataset[1].percent = 100 - dataset[0].percent;
					generatePieCharts(dataset, firstGraph);

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

					barChart($('#barChart'), knowledgeBarChart(tabQuestionnaires, 0), "Number of participants according to their level of knowledge in the area of ​​the questionnaire without graph help");
					barChart($('#barChart3'), knowledgeBarChart(tabQuestionnaires, 1), "Number of participants according to their level of knowledge in the area of ​​the questionnaire with graph help");
					
					barChart($('#barChart2'), rankBarChart(tabQuestionnaires, 0), "Number of participants based on difficulty in answering questions without graph help");
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
					var questions = createQuestions(tabQuestionnaires);
					if(this.value != "All questionnaires"){
						$('#questions').append('<h3>Look at the statistics for each questions</h3>');
						$.each(questions, function(index, value){
							$('#questions').append('<input class="w3-check" type="checkbox" checked="checked" value="'+value.key_question+'">');
							$('#questions').append('<label>'+value.question.split('(/=/)')[0]+'</label>');
							$('#questions').append('<br>');

						});
						$('#questions input').on('click', function(){
							var choosedQuestion = chooseQuestion(dataParse, questions);
							if( choosedQuestion != undefined && choosedQuestion.length>0){
								var tabQuestionnaires = choosedQuestion;

								dataPieChart = {'percent': [] , 'total': 0};
								dataPieChart2 = {'percent': [] , 'total': 0};
								$('#chart1').children().remove();
								dataset[0].percent = percentPieChart(tabQuestionnaires, dataPieChart, 0);
								dataset[1].percent = 100 - dataset[0].percent;
								generatePieCharts(dataset, firstGraph);

								$('#chart2').children().remove();
								var firstGraph2 = '#chart2'
								dataset2[0].percent = percentPieChart(tabQuestionnaires, dataPieChart2, 1);
								dataset2[1].percent = 100 - dataset2[0].percent;
								generatePieCharts(dataset2, firstGraph2);

								$('#chart3').children().remove();
								var firstGraph3 = '#chart3'
								dataset2[0].percent = percentPieChart(tabQuestionnaires, dataPieChart2, 0);
								dataset2[1].percent = 100 - dataset2[0].percent;
								generatePieCharts(dataset2, firstGraph3);

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

								barChart($('#barChart'), knowledgeBarChart(tabQuestionnaires, 0), "Number of participants according to their level of knowledge in the area of ​​the questionnaire without graph help");
								barChart($('#barChart3'), knowledgeBarChart(tabQuestionnaires, 1), "Number of participants according to their level of knowledge in the area of ​​the questionnaire with graph help");
								
								barChart($('#barChart2'), rankBarChart(tabQuestionnaires, 0), "Number of participants based on difficulty in answering questions without graph help");
								barChart($('#barChart4'), rankBarChart(tabQuestionnaires, 1), "Number of participants based on difficulty in answering questions with graph help");


								$('#nbParticipants p').remove();
								$("#nbParticipants").append('<p>Number of participants : '+nbParticipants(tabQuestionnaires, 0)+'</p>');
								$('#nbParticipants2 p').remove();
								$("#nbParticipants2").append('<p>Number of participants : '+nbParticipants(tabQuestionnaires, 1)+'</p>');
								$('#avgSites p').remove();
								$("#avgSites").append('<p>Number of sites visited on average without graph help: '+avgSites(tabQuestionnaires, 0)+'</p>');
								$('#avgSites2 p').remove();
								$("#avgSites2").append('<p>Number of sites visited on average with graph help: '+avgSites(tabQuestionnaires, 1)+'</p>');
							}
							else{
								var parentBarChart = $('#barChart').parent();
								var parentBarChart2 = $('#barChart2').parent();
								var parentBarChart3 = $('#barChart3').parent();
								var parentBarChart4 = $('#barChart4').parent();
								// var parentBarChart4 = $('#barChart4').parent();

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

function chooseQuestionnaires(dataParse, title){
	var tabQuestionnaires = []
	$.each(dataParse, function(index, value){
		if(value.title == title){
			tabQuestionnaires.push(value);
		}
	});
	return tabQuestionnaires;
}

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
		if (checkGraph == 1) {
			console.log(value);
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

function percentPieChart(dataParse, dataChart, checkGraph){
	$.each(dataParse, function(index, value){
		if(parseInt(value.result) && checkGraph == value.use_graph){
			dataChart.percent.push(value.key_user);
		}
		dataChart.total++;

	});

	return ((dataChart.percent.length/dataChart.total)*100).toFixed(2);
}

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
	console.log("fkezbfiuez"+count);
	return count;
}

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

function showElem(id) {
	var x = document.getElementById(id);
	if (x.className.indexOf("w3-show") == -1) {
		x.className += " w3-show";
	} else { 
		x.className = x.className.replace(" w3-show", "");
	}
}