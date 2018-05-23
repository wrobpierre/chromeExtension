<?php
header('Access-Control-Allow-Origin: *');
    /**
     * Nous créons deux variables : $username et $password qui valent respectivement "Sdz" et "salut"
     */

    $servername = "localhost";
    $username = "root";
    // $password = "stageOsaka";
    $password = "";
    $dbname = "chrome_extension";

    try {
    	$stmid = $conn->prepare("INSERT INTO users (check_id,name,email,job) VALUES (:createId, :name, :email, :job)");
    	$stmid->bindParam(':password', $password);
    	$stmid->bindParam(':name', $name);
    	$stmid->bindParam(':email', $email);
    	$stmid->bindParam(':job', $job);
    	$createId = uniqid();
    	$name = $_POST['name'];
    	$email = $_POST['email'];
    	$job = $_POST['job'];
    	$job = $_POST['password'];
    	$stmid->execute()
    }
    catch(PDOException $e)
    {
    	echo "Error: " . $e->getMessage();
    }
    ?>