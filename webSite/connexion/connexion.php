<?php
header('Access-Control-Allow-Origin: *');

    $servername = "localhost";
    $username = "root";
    // $password = "stageOsaka";
    $password = "";
    $dbname = "chrome_extension";

    try {

        if( isset($_POST['email']) && isset($_POST['password']) ){
        
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $salt = "7Gmk6}b]qgs%";
        $salt = md5($salt);
        $pwd = $_POST['password'];
        $pwdSalt = md5($salt.$pwd);

        $stmtCheck = $conn->prepare("SELECT id, email, password FROM users WHERE email like '".$_POST['email']."'");

        $stmtCheck->execute();
        $user = $stmtCheck->fetchAll();

        $email = $user[0]['email'];
        $pwdCheck = $user[0]['password'];        

        if($_POST['email'] == $email && $pwdSalt == $pwdCheck){
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