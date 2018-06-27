<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Users</title>
	<link rel="stylesheet" type="text/css" href="/webSite/css/questionnaire.css">
	<style>

	.chart-container{
		padding:25px;
	}

	
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>
</head>
<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/includes.php'; ?>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/webSite/layout/header.php'; ?>
	<div id="form-questionnaire" class="w3-col l12 w3-white w3-margin-bottom w3-center" style="margin-top:50px;">
		<h1 class="w3-center">Statistics</h1>
		<h2 class="w3-center">Statistics for</h2>
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
					<div id="nbParticipants" class=" w3-center w3-section w3-col l6">
						<p>Number of participants without graph help: </p>
					</div>
					<div id="nbParticipants2" class=" w3-center w3-section w3-col l6">
						<p>Number of participants with graph help: </p>
					</div>
				</div>
				<div class="w3-center w3-section">
					<div id="avgSites" class=" w3-center w3-section w3-col l6">
						<p>Number of sites visited on average without graph help: </p>
					</div>
					<div id="avgSites2" class=" w3-center w3-section w3-col l6">
						<p>Number of sites visited on average with graph help: </p>
					</div>
				</div>
				<div  style="margin-top: 50px;">
					<h3>Percent of good response</h3>
				</div>
				<div class="widget w3-col l6">
					<div class="header">Without the graphics help</div>
					<div id="chart1" class="chart-container"></div>
				</div>
				<div class="widget  w3-col l6">
					<div class="header">With the graphics help</div>
					<div id="chart2" class="chart-container"></div>
				</div>
			</div>
			<div  style="margin-top: 50px;">
				<h3>Number of participants according to their level of knowledge in the area of ​​the questionnaire</h3>
			</div>
			<div class="w3-col l2 w3-padding"></div>
			<div class="w3-row w3-section w3-padding">
				<div class="w3-col l6 w3-padding">
					<canvas id="barChart" class="w3-col l12"></canvas>
				</div>
				<div class="w3-col l6 w3-padding">
					<canvas id="barChart3" class="w3-col l12"></canvas>
				</div>
			</div>
			<div style="margin-top: 50px;">
				<h3>Number of participants based on difficulty in answering questions</h3>
			</div>
			<div class="w3-row w3-section w3-padding">
				<div class="w3-col l6 w3-padding">
					<canvas id="barChart2" class="w3-col l12"></canvas>
				</div>
				<div class="w3-col l6 w3-padding">
					<canvas id="barChart4" class="w3-col l12"></canvas>
				</div>
			</div>

		</div>
		<script src="/webSite/questionnaires/js/barChart.js"></script>

		<script src="/webSite/questionnaires/js/pieChart.js"></script>
		<script src="/webSite/questionnaires/js/statistics.js"></script>
		<!-- <?php include '/webSite/layout/footer.php' ?> -->
	</body>
	</html>