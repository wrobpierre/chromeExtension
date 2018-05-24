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

	$stmt = $conn->prepare("SELECT email FROM users WHERE email like :email");
	$stmt->bindParam(':email', $email);
	$email = $_POST['email'];
	$stmt->execute();
	$user = $stmt->fetchAll();

	if (isset($user[0]['email']) && $user[0]['email'] == $_POST['email']) {
		echo "Exist";
	}
	else{
		$salt = "7Gmk6}b]qgs%";
		$salt = md5($salt);
		$pwd = $_POST['password'];
		$pwd = md5($salt.$pwd);

		$stmid = $conn->prepare("INSERT INTO users (check_id,name,email,job, password) VALUES (:checkId, :name, :email, :job, :password)");
		$stmid->bindParam(':password', $password);
		$stmid->bindParam(':name', $name);
		$stmid->bindParam(':email', $email);
		$stmid->bindParam(':job', $job);
		$stmid->bindParam(':checkId', $checkId);
		$createId = uniqid();
		$name = $_POST['name'];
		$email = $_POST['email'];
		$job = $_POST['job'];
		$password = $pwd;
		$checkId = uniqid();


		if($stmid->execute()){
			session_start();
			$_SESSION['user'] = $email;
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