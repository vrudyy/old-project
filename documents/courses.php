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
    $courses = $dao->listAll("Course", "clientId", $client->clientId);
    
    
    //closing connection;
    $dao->close();
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        <link href="css/components.css" rel="stylesheet" type="text/css"/>
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
                        <li class="in-teach-tab-select" style="margin-left: 15px; "><form action="courses.php" method="post"><button><?php echo($dic->translate("Courses"));?></button></form></li>
                        <li><form action="qualifications.php" method="post"><button><?php echo($dic->translate("Qualifications"));?></button></form></li>
                    </ul>
                    <div class="home-title" style="border:none;">
                        <a href="newcourse.php"><?php echo $dic->translate("New Course") ?></a>
                    </div>
                    
                </div>
                <div class="in-row in-courses-wrapper">
                    <?php
                        foreach($courses as $c){
                            echo '<div class="in-course-wrapper col3">'
                            . '<div class="in-course">'
                                    . '<i class="fa fa-book"></i>'
                                    . '<form style="float:left;" action="course.php?sec=teachingnotes" method="post">'
                                        . '<input type="hidden" name="courseId" value="'.$c->courseId.'"/>'
                                        . '<button class="in-button" type="submit">'.$c->courseName.'</button>'
                                    . '</form>'
                                    . '<form style="float:right;margin-top:10px;" action="editcourse.php" method="post">'
                                        . '<input type="hidden" name="courseId" value="'.$c->courseId.'"/>'
                                        . '<button class="in-button" type="submit"><i style="margin:0;font-size:14px;" class="fas fa-pencil-alt"></i></button>'
                                    . '</form>'
                            . '</div>'
                            . '</div>';
                        }
                    
                    ?>
                </div>
            </div>
        </div>
        <?php include("content/footer.php") ?>
        <?php
            if(isset($_SESSION["popup"])){
                echo '<div id="popup">'.$dic->translate($_SESSION["popup"]).'</div>';
                unset($_SESSION["popup"]);
            }
            
        ?>
        <script src="javascript/hide.js"></script>
    </body>
</html>