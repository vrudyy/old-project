<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    //checking if the user logged in
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //initialising the DAO
    $dao = new DAO();
    
    //getting the client and contact of account owner
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    //closing connection;
    $dao->close();
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        <style>
            .in-teaching-nav{
                background: #dddddd;
                height: 60px;
                line-height: 50px;
                padding: 5px;
            }
            .in-teaching-nav li{
                float: left;
                color: black;
                margin-right: 5px;
                border-radius: 5px;
                padding: 0 10px;
            }
            .in-teaching-nav li:hover{
                background: lightsteelblue;
            }
            .in-teaching-nav button{
                font-size: 15px;
                color: lightslategray;;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="in-row in-teaching-nav">
                    <ul>
                        <li style="margin-left: 15px; "><form action="courses.php" method="post"><button><?php echo($dic->translate("Courses"));?></button></form></li>
                        <li><form action="qualification.php" method="post"><button><?php echo($dic->translate("Qualifications"));?></button></form></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>