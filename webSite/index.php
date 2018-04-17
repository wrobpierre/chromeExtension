<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta charset="utf-8">
	<title>Chrome extension</title>

	<link rel="stylesheet" type="text/css" href="https://static01.nyt.com/css/0.1/screen/build/interactive/us/politics/styles.css">
	<link rel="stylesheet" type="text/css" media="print" href="https://static01.nyt.com/css/0.1/print/styles.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="./css/header_footer.css">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
</head>
<body id="interactiveABC">
	<?php 
	if (isset($_GET['id'])) {
		echo '<input type="hidden" value="'.$_GET['id'].'">';
	}
	?>
	<header>
		<div id="header">
			<h1 class="textHeader">Welcome</h1>
			<p class="textHeader">Our application allow you to know which web sites are usually  consult on a specific page. You can click on a circle to see the associated web page.</p>
			<img src="./img/home.svg" id="imgHeader" alt="Home">
		</div>
	</header>
	<div id="azerty"></div>
	<div id="shell">
		<div id="page" class="tabContent active">                   
			<div id="main">
				<div id="interactiveShell">
					<div class="columnGroup firstColumnGroup">
						<div class="ledeStory">
							<div id="interactiveFreeFormMain">
								<div class="nytg">
									<div id="nytg-chartFrame">
										<div id="nytg-chart">
											<div class="nytg-navBar">
												<div id="nytg-search" style="display:none;"></div>
												<ul class="nytg-navigation clearfix">
													<li id="nytg-nav-all">All web site</li>
													<li id="nytg-nav-mandatory">By domain name</li>
													<li id="nytg-nav-discretionary">Visit by time</li>
													<li id="nytg-nav-deparment">Other</li>
													<!--<li id="nytg-nav-comparison">Historical</li> -->
												</ul>
											</div>
											<div id="nytg-tooltip">
												<div id="nytg-tooltipContainer">
													<div class="nytg-domain"></div>
													<div class="nytg-logo"></div>
													<div class="nytg-rule"></div>
													<div class="nytg-url"></div>
													<div class="nytg-discretion"></div>
													<div class="nytg-valuesContainer">
														<span class="nytg-value"></span>
														<span class="nytg-change"></span>
													</div>
													<div class="nytg-chart"></div>
													<div class="nytg-tail"></div>
												</div>
											</div>
											<div id="nytg-overlays">
												<div class="nytg-overlay" id="nytg-totalOverlay" >
													<div class="nytg-overview">
														<h5>Which pages was used ?</h5>
														<p>You can see a huge parts of the searchs done from the same page as you.</p>
													</div>
													<div class="nytg-increasesLabel">
														<span><p>More pertinent</p></span>
													</div>
													<div class="nytg-decreasesLabel">
														<span><p>Less pertinent</p></span>
													</div>
													<div id="nytg-colorKey">
														<p>Circles are sized according to the number of views.</p>
														<div id="nytg-sizeKey">
															<div style="left:52px;top:10px;width:20px;" class="nytg-circleKeyLabel"><span style="left:25px;">1 million viewers</span></div>
															<div style="left:32px;top:28px;" class="nytg-circleKeyLabel"><span>100,000 viewers</span></div>
															<div style="left:32px;top:40px;" class="nytg-circleKeyLabel"><span>10,000 viewers</span></div>
															<svg id="nytg-scaleKey"></svg>
														</div>
														<p>Color shows the time spent on a web site.</p>

														<ul class="nytg-colorSwatches">
															<li class="change-decrease3"></li>
															<li class="change-decrease2"></li>
															<li class="change-decrease1"></li>
															<li class="change-increase1"></li>
															<li class="change-increase2"></li>
															<li class="change-increase3"></li>
														</ul>
														<!-- <ul class="nytg-colorTicks">
															<li></li>
															<li></li>
															<li></li>
															<li></li>
															<li></li>
															<li></li>
														</ul> -->
														<ul class="nytg-colorLabels">
															<li></li>
															<li></li>
															<li></li>
															<li></li>
															<li></li>
															<li></li>
															
														</ul>
													</div>
												</div>
												<div class="nytg-overlay" id="nytg-mandatoryOverlay" >
													<div class="nytg-mandatoryExplainer">
													</div>
													<div class="nytg-mandatoryAside">
														<h5>By domain name</h5>
														<p>You can see the different web pages visited by domain name. The importance of a page is calculate on the same way as the previece tab.</p>
													</div>
													<div class="nytg-discretionaryExplainer">
													</div>
													<div class="nytg-discretionaryAside">
														<h5>Recommended navigation route</h5>
														<p>We propose you a route who help the most person using our application. The horizontal axis represents the visit path of the users. The vertical axis represent the relevance of the pages.</p>
													</div>
												</div>
												<div class="nytg-overlay" id="nytg-discretionaryOverlay" >
													<div id="nytg-discretionaryIntro">
														<h5>Changes to Discretionary Spending</h5>
														<p>Discretionary spending is controlled by the annual budget process. The White House and Congress agreed to a cap in spending in August, so proposed increases in categories like education and energy require cuts in other areas.</p>
													</div>
												</div>
												<div class="nytg-overlay" id="nytg-departmentOverlay" >
													<p class="nytg-emptyCircleLabel">Empty circles show income.</p>
												</div>
												<div class="nytg-overlay" id="nytg-comparisonOverlay" >
													<h5>Comparison Overlay</h5>
												</div>
											</div>
											<div id="nytg-chartCanvas"></div>
										</div>
									</div>
								</div>

								<script src="http://code.jquery.com/jquery-1.7.1.js"></script>
								<script type="text/javascript" charset="utf-8">

									var nytg = nytg || {};

								/*var post = $.post('http://localhost/chromeExtension/dataBase.php', { key:"load" });

								post.done(function(data) {
									test = JSON.parse(data);
									test.forEach(function(element){
										element["positions"] = {"total":{"x": Math.random()*600 - 300, "y": Math.random()*600 - 300 }};
										element["domain"] = "Health and Human Services";
										element["timer"] = JSON.parse(element["timer"]);
										
										var nytg = nytg || {}; 
										nytg.budget_array_data = [];
										nytg.budget_array_data.push(element);
									});
								});

								post.fail(function(data){
									console.log('fail');
								});*/

								//console.log(nytg.budget_array_data);

								/*nytg.budget_array_data = 
									[
									{
										"url": "https://fr.wikipedia.org/wiki/Th%C3%A9orie_des_cordes",
										"positions": {
											"total": {
												"x": 308,
												"y": 229
											},
											"department": {
												"x": 225,
												"y": 476
											}
										},
										"id": 1,
										//"budget_2012": 66161000,
										"timer": {
											'hours': 0,
											'minutes': 2, 
											'secondes': 30
													},//0.14342286241139,
										"view": 1000,
										"domain": "Wikipedia",
										//"discretion": "Mandatory"
										},
										{
											"url": "https://lci.fr",
										"positions": {
											"total": {
												"x": 308,
												"y": 229
											},
											"department": {
												"x": 225,
												"y": 476
											}
										},
										"id": 2,
										//"budget_2012": 66161000,
										"timer": {
											'hours': 0,
											'minutes': 0, 
											'secondes': 5
													},//0.14342286241139,
										"view": 10,
										"domain": "lci",
										//"discretion": "Mandatory"
										},
										{
										"url": "https://youtube.com",
										"positions": {
											"total": {
												"x": 308,
												"y": 229
											},
											"department": {
												"x": 225,
												"y": 476
											}
										},
										"id": 3,
										//"budget_2012": 66161000,
										"timer": {
											'hours': 2,
											'minutes': 12, 
											'secondes': 46
													},//0.14342286241139,
										"view": 100,
										"domain": "youtube",
										//"discretion": "Mandatory"
										},
									/*{
										"name": "Veterans Health Administration",
										"positions": {
											"total": {
												"x": 570,
												"y": 340
											},
											"department": {
												"x": 248,
												"y": 434
											}
										},
										"id": 285,
										"budget_2012": 53981000,
										"change": 0.0424038087475223,
										"budget_2013": 56270000,
										"department": "Veterans Affairs",
										"discretion": "Discretionary"
									},
									];*/
								</script>

								<script src="../d3/d3.min.js"></script>
								<script src="../d3/d3.geom.min.js"></script>
								<script src="../d3/d3.layout.min.js"></script>
								<script src="js/script.js"></script>
								<script src="js/header_footer.js"></script>

								<!--END Stencil generated content. -->    </div><!--close main free form -->
							</div><!--close .insetHFullWidth -->
						</div><!--close .ledeStory -->
					</div><!--close .columnGroup -->
				</div><!--close #interactiveShell -->
			</div><!--close #main -->

		</div><!--close #page -->
	</div><!--close #shell -->
	<script type="text/javascript" language="JavaScript" src="https://static01.nyt.com/js/app/homepage/articleCommentCounts.js"></script>

	<!-- Start UPT call -->
	<img height="1" width="3" border=0 src="http://up.nytimes.com/?d=0//&t=6&s=0&ui=&r=&u=www%2enytimes%2ecom%2finteractive%2f2012%2f02%2f13%2fus%2fpolitics%2f2013%2dbudget%2dproposal%2dgraphic%2ehtml%3f">
	<!-- End UPT call -->


	<script language="JavaScript"><!--
	var dcsvid="";
	var regstatus="non-registered";
	//--></script>
	<script src="https://static01.nyt.com/js/app/analytics/trackingTags_v1.1.js" type="text/javascript"></script>
	<noscript>
		<div><img alt="DCSIMG" id="DCSIMG" width="1" height="1" src="http://wt.o.nytimes.com/dcsym57yw10000s1s8g0boozt_9t1x/njs.gif?dcsuri=/nojavascript&amp;WT.js=No&amp;WT.tv=1.0.7"/></div>
	</noscript>
	<div id="info"><img src="./img/info.svg" id="imgFooter" alt="Information"></div>
	<footer><div id="footer"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div></footer>

</body>
</html>


