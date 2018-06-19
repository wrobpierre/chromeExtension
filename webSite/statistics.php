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
<script src="http://d3js.org/d3.v3.min.js"></script>
</head>
<?php include './layout/includes.php'; ?>
<body>
	<?php include './layout/header.php'; ?>
	<div id="form-questionnaire" class="w3-col l12 w3-white w3-margin-bottom w3-center" style="margin-top:50px;">
		<h1 class="w3-center">Statistics</h1>

		<div class="widget w3-col l6">
			<div class="header">Percent of response without the graphics help</div>
			<div id="chart1" class="chart-container"></div>
		</div>
		<div class="widget  w3-col l6">
			<div class="header">Percent of response with the graphics help</div>
			<div id="chart2" class="chart-container"></div>
		</div>

		<ul id="users">

		</ul>

	</div>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">
		var adress = "http://163.172.59.102"
		// var adress = "http://localhost/chromeExtension"

		var param = document.URL.split('-')[1];
		//console.log(param);

		if (param == undefined) {
			var post = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:"all" });

			post.done(function(data){
				if (data != '') {
					var dataParse = JSON.parse(data);
					console.log(dataParse);
				}
			});
		}

		var post = $.post(adress+'/dataBase.php', { key:"get_data_users" });

		post.done(function(data){
			console.log(data);
			var dataParse = JSON.parse(data);
			var ul = $('ul');
			ul.css("list-style", "none");

			$.each(dataParse, function(index,value){
				console.log($('ul#users > li#'+value['user_id']));
				if ($('ul#users > li#'+value['user_id']).length) {
					var link = adress+'/webSite/graph.php?id='+value['id']+'&user='+value['user_id'];
					if (value['title'] == "") {
						var li_questionnaire = $('<li><input type="button" value="'+link+'" class="button_link" onclick="window:location.href=\''+link+'\'"></input></li>');
					}
					else {	
						var li_questionnaire = $('<li><input type="button" value="'+value['title']+'" class="button_link" onclick="window:location.href=\''+link+'\'"></input></li>');
					}
					$('li#'+value['user_id']+'>ul').append(li_questionnaire);
				}
				else {
					var li = $('<li id="'+value['user_id']+'"></li>');
					var p = $('<p>User '+value['user_id']+'</p>');
					var ul = $('<ul></ul>');
					var link = adress+'/webSite/graph.php?id='+value['id']+'&user='+value['user_id'];
					if (value['title'] == "") {
						var li_questionnaire = $('<li><input type="button" value="'+link+'" class="button_link" onclick="window:location.href=\''+link+'\'"></input></li>');
					}
					else {	
						var li_questionnaire = $('<li><input type="button" value="'+value['title']+'" class="button_link" onclick="window:location.href=\''+link+'\'"></input></li>');
					}
					li_questionnaire.css("list-style", "none");
					ul.append(li_questionnaire);
					li.append(p,ul);
					$('ul#users').append(li);
					$('.button_link').css("width", "80%")
				}
			});
		});
	</script>
	<script src="js/pieChart.js"></script>
	<!-- <?php include './layout/footer.php'; ?> -->
</body>
</html>