<?php
header('Access-Control-Allow-Origin: *');

ini_set('display_errors',1);
error_reporting(E_ALL);

class site {}

class firsturl {}

if (isset($_POST['key'])) {

	/** @var string The name of the server where the database is stored */
	$servername = "localhost";
	/** @var string The login of the database */
	$username = "root";
	/** @var string The password of the database */
	$password = "stageOsaka";
	/** @var string The name of the database */
	$dbname = "chrome_extension";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// Set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($_POST['key'] == 'add') {
			if ( isset($_POST['d']) && isset($_POST['url']) && isset($_POST['email']) ) {

				$stmtCheck = $conn->prepare("SELECT id FROM users WHERE email like :email");
				$stmtCheck->bindParam(':email',$email);
				$email = $_POST['email'];
				$stmtCheck->execute();
				
				$uniqId = $stmtCheck->fetchAll();
				
				$stmt = $conn->prepare("INSERT INTO sites (url, title, keywords, view, timer, first_time, host_name, question, key_user, key_first_url) 
					SELECT :url ,:title ,:keywords,:view,:timer,:first_time,:host_name,:question,:key_user, id
					FROM firsturl 
					WHERE url like :first_url");
				$stmt->bindParam(':url', $url);
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':keywords', $keywords);
				$stmt->bindParam(':view', $view);
				$stmt->bindParam(':timer', $timer);
				$stmt->bindParam(':first_time', $first_time);
				$stmt->bindParam(':first_url', $first_url);
				$stmt->bindParam(':host_name', $hostName);
				$stmt->bindParam(':question', $question);
				$stmt->bindParam(':key_user', $uniqId[0]['id']);

				$first_url = $_POST['url']['firstUrl'];

				foreach ($_POST['d']['data'] as $key => $value) {
					$url = $value['url'];
					$title = $value['title'];
					if (isset($value['keywords'])) {
						$keywords = implode(", ", $value['keywords']);
					}
					else {
						$keywords = "nothing...";	
					}
					$view = $value['views'];
					$hostName = $value['hostName'];
					$first_time = $value['firstTime'];
					$question = json_encode($value['question']);
					if ( $value['timeOnPage']['hours'] > 0 || $value['timeOnPage']['minutes'] > 0 || $value['timeOnPage']['secondes'] > 2 ) {
						$timer = json_encode($value['timeOnPage']);
						$stmt->execute();
					}
				}
			}
			if (!isset($_POST['d'])) {
				echo "missing d";
			}
			if (!isset($_POST['url'])) {
				echo "missing url";
			}
			if (!isset($_POST['email'])) {
				echo "missing email";
			}
		}
		elseif ($_POST['key'] == 'load') {
			$requete = "";
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("SELECT s.*,
					(SELECT distinct SUM(a.result)
					FROM firsturl fu2
					INNER JOIN questionnaires qts ON fu2.id = qts.key_first_url
					INNER JOIN questions q ON qts.id = q.key_questionnaires
					INNER JOIN answers a ON q.id = a.key_question
					WHERE a.key_user = s.key_user and fu2.url LIKE 'http://163.172.59.102/webSite/questionnaires/questionnaire-".$_POST['id']."')note, 
					(SELECT distinct COUNT(*) FROM questions WHERE questions.key_questionnaires = questionnaires.id)nb_question
					FROM sites s
					INNER JOIN firsturl fu1 ON s.key_first_url = fu1.id
					INNER JOIN questionnaires ON fu1.id = questionnaires.key_first_url
					WHERE fu1.url LIKE 'http://163.172.59.102/webSite/questionnaires/questionnaire-".$_POST['id']."'");
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "site"));
			}
		}
		elseif ($_POST['key'] == 'first') {
			if (isset($_POST['url'])) {
				$stmt = $conn->prepare("INSERT INTO firsturl (url)
					VALUES (:url)");
				$stmt->bindParam(':url', $url);
				$url = $_POST['url'];
				$stmt->execute();
			}
		}
		elseif ($_POST['key'] == 'get_id_firstUrl') {
			if (isset($_POST['url'])) {
				$stmt = $conn->prepare("SELECT id FROM firsturl WHERE url like '".$_POST['url']."'");
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "firsturl"));
			}
		}
		elseif ($_POST['key'] == 'get_stat') {
			$stmt = $conn->prepare("SELECT qts1.id, qts1.title, qts1.statement, qts1.link_img, q1.question, q1.image, q1.key_questionnaires, a1.result, a1.rank, a1.knowledge, a1.use_graph, a1.key_user, a1.key_question, 
				( (SELECT COUNT(s.url)
				FROM questionnaires qts3
				INNER JOIN firsturl fu ON qts3.key_first_url = fu.id
				INNER JOIN sites s ON fu.id = s.key_first_url
				WHERE qts3.id = qts1.id ) /
				(SELECT COUNT(DISTINCT a2.key_user)
				FROM answers a2
				INNER JOIN questions q2 ON a2.key_question = q2.id
				INNER JOIN questionnaires qts2 ON q2.key_questionnaires = qts2.id
				WHERE qts2.id = qts1.id ) )avg_sites,
				(SELECT COUNT(DISTINCT a2.key_user)
				FROM answers a2
				INNER JOIN questions q2 ON a2.key_question = q2.id
				INNER JOIN questionnaires qts2 ON q2.key_questionnaires = qts2.id
				WHERE qts2.id = qts1.id )nb_users
				FROM questionnaires qts1
				INNER JOIN questions q1 ON qts1.id = q1.key_questionnaires
				INNER JOIN answers a1 ON q1.id = a1.key_question
				ORDER BY qts1.id");

			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "firsturl"));
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}

?>