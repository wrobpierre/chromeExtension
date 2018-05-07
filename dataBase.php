<?php 
header('Access-Control-Allow-Origin: *');

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
			if (isset($_POST['d']) && isset($_POST['url']) && isset($_POST['uniqId']) ) {
				/*$stmt = $conn->prepare("SELECT s.id, s.url, view, timer FROM sites s INNER JOIN firsturl fu ON s.key_first_url = fu.id WHERE fu.url like :url");
				$stmt->bindParam(':url', $url);
				$url = $_POST['url'];
				$stmt->execute();

				$compare = $stmt->fetchAll(PDO::FETCH_CLASS, "site");
				$url_compare = array_column($compare, 'url');

				$insert = [];
				$update = [];

				foreach ($_POST['d']['data'] as $key => $value) {
					$c = array_search($value['url'], $url_compare); 
					if ($c !== false) {
						$value['views'] += $compare[$c]['view'];
						$value['timeOnPage']['secondes'] += json_decode($compare[$c]['timer'])['secondes'];
						if ($value['timeOnPage']['secondes'] >= 60) {
							$value['timeOnPage']['secondes'] = $value['timeOnPage']['secondes']%60;
							$value['timeOnPage']['minutes'] += 1;
						}
						$value['timeOnPage']['minutes'] += json_decode($compare[$c]['timer'])['minutes'];
						if ($value['timeOnPage']['minutes'] >= 60) {
							$value['timeOnPage']['minutes'] = $value['timeOnPage']['minutes']%60;
							$value['timeOnPage']['hours'] += 1;
						}
						$value['timeOnPage']['hours'] += json_decode($compare[$c]['timer'])['hours'];
						$value['id'] = $compare[$c]['id'];
						array_push($update, $value);
					}
					else {
						array_push($insert, $value);
					}
				}

				$stmt = $conn->prepare("UPDATE sites
					SET view = :view, timer = :timer
					WHERE id = :id");
				$stmt->bindParam(':view', $view);
				$stmt->bindParam(':timer', $timer);
				$stmt->bindParam(':id', $id);

				foreach ($update as $key => $value) {
					$view = $value['views'];
					$timer = json_encode($value['timeOnPage']);
					$id = $value['id'];

					$stmt->execute();
				}*/

				if (isset($_POST['type'])) {
					$stmid = $conn->prepare("INSERT INTO users (check_id) VALUES (:createId)");
					$stmid->bindParam(':createId', $createId);
					$createId = $_POST['uniqId'];
					$stmid->execute();
				}

				$stmtCheck = $conn->prepare("SELECT id FROM users WHERE check_id like '".$_POST['uniqId']."'");
				$stmtCheck->execute();
				
				$uniqId = $stmtCheck->fetchAll();
				
				$stmt = $conn->prepare("INSERT INTO sites (url, title, keywords, view, timer, host_name, key_user, key_first_url) SELECT :url ,:title ,:keywords,:view,:timer, :host_name, :key_user, id FROM firsturl WHERE url like :first_url ");
					//$reponse = $conn->query('SELECT id FROM firsturl WHERE key_first_url = '+$key_first_url);
				$stmt->bindParam(':url', $url);
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':keywords', $keywords);
				$stmt->bindParam(':view', $view);
				$stmt->bindParam(':timer', $timer);
				$stmt->bindParam(':first_url', $first_url);
				$stmt->bindParam(':host_name', $hostName);
				$stmt->bindParam(':key_user', $uniqId[0]['id']);

				$first_url = $_POST['url']['firstUrl'];

				foreach ($_POST['d']['data'] as $key => $value) {
					if ($value['hostName'] != '163.172.59.102') {
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

						if ( $value['timeOnPage']['hours'] > 0 || $value['timeOnPage']['minutes'] > 0 || $value['timeOnPage']['secondes'] > 2 ) {
							//echo "pas de bruit";
							$timer = json_encode($value['timeOnPage']);
							$stmt->execute();
						}
						/*else {
							echo "bruit";
						}*/	
					}
				}
			}
				//echo "New records created successfully";
		}
		elseif ($_POST['key'] == 'load') {
			$requete = "";
			if (isset($_POST['id']) && isset($_POST['user'])) {
				$requete = "SELECT s.*
				FROM users u
				INNER JOIN sites s ON u.id = s.key_user
				INNER JOIN firsturl fu ON s.key_first_url = fu.id
				WHERE u.id = ".$_POST['user']." AND fu.id = ".$_POST['id'];
			}
			elseif (isset($_POST['id'])) {
				$requete = "SELECT s.* 
				FROM sites s
				INNER JOIN firsturl fu ON s.key_first_url = fu.id
				WHERE fu.id = ".$_POST['id'];
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