<?php
require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Mailer2.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");

$message = "";
$emailError = "";
$error = 0;
$dao = new DAO();
if($_POST){
    $email = $_POST["email"];
    
    if(strlen($email) == 0){
        $emailError = "Email can't be empty";
        $error = 1;
    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "The email is Invalid";
        $error = 1;
    }
    
    if($error == 0){
        $contact = $dao->get("Contact", $email, "contactEmail");
        $mailer = new Mailer2();
        $mailer->resetPassword($contact, "https://www.i-nucleus.com/main/resetpass.php?email=".md5($contact->contactEmail));
        $message = "The email with reset instructions have been sent!";
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
            .form p{
                margin: 20px;
                color: blue;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <form class="form" action="" method="post">
                <input type="email" placeholder="email" name="email">
                <span><?php echo  $emailError ?></span>
                <button>change password</button>
                <span><?php echo $message ?></span>
            </form>   
            
        </div>
    </body>
</html>