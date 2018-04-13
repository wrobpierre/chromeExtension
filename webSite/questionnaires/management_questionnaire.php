<?php 
header('Access-Control-Allow-Origin: *');

class all{}

class questionnaire{}

function stripVN($str) {
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|ä)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ë)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ|î|ï)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ô|ö)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|û|ü)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ|ÿ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);

	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|Ä)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|Ë)/", 'E', $str);
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ|Î|Ï)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ô|Ö)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);
	return $str;
}

if (isset($_POST['action'])) {

	$servername = "localhost";
	$username = "root";
	$password = "stageOsaka";
	$dbname = "chrome_extension";
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($_POST['action'] == 'add') {
			header("Location: http://163.172.59.102/webSite/questionnaires/questionnaire.html");
			
			$id = uniqid();

			$stmt = $conn->prepare("INSERT INTO firsturl (url)
				VALUES (:url)");
			$stmt->bindParam(':url', $url);
			$url = "http://163.172.59.102/webSite/questionnaires/questionnaire.html?id=".$id;
			$stmt->execute();

			$stmt = $conn->prepare("INSERT INTO questionnaires (title, type, link, key_first_url)
				SELECT :title, :type, :link, id FROM firsturl WHERE url like :url");
			$stmt->bindParam(':title', $title);
			$stmt->bindParam(':type', $type);
			$stmt->bindParam(':link', $link);
			$stmt->bindParam(':url', $url);
			$title = $_POST['title'];
			$type = ($_POST['title'] == 'article') ? 0 : 1;
			$link = $_POST['data'];
			$stmt->execute();

			$stmt = $conn->prepare("INSERT INTO questions (question, answer, key_questionnaires)
				SELECT :question, :answer, id FROM questionnaires WHERE title like :title");
			$stmt->bindParam(':title', $title);
			$stmt->bindParam(':question', $question);
			$stmt->bindParam(':answer', $answer);

			foreach ($_POST['q'] as $key => $value) {
				$question = $value;
				$answer = strtoupper(stripVN($_POST['a'][$key]));

				$stmt->execute();
			}
		}
		elseif ($_POST['action'] == 'get_questions_to_edit') {
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("SELECT qtn.title, qtn.type, qtn.link, q.question, q.id
					FROM questionnaires qtn 
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					WHERE qtn.key_first_url = (SELECT id FROM firsturl WHERE url LIKE :url)");
				$stmt->bindParam(':url', $url);
				$url = 'http://163.172.59.102/webSite/questionnaires/questionnaire.html?id='.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "questionnaire"));
			}
		}
		elseif ($_POST['action'] == 'edit') {
			# code...
		}
		elseif ($_POST['action'] == 'delete') {
			$stmt = $conn->prepare("DELETE FROM firsturl WHERE url like :url");
			$stmt->bindParam(':url', $url);
			$url = $_POST['url'];
			$stmt->execute();

			echo "The questionnaire has been deleted";
		}
		elseif ($_POST['action'] == 'all') {
			$stmt = $conn->prepare("SELECT q.title, fu.url
				FROM questionnaires q 
				INNER JOIN firsturl fu ON q.key_first_url = fu.id");
			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "all"));
		}
		elseif ($_POST['action'] == 'get_questions') {
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("SELECT qtn.title, qtn.type, qtn.link, q.question, q.id
					FROM questionnaires qtn 
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					WHERE qtn.key_first_url = (SELECT id FROM firsturl WHERE url LIKE :url)");
				$stmt->bindParam(':url', $url);
				$url = 'http://163.172.59.102/webSite/questionnaires/questionnaire.html?id='.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "questionnaire"));
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