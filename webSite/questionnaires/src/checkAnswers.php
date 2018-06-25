<?php 
header('Access-Control-Allow-Origin: *');
$adress = "http://163.172.59.102";
// $adress = "http://localhost/chromeExtension";

ini_set('display_errors',1);
error_reporting(E_ALL);

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

$servername = "localhost";
$username = "root";
$password = "stageOsaka";
//$password = "";
$dbname = "chrome_extension";

if (isset($_POST['user_email'])) {
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$first = true;
		$requete = "SELECT id, type_ques, answer FROM questions WHERE id=";
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
		
		$answers = array();

		foreach ($stmt->fetchAll() as $key => $value) {
			$answers[$value['id']] = array("type" => $value['type_ques'], "answer" => $value['answer']);
		}

		/*echo "<pre>";
		var_dump($_POST);
		echo "</pre>";

		echo "<pre>";
		var_dump($answers);
		echo "</pre>";*/

		header("Location: ".$adress."/webSite/questionnaires/result.php");			
		$stmt = $conn->prepare("INSERT INTO answers (key_question, answer, result, rank, knowledge, use_graph, key_user)
			SELECT :key_question, :answer, :result, :rank, :knowledge, :use_graph, id FROM users WHERE email like :email");
		$stmt->bindParam(':key_question', $key_question);
		$stmt->bindParam(':answer', $answer);
		$stmt->bindParam(':result', $result);
		$stmt->bindParam(':rank', $rank);
		$stmt->bindParam(':knowledge', $knowledge);
		$stmt->bindParam(':use_graph', $use_graph);
		$stmt->bindParam(':email', $email);
		$email = $_POST['user_email'];
		$knowledge = $_POST['knowledge'];
		$use_graph = (!isset($_POST['use_graph'])) ? 0 : 1;

		foreach ($_POST['q'] as $key => $value) {
			$key_question = $key;
			$answer = $value['answer'];
			$rank = $value['rank'];
			$result = true;
			if ($answers[$key]['type'] == "text") {
				$tmp = strtoupper(stripVN($value['answer']));
				$tmp = explode(" ", $tmp);
				$a = explode(",", $answers[$key]['answer']);
				foreach ($a as $k => $v) {
					if (array_search($v, $tmp) === false) {
						$result = false;
					}
				}
			}
			elseif ($answers[$key]['type'] == "number") {
				if ($value['answer'] != explode("/", $answers[$key]['answer'])[0]) {
					$result = false;
				}
			}
			elseif ($answers[$key]['type'] == "interval") {
				$min = explode('/', $answers[$key]['answer'])[0];
				$max = explode('/', $answers[$key]['answer'])[1];
				if ($value['answer'] < $min || $value['answer'] > $max) {
					$result = false;
				}
			}
			elseif ($answers[$key]['type'] == "radio") {
				if (isset($value['answer'])) {
					if ($value['answer'] != $answers[$key]['answer']) {
						$result = false;
					}
				}
				else {
					$result = false;
				}
			}
			elseif ($answers[$key]['type'] == "free") {
				$result = null;
			}
			$stmt->execute();
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}
else {
	echo "missing user_email";
}

?>