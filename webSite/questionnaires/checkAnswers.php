<?php 
header('Access-Control-Allow-Origin: *');
header("Location: http://163.172.59.102/webSite/questionnaires/questionnaire.html");

class answer{}

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

//var_dump($_POST);

$servername = "localhost";
$username = "root";
$password = "stageOsaka";
$dbname = "chrome_extension";

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$first = true;
	$requete = "SELECT id, answer FROM questions WHERE id=";
	foreach ($_POST['q'] as $key => $value) {
		if ($first) {
			$requete = $requete.$key;
			$first = false;
		}
		else {
			$requete = $requete.' OR id='.$key;
		}
	}

	$stmt = $conn->prepare($requete);
	$stmt->execute();

	$answers = $stmt->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);

	$stmt = $conn->prepare("INSERT INTO answers (key_question, answer, result)
		VALUES (:key_question, :answer, :result)");
	$stmt->bindParam(':key_question', $key_question);
	$stmt->bindParam(':answer', $answer);
	$stmt->bindParam(':result', $result);

	foreach ($_POST['q'] as $key => $value) {
		$key_question = $key;
		$answer = $value;
		$result = true;
		
		$tmp = strtoupper(stripVN($value));
		$tmp = explode(" ", $tmp);
		$a = explode(",", $answers[$key][0]);
		foreach ($a as $k => $v) {
			if (array_search($v, $tmp) === false) {
				$result = false;
			}
		}
		$stmt->execute();
	}
}
catch(PDOException $e)
{
	echo "Error: " . $e->getMessage();
}
$conn = null;

?>