<?php 
header('Access-Control-Allow-Origin: *');
//var_dump($_POST['d']['data']);

class site {
	/*public $url;
	public $title;
	public $keywords;
	public $view;
	public $timer;*/
}

if (isset($_POST['key'])) {

	$servername = "163.172.59.102";
	$username = "root";
	$password = "stageOsaka";
	$dbname = "chrome_extension";
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($_POST['key'] == 'add') {
			if (isset($_POST['d'])) {
				$stmt = $conn->prepare("INSERT INTO sites (url, title, keywords, view, timer)
					VALUES (:url, :title, :keywords, :view, :timer)");
				$stmt->bindParam(':url', $url);
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':keywords', $keywords);
				$stmt->bindParam(':view', $view);
				$stmt->bindParam(':timer', $timer);

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
					$timer = json_encode($value['timeOnPage']);

					$stmt->execute();
				}

			}
				//echo "New records created successfully";
		}
		elseif ($_POST['key'] == 'load') {
			$stmt = $conn->prepare("SELECT * FROM sites");
			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "site"));
		}
		elseif ($_POST['key'] == 'delete') {
			$stmt = $conn->prepare("DELETE FROM sites");
			$stmt->execute();

				//echo "All datas deleted";
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}

?>