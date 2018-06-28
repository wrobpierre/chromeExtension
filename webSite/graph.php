<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	
	<title>SharedOn</title>

	<link rel="stylesheet" type="text/css" href="https://static01.nyt.com/css/0.1/screen/build/interactive/us/politics/styles.css">
	<link rel="stylesheet" type="text/css" media="print" href="https://static01.nyt.com/css/0.1/print/styles.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<?php include './layout/includes.php'; ?>
	<style>
	.w3-bar-item.w3-button.w3-hide-small.w3-padding-large.w3-hover-white{color: white; text-decoration: none;}
</style>
</head>
<body id="interactiveABC">
	<?php include './layout/header.php'; ?>

	<div id="content"></div>
	<h1 id="question_title"></h1>
	<div id="list_questionnaire">
		
	</div>
	<div class="sort w3-right w3-col l3 w3-col s12 w3-margin w3-border">
		<div class="w3-container w3-red">
			<h2 class="w3-text-white">Sort</h2>
		</div>
		<div class="w3-padding">
			<div id="questionFilter" class="sorts w3-col m12 w3-center w3-section">
				<h5>Look at visited websites for each question</h5>
			</div>
			<hr>
			<div id="notes" class="w3-col m12 w3-center w3-section">
				<h5>Look at visited websites based on ratings results</h5>
			</div>
			
		</div>

	</div>
	<div id="shell" class="w3-col m9 w3-margin">
		<div id="page" class="tabContent active">                   
			<div id="main">
				<div id="interactiveShell">
					<div class="columnGroup firstColumnGroup">
						<div class="ledeStory">
							<div id="interactiveFreeFormMain">
								<div class="nytg">
									<h1 id="nytg-error" style="visibility: hidden;text-align: center;">An error occured or no data is available for this graphic</h1>
									<div id="nytg-chartFrame">
										<div id="nytg-chart">
											<div class="nytg-navBar">
												<div id="nytg-search" style="display:none;"></div>
												<ul id="navBarGraph" class="nytg-navigation clearfix">
													<li id="nytg-nav-all">All web site</li>
													<li id="nytg-nav-discretionary">Timeline</li>
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
														<h5>Context of the questionnaire</h5>
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
															<div style="left:52px;top:10px;width:20px;" class="nytg-circleKeyLabel"><span style="left:25px;"></span></div>
															<div style="left:32px;top:28px;" class="nytg-circleKeyLabel"><span></span></div>
															<div style="left:32px;top:40px;" class="nytg-circleKeyLabel"><span></span></div>
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
												<div class="nytg-overlay" id="nytg-discretionaryOverlay" >
													<div id="nytg-discretionaryIntro">
														<h5>Timeline</h5>
														<p>This graphic represent the navigation of two users with the best note (if only one line appears it's because there is only one people who answered this questionnaire). The graph reads from left to right, it represents the timeline of a user's navigation.</p>
														<ul>
															<li>The first line is the user who visited the fewest websites.</li>
															<li>The second line is the user who answered the questionnaire the most quickly.</li>
															<li>The size of the circles represents the number of times the user went to the page.</li>
															<li>The color représente the time spend on the page (Red a few time and green a lot of time).</li>
														</ul>
													</div>
												</div>
												<div class="nytg-overlay" id="nytg-departmentOverlay" >
													<p class="nytg-emptyCircleLabel">Empty circles show income.</p>
												</div>
												<div class="nytg-overlay" id="nytg-comparisonOverlay" >
													<h5>Comparison Overlay</h5>
												</div>
											</div>
											<div id="nytg-chartCanvas">
											</div>
										</div>
									</div>
								</div>

								<script src="http://code.jquery.com/jquery-1.7.1.js"></script>
								<script src="../d3/d3.min.js"></script>
								<script src="../d3/d3.geom.min.js"></script>
								<script src="../d3/d3.layout.min.js"></script>
								<script src="js/script.js"></script>

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
</body>
</html>


