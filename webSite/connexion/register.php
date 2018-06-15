<?php
header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username = "root";
$password = "stageOsaka";
// $password = "";
$dbname = "chrome_extension";

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $conn->prepare("SELECT email FROM users WHERE email like :login");
	$stmt->bindParam(':login', $login);
	$email = $_POST['login'];
	$stmt->execute();
	$user = $stmt->fetchAll();

	if (isset($user[0]['email']) && $user[0]['email'] == $_POST['login']) {
		echo "Exist";
	}
	else{
		$salt = "7Gmk6}b]qgs%";
		$salt = md5($salt);
		$pwd = $_POST['password'];
		$pwd = md5($salt.$pwd);

		$stmid = $conn->prepare("INSERT INTO users (check_id,name,email,job,lang,password) VALUES (:checkId, :name, :login, :job, :lang, :password)");
		$stmid->bindParam(':password', $password);
		$stmid->bindParam(':name', $name);
		$stmid->bindParam(':login', $login);
		$stmid->bindParam(':job', $job);
		$stmid->bindParam(':lang', $lang);
		$stmid->bindParam(':checkId', $checkId);
		$createId = uniqid();
		$name = $_POST['name'];
		$login = $_POST['login'];
		$job = $_POST['job'];
		$lang = $_POST['lang'];
		$password = $pwd;
		$checkId = uniqid();


		if($stmid->execute()){
			session_start();
			$_SESSION['user'] = $email;
			$_SESSION['timeout'] = time();
			echo "Success";
		}
        else{
        	echo "Failed";
        }

    }
}
catch(PDOException $e)
{
	echo "Error: " . $e->getMessage();
}
?>