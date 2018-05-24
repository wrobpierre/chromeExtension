<?php
header('Access-Control-Allow-Origin: *');

    $adress = "http://163.172.59.102";
    //$adress = "http://localhost/chromeExtension";

    $servername = "localhost";
    $username = "root";
    $password = "stageOsaka";
    //$password = "";
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
            header("Location: ".$adress."/webSite/index.html");
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