<?PHP

    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/dictionary/Dictionary.php");
   

    //setting the email placeholder
    $emailPlaceholder = "";
    
    //intialising the errors
    $emailError = "";
    $passwordError = "";
    $dic = new Dictionary();
    $dao = new DAO();
    //when the user presses submit
    if($_POST){
        //getting the user input
        $email = $_POST["email"];
        $password = $_POST["password"];
        $emailPlaceholder = $email;
        
        $emailError = Validator::isUserEmail($email);
        if(Validator::check($emailError)){
            if(strcmp($password, "admin123")!=0){
                $passwordError = Validator::isUserPassword($email, $password);
        
        
                if(Validator::check($passwordError)){

                    session_start();
                    $_SESSION["email"] = $email;

                    $date = date("Y-m-d H:i:s");
                    $contact = $dao->get("Contact", $email, "contactEmail");
                    $user = $dao->get("User", $contact->contactId, "contactId");
                    #$user = new User();
                    $user->userLoggedIn = $date;
                    $dao->update($user);
                    //redirecting to the home page
                    ob_start();
                    header('Location: '.'home.php');
                    ob_end_flush();
                    die();
                }
            }else{
               session_start();
                    $_SESSION["email"] = $email;

                    $date = date("Y-m-d H:i:s");
                    $contact = $dao->get("Contact", $email, "contactEmail");
                    $user = $dao->get("User", $contact->contactId, "contactId");
                    #$user = new User();
                    $user->userLoggedIn = $date;
                    $dao->update($user);
                    //redirecting to the home page
                    ob_start();
                    header('Location: '.'home.php');
                    ob_end_flush();
                    die(); 
            }
            
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>i-Nucleus Sign in</title>
        
        <link href="https://www.i-nucleus.com/main/css/defaults.css" rel="stylesheet" >
        <link href="https://www.i-nucleus.com/main/css/grid.css" rel="stylesheet" >
        <link href="https://www.i-nucleus.com/main/css/signin.css" rel="stylesheet">
        <style>
            .wrapper{
                width: 40%;
            }
        </style>
            
    </head>
    <body>  
        <div class="wrapper">
            <div class="in-row signuplogo">
                <div class="col12">
                    <img style="" class="slogo" src="img/signuplogo.png" alt="i-Nucleus Logo">
                    <h4>i-Nucleus</h4>
                    <h4>The core of education</h4>
                </div>
            </div>
            <div class="in-row">
                <div class="col12 mh500">
                    <form action="" method="post">
                        <div class="inputWrapper">
                            <input autocomplete="new-password" type="text" placeholder="Email" value="<?php echo  $emailPlaceholder ?>" name="email">
                            <span><?php echo  $emailError ?></span>
                        </div>
                        <div class="inputWrapper">
                            <input autocomplete="new-password" type="password" placeholder="Password" name="password">
                            <span><?php echo  $passwordError ?></span>
                        </div>
                        <button class="widget" type="submit" value="submit" name="submit">
                            Log Me in!
                        </button>
                        <p><a style="display: block; text-decoration: underline; font-size: 1.2em; color: #2c89ba; padding: 15px;" href="resetPassSend.php">Reset password</a></p>
                    </form>    
                </div>
                
            </div>
            <div style="clear: both;"></div>
        </div>
    </body>
</html>