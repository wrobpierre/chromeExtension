<?php 
$val = $_GET['q'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		<?php if ($val == 1) {
			echo "Document analysis";
		} elseif ($val == 2) {
			echo "Image search 1";
		} elseif ($val == 3) {
			echo "Image search 2";
		} ?>	
	</title>
	<style type="text/css">
		div {
			margin: 0 0 40px 0;
		}
		
		div input {
			margin: 0 0 10px 0;
		}

		div input[type="button"] {
			display: block;
		}
	</style>
</head>
<body>
	<div id="content">
		<?php if ($val == 1) {
			echo "<h1>Document analysis</h1>";
			echo "<span>Link to the article => </span><a href=\"https://en.wikipedia.org/wiki/Blockchain\">link</a>";
			echo "<p>At the end of the reading, you will have to answer a questionnaire in order to determine what you have understood of the subject</p>";
		} elseif ($val == 2) {
			echo "<h1>Image search 1</h1>";
			echo "<img src=\"img/image_1.JPG\">";

			echo "<h2>Quizz :</h2>";
			echo "<form action=\"checkAnswers.php\" method=\"post\">";

			echo '<input type="hidden" name="key" value="question">';
			echo '<input type="hidden" name="val" value="'.$val.'">';

			echo "<div class=\"question\" id=\"1\">";
			echo "<p>Find the name of this building ?</p>";
			echo "<input type=\"text\" name=\"q[]\">";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div class=\"question\" id=\"2\">";
			echo "<p>How long has lasted the first OnTour of this building ?</p>";
			echo "<input type=\"number\" step=\"any\" name=\"q[]\"><span> years</span>";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div class=\"question\" id=\"3\">";
			echo "<p>What is the number of women from this country between the ages of 20 and 24 in the year 2000 ?</p>";
			echo "<input type=\"number\" step=\"any\" name=\"q[]\"><span> millions</span>";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div class=\"question\" id=\"4\">";
			echo "<p>What was the high school of the first president of this country?</p>";
			echo "<input type=\"text\" name=\"q[]\">";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div>";
			echo "<p>Don't forget to send your questionnaire and to press the button \"stop\"</p>";
			echo "<input type=\"submit\" value=\"send\">";
			echo "</div>";

			echo "</form>";
		} elseif ($val == 3) {
			echo "<h1>Image search 2</h1>";
			echo "<img src=\"img/image_2.jpg\">";

			echo "<h2>Quizz :</h2>";
			echo "<form action=\"checkAnswers.php\" method=\"post\">";

			echo '<input type="hidden" name="key" value="question">';
			echo '<input type="hidden" name="val" value="'.$val.'">';

			echo "<div class=\"question\" id=\"1\">";
			echo "<p>Find the name of this painting ?</p>";
			echo "<input type=\"text\" name=\"q[]\">";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div class=\"question\" id=\"2\">";
			echo "<p>How many people have worked to paint this wall ?</p>";
			echo "<input type=\"number\" step=\"any\" name=\"q[]\"><span> peoples</span>";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div class=\"question\" id=\"3\">";
			echo "<p>What is the name of the movie released in 1987 which one of the Cuban actors played in the godfather ?</p>";
			echo "<input type=\"text\" name=\"q[]\">";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div class=\"question\" id=\"4\">";
			echo "<p>A Cuban cigar is named after a famous play, what is the name of this cigar ?</p>";
			echo "<input type=\"text\" name=\"q[]\">";
			echo "<input type=\"button\" value=\"valid\">";
			echo "</div>";

			echo "<div>";
			echo "<p>Don't forget to send your questionnaire and to press the button \"stop\"</p>";
			echo "<input type=\"submit\" value=\"send\">";
			echo "</div>";

			echo "</form>";
		} ?>
	</div>
	

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			$('div.question[id!=1]').css('display','none');
			$('form div:last-child').css('display','none');

			alert("Don't forget to press the button \"start\" when you become your research !");
			
			$('input[type="button"]').click(function(){
				$(this).parent().css('display','none');
				$(this).parent().next().css('display','block');
			})

			$('form').submit(function(){
				alert('Thanks for your participation !')
				//return false;
			})
		})	
	</script>
</body>
</html>