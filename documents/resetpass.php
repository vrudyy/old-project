<?php

require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");


$pass1 = $_POST["pass1"];
$pass2 = $_POST["pass2"];
$email = $_GET["email"];
$passwordError = "";
$error = 0;
$dao = new DAO();
if($_POST){
    $uppercase = preg_match('@[A-Z]@', $pass1);
    $lowercase = preg_match('@[a-z]@', $pass1);
    $number    = preg_match('@[0-9]@', $pass1);
    if(strcmp($pass1, $pass2) != 0){
        $passwordError = "The password don't much";
        $error = 1;
    }else if(strlen($pass1) == 0){
        $passwordError = "Password name can't be empty";
        $error = 1;
    }else if(!$uppercase || !$lowercase || !$number || strlen($pass1) < 8) {
        $passwordError = "Minimum 8 characters, 1 number, 1 uppercase, 1 lowercase";
        $error = 1;
    }
    
    if($error == 0){
        #$contact = new Contact();
        
        $contact = $dao->get("Contact", $email , "contactEmail");
        #$user = new User();
        $user = $dao->get("User", $contact->contactId, "contactId");
        $user->userPassword = md5($pass1);
        
        $dao->update($user);
        
        
        ob_start();
        header('Location: '.'passwordchanged.php');
        ob_end_flush();
        die();
    }
    
    
}
$dao->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>i-Nucleus Sign in</title>
        
        <link href="https://www.i-nucleus.com/main/css/defaults.css" rel="stylesheet" >
        <link href="https://www.i-nucleus.com/main/css/grid.css" rel="stylesheet" >
        <style>
            .form{
                margin-top: 150px;
            }
            .form input{
                margin: 10px auto;
                width: 250px;
                height: 45px;
                font-size: 1.2em
            }
            .form button{
                margin: 5px auto;
                width: 250px;
                padding: 15px;
                font-size: 1.1em;
            }
            .form span{
                color: red;
                font-size: 0.8em;
                height: 15px;
                display: block;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <form class="form" action="" method="post">
                <input type="password" placeholder="new password" name="pass1">
                <input type="password" placeholder="confirm password" name="pass2">
                <span><?php echo  $passwordError ?></span>
                <button>change password</button>
            </form>    
        </div>
    </body>
</html>