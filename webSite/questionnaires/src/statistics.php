<?php
session_start();
if(isset($_SESSION['user'])){
	$checkUser = $_SESSION['user'];
	$checkIdUser = $_SESSION['id'];
}
else{
	$checkUser = null;
	$checkIdUser = null;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Users</title>
	<link rel="stylesheet" type="text/css" href="css/questionnaire.css">
	<style>

	.chart-container{
		padding:25px;
	}

	
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>
</head>
<?php include './layout/includes.php'; ?>
<body>
	<?php include './layout/header.php'; ?>
	<div id="form-questionnaire" class="w3-col l12 w3-white w3-margin-bottom w3-center" style="margin-top:50px;">
		<h1 class="w3-center">Statistics</h1>
		<h2 class="w3-center">Statistiques for</h2>
		<h2 id="title" class="w3-center">all questionnaires</h2>
		<div class="w3-col l2 w3-padding"></div>
		<div class="w3-col l8 w3-center w3-section">
			<button onclick="showElem('questionnaires')" class="w3-padding-16 w3-button w3-block w3-left-align w3-red w3-hover-light-grey">Questionnaires
				<i class="fa fa-caret-down"></i></button>
				<div id="questionnaires" class="w3-card w3-hide">
					<input class="nav w3-button w3-block w3-left-align" type="button" name="" value="All questionnaires">
				</div>
				<div class="w3-panel w3-border w3-padding">
					<div class="w3-col l3 w3-padding"></div>
					<div id="questions" class="w3-col l6" style="text-align: left;"></div>
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-center w3-section">
					<div id="nbPaticipants" class=" w3-center w3-section">
						<p>Number of participants : </p>
					</div>
					<div id="avgSites" class=" w3-center w3-section">
						<p>Number of sites visited on average : </p>
					</div>
				</div>
				<div class="widget w3-col l6">
					<div class="header">Percent of good response without the graphics help</div>
					<div id="chart1" class="chart-container"></div>
				</div>
				<div class="widget  w3-col l6">
					<div class="header">Percent of good response with the graphics help</div>
					<div id="chart2" class="chart-container"></div>
				</div>
			</div>
			<div class="w3-col l2 w3-padding"></div>
			<div class="w3-row w3-section w3-padding">
				<div class="w3-col l6">
					<canvas id="barChart" class="w3-col l12"></canvas>
				</div>
				<div class="w3-col l6 ">
					<canvas id="barChart2" class="w3-col l12"></canvas>
				</div>
			</div>

		</div>
		<script src="js/barChart.js"></script>
		<script type="text/javascript">
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

					barChart($('#barChart'), knowledgeBarChart(dataParse), "Number of participants according to their level of knowledge in the area of ​​the questionnaire");
					barChart($('#barChart2'), rankBarChart(dataParse), "Number of participants based on difficulty in answering questions");
					

					$("#nbPaticipants p").append(nbParticipants(dataParse));
					$("#avgSites p").append(avgSites(dataParse));

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
					console.log(this.value);
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

					$('#barChart').remove();
					$('#barChart2').remove();

					parentBarChart.append('<canvas id="barChart" class="w3-col l12"></canvas>');
					parentBarChart2.append('<canvas id="barChart2" class="w3-col l12"></canvas>');

					barChart($('#barChart'), knowledgeBarChart(tabQuestionnaires), "Number of participants according to their level of knowledge in the area of ​​the questionnaire");
					barChart($('#barChart2'), rankBarChart(tabQuestionnaires), "Number of participants based on difficulty in answering questions");
					
					$('#nbPaticipants p').remove();
					$("#nbPaticipants").append('<p>Number of participants : '+nbParticipants(tabQuestionnaires)+'</p>');
					$('#avgSites p').remove();
					$("#avgSites").append('<p>Number of sites visited on average : '+avgSites(tabQuestionnaires)+'</p>');

					$('#questions').children().remove();
					var questions = createQuestions(tabQuestionnaires);
					if(this.value != "All questionnaires"){
						$('#questions').append('<h3>Look at the statistics for each questions</h3>');
						$.each(questions, function(index, value){
							$('#questions').append('<input class="w3-check" type="checkbox" checked="checked" value="'+value.question.split('(/=/)')[0]+'">');
							$('#questions').append('<label>'+value.question.split('(/=/)')[0]+'</label>');
							$('#questions').append('<br>');

						});
						$('#questions input').on('click', function(){
							var choosedQuestion = chooseQuestion(dataParse, questions);
							console.log(choosedQuestion);
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

								var parentBarChart = $('#barChart').parent();
								var parentBarChart2 = $('#barChart2').parent();

								$('#barChart').remove();
								$('#barChart2').remove();

								parentBarChart.append('<canvas id="barChart" class="w3-col l12"></canvas>');
								parentBarChart2.append('<canvas id="barChart2" class="w3-col l12"></canvas>');

								barChart($('#barChart'), knowledgeBarChart(tabQuestionnaires), "Number of participants according to their level of knowledge in the area of ​​the questionnaire");
								barChart($('#barChart2'), rankBarChart(tabQuestionnaires), "Number of participants based on difficulty in answering questions");

								$('#nbPaticipants p').remove();
								$("#nbPaticipants").append('<p>Number of participants : '+nbParticipants(tabQuestionnaires)+'</p>');
								$('#avgSites p').remove();
								$("#avgSites").append('<p>Number of sites visited on average : '+avgSites(tabQuestionnaires)+'</p>');
							}
							else{
								var parentBarChart = $('#barChart').parent();
								var parentBarChart2 = $('#barChart2').parent();

								$('#chart1').children().remove();
								$('#chart1').append('<p>No Data</p>');
								$('#chart2').children().remove();
								$('#chart2').append('<p>No Data</p>');
								$('#barChart').remove();
								parentBarChart.append('<p id="barChart">No Data</p>');
								$('#barChart2').remove();
								parentBarChart2.append('<p id="barChart2">No Data</p>');
								$('#nbPaticipants p').remove();
								$("#nbPaticipants").append('<p>No Data</p>');
								$('#avgSites p').remove();
								$("#avgSites").append('<p>No Data</p>');

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

							if(value.question == valueChecked.value && valueChecked.checked){
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

			function knowledgeBarChart(data){
				var keyUser;
				var keyQuestionnaires;
				var knowledgeTab = [{
					'value1': {'labels':'novice', 'nbData': 0},
					'value2': {'labels':'medium', 'nbData': 0},
					'value3': {'labels':'expert', 'nbData': 0}
				}];

				$.each(data, function(index, value){
					if (index == 0) {
						keyUser = value.key_user;
						keyQuestionnaires = value.key_questionnaires;
					}
					if(keyUser != value.key_user || keyQuestionnaires != value.key_questionnaires || index == data.length-1){
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
					keyUser = value.key_user;
					keyQuestionnaires = value.key_questionnaires;
				});
				return knowledgeTab[0];
			}

			function rankBarChart(dataParse){
				var keyUser;
				var keyQuestionnaires;
				var rankTab = [{
					'value1': {'labels':'easy', 'nbData': 0},
					'value2': {'labels':'medium', 'nbData': 0},
					'value3': {'labels':'hard', 'nbData': 0}
				}];

				$.each(dataParse, function(index, value){
					if (rankTab[0].value1.labels == value.rank) {
						rankTab[0].value1.nbData += 1;
					}
					else if (rankTab[0].value2.labels == value.rank) {
						rankTab[0].value2.nbData += 1;
					}
					else{
						rankTab[0].value3.nbData += 1;
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

			function nbParticipants(dataParse){

				var count = 0;
				var keyUser;
				var keyQuestionnaires;

				$.each(dataParse, function(index, value){
					if (index == 0) {
						keyUser = value.key_user;
						keyQuestionnaires = value.key_questionnaires;
					}
					if(keyUser != value.key_user || keyQuestionnaires != value.key_questionnaires || index == dataParse.length-1){
						count ++;
					}
					keyUser = value.key_user;
					keyQuestionnaires = value.key_questionnaires;
				});
				return count;
			}

			function avgSites(dataParse){
				var count = 0;
				var keyUser;
				var keyQuestionnaires;
				var avg = 0;

				$.each(dataParse, function(index, value){
					if (index == 0) {
						keyUser = value.key_user;
						keyQuestionnaires = value.key_questionnaires;
					}
					if(keyUser != value.key_user || keyQuestionnaires != value.key_questionnaires || index == dataParse.length-1 || index == 0){
						avg += parseInt(value.avg_sites);
						count ++;
					}
					keyUser = value.key_user;
					keyQuestionnaires = value.key_questionnaires;
				});
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

</script>

<script src="js/pieChart.js"></script>
<!-- <?php include './layout/footer.php'; ?> -->
</body>
</html>