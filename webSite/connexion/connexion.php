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

        if( isset($_POST['username']) && isset($_POST['password']) ){
        
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtCheck = $conn->prepare("SELECT id, email, check_id FROM users WHERE email like '".$_POST['username']."' AND check_id like '".$_POST['password']."'");
        $stmtCheck->execute();

        $user = $stmtCheck->fetchAll();

        $email = $user[0]['email'];
        $pwd = $user[0]['check_id'];

        if($_POST['username'] == $email && $_POST['password'] == $pwd){ // Si les infos correspondent...
            session_start();
            $_SESSION['user'] = $email;
            echo "Success";    
        }
        else{ // Sinon
            echo "Failed";
        }
    }
}
catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
?>