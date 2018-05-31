<?php 
header('Access-Control-Allow-Origin: *');

ini_set('display_errors',1);
error_reporting(E_ALL);

class site {
	/*public $url;
	public $title;
	public $keywords;
	public $view;
	public $timer;*/
}

class firsturl {}

if (isset($_POST['key'])) {

	$servername = "localhost";
	$username = "root";
	$password = "stageOsaka";
	//$password = "";
	$dbname = "chrome_extension";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($_POST['key'] == 'add') {
			if ( isset($_POST['d']) && isset($_POST['url']) && isset($_POST['email']) ) {
				//echo "<pre>";
				var_dump($_POST);
				//echo "</pre>";

				/*if (isset($_POST['type'])) {
					$stmid = $conn->prepare("INSERT INTO users (check_id) VALUES (:createId)");
					$stmid->bindParam(':createId', $createId);
					$createId = $_POST['uniqId'];
					$stmid->execute();
				}*/

				$stmtCheck = $conn->prepare("SELECT id FROM users WHERE email like :email");
				$stmtCheck->bindParam(':email',$email);
				$email = $_POST['email'];
				$stmtCheck->execute();
				
				$uniqId = $stmtCheck->fetchAll();
				
				$stmt = $conn->prepare("INSERT INTO sites (url, title, keywords, view, timer, first_time, host_name, question, key_user, key_first_url) SELECT :url ,:title ,:keywords,:view,:timer,:first_time,:host_name,:question,:key_user, id FROM firsturl WHERE url like :first_url ");
					//$reponse = $conn->query('SELECT id FROM firsturl WHERE key_first_url = '+$key_first_url);
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
					if ( $value['hostName'] != '163.172.59.102'/* && strpos($value['hostName'], 'www.google.') === false*/ ) {
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
						$question = $value['question'];
						if ( $value['timeOnPage']['hours'] > 0 || $value['timeOnPage']['minutes'] > 0 || $value['timeOnPage']['secondes'] > 2 ) {
							$timer = json_encode($value['timeOnPage']);
							$stmt->execute();
						}
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
				//echo "New records created successfully";
		}
		elseif ($_POST['key'] == 'load') {
			$requete = "";
			if (isset($_POST['id']) && isset($_POST['user'])) {
				$requete = "SELECT s.*, (SELECT SUM(a.result) FROM answers a WHERE a.key_user = ".$_POST['user']." )note
				FROM users u
				INNER JOIN sites s ON u.id = s.key_user
				INNER JOIN firsturl fu ON s.key_first_url = fu.id
				WHERE u.id = ".$_POST['user']." AND fu.id = ".$_POST['id'];
			}
			elseif (isset($_POST['id'])) {
				$requete = "SELECT s.*, (SELECT SUM(a.result) FROM answers a WHERE a.key_user = s.key_user)note, (SELECT distinct COUNT(*) FROM questions WHERE questions.key_questionnaires = q.id)nb_question
				FROM sites s
				INNER JOIN firsturl fu ON s.key_first_url = fu.id
				INNER JOIN questionnaires q ON fu.id = q.key_first_url
				WHERE fu.url LIKE 'http://163.172.59.102/webSite/questionnaires/questionnaire.php?id=".$_POST['id']."'";
			}
			else {
				$requete = "SELECT * FROM sites";
			}

			$stmt = $conn->prepare($requete);
			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "site"));
		}
		elseif ($_POST['key'] == 'delete') {
			$stmt = $conn->prepare("DELETE FROM sites");
			$stmt->execute();

				//echo "All datas deleted";
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
		elseif ($_POST['key'] == 'get_graphs') {
			$stmt = $conn->prepare("SELECT fu.id, fu.url , ('')title
				FROM firsturl fu
				WHERE NOT EXISTS (SELECT * FROM questionnaires q WHERE q.key_first_url = fu.id)

				UNION

				SELECT fu.*, q.title
				FROM firsturl fu
				INNER JOIN questionnaires q ON fu.id = q.key_first_url");
			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "firsturl"));
		}
		elseif ($_POST['key'] == 'get_data_users') {
			$stmt = $conn->prepare("SELECT distinct (u.id)user_id, fu.*, q.title
				FROM users u
				INNER JOIN sites s ON u.id = s.key_user
				INNER JOIN firsturl fu ON s.key_first_url = fu.id
				INNER JOIN questionnaires q ON fu.id = q.key_first_url

				UNION

				SELECT distinct (u.id)user_id, fu.*, ('')title
				FROM users u
				INNER JOIN sites s ON u.id = s.key_user
				INNER JOIN firsturl fu ON s.key_first_url = fu.id
				WHERE NOT EXISTS (SELECT * FROM questionnaires q where q.key_first_url = fu.id)");
			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "firsturl"));
		}
		elseif ($_POST['key'] == 'check_user_email') {
			if (isset($_POST['email'])) {
				$stmt = $conn->prepare("SELECT * FROM users WHERE email like :email");
				$stmt->bindParam(':email', $email);
				$email = $_POST['email'];
				$stmt->execute();

				if (!isset($_POST['method'])) {
					echo count($stmt->fetchAll());
				}
				else {
					echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "firsturl"));
				}
			}
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}

?>