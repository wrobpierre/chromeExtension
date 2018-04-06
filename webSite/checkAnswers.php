<?php 
//header('Access-Control-Allow-Origin: *');

function stripVN($str) {
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);

	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);
	return $str;
}

//var_dump($_POST);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chrome_extension";

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("INSERT INTO answers (image, question, answer, result)
		VALUES (:image, :question, :answer, :result)");
	$stmt->bindParam(':image', $image);
	$stmt->bindParam(':question', $question);
	$stmt->bindParam(':answer', $answer);
	$stmt->bindParam(':result', $result);

	if ($_POST['val'] == 2) {
		$val = (int)$_POST['val']-1;
		$image = "image_".$val;
		foreach ($_POST['q'] as $key => $value) {

			$question = $key+1;
			$answer = $value;
			$result = false;

			if ($key == 0) {
				$tmp = mb_strtoupper($value, 'UTF-8');
				$tmp = explode(" ", $tmp);

				if (array_search("SAIGON", $tmp) !== false && array_search("OPERA", $tmp) !== false) {
					$result = true;
				}
			} elseif ($key == 1) {
				if ($value == 4) {
					$result = true;	
				}
			} elseif ($key == 2) {
				if ( $value >= 3 && $value <= 4 ) {
					$result = true;	
				}
			} elseif ($key == 3) {
				$tmp = strtoupper(stripVN($value));
				$tmp = explode(" ", $tmp);
				if (array_search("QUOC", $tmp) !== false && array_search("HOC", $tmp) !== false) {
					$result = true;
				}
			}
			$stmt->execute();
		}
	} elseif ($_POST['val'] == 3) {
		$val = (int)$_POST['val']-1;
		$image = "image_".$val;
		foreach ($_POST['q'] as $key => $value) {

			$question = $key+1;
			$answer = $value;
			$result = false;

			if ($key == 0) {
				$tmp = mb_strtoupper($value, 'UTF-8');
				$tmp = explode(" ", $tmp);

				if (array_search("PREHISTORIC", $tmp) !== false && array_search("WALL", $tmp) !== false && array_search("VINALES", $tmp) !== false) {
					$result = true;
				}
			} elseif ($key == 1) {
				if ($value == 18) {
					$result = true;	
				}
			} elseif ($key == 2) {
				$tmp = mb_strtoupper($value, 'UTF-8');
				$tmp = explode(" ", $tmp);

				if (array_search("THE", $tmp) !== false && array_search("UNTOUCHABLES", $tmp) !== false) {
					$result = true;
				}
			} elseif ($key == 3) {
				$tmp = mb_strtoupper($value, 'UTF-8');
				$tmp = explode(" ", $tmp);
				if (array_search("ROMEO", $tmp) !== false && array_search("JULIETA", $tmp) !== false) {
					$result = true;
				}
			}
			$stmt->execute();
		}
	}
}
catch(PDOException $e)
{
	echo "Error: " . $e->getMessage();
}
$conn = null;

?>