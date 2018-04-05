<?php 

var_dump($_POST);

$servername = "163.172.59.102";
$username = "root";
$password = "stageOsaka";
$dbname = "chrome_extension";


try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("INSERT INTO Answers (image, question, answer, result)
		VALUES (:image, :question, :answer, :result");
	$stmt->bindParam(':image', $image);
	$stmt->bindParam(':question', $question);
	$stmt->bindParam(':answer', $answer);
	$stmt->bindParam(':result', $result);

	if ($_POST['val'] == 2) {
		$image = "image_".$_POST['val']-1;
		foreach ($_POST['q'] as $key => $value) {

			$question = $key+1;
			$answer = $value;
			$result = false;

			if ($key == 0) {
			# code...
			} elseif ($key == 1) {
				if ($value == 4) {
					$result = true;	
				}
			} elseif ($key == 2) {
				if ( 3 <= $value <= 4 ) {
					$result = true;	
				}
			} elseif ($key == 3) {
			# code...
			}
			$stmt->execute();
		}
	} elseif ($_POST['val'] == 3) {
		foreach ($_POST['q'] as $key => $value) {
		# code...
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