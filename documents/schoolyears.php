<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Sort.php");
    
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
    
    $schoolYears = $dao->listAll("SchoolYear", "clientId", $client->clientId);
    $sort = new Sort();
    $schoolYears = $sort->sortSchoolYears($schoolYears);
    
    
    //closing connection;
    $dao->close();
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        <link href="css/gstyle.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="home-title in-row">
                    <h3><?php echo $dic->translate("School Years") ?></h3>
                    <form action="addyear.php" method="post">
                        <button type="submit"><?php echo $dic->translate("Add Year") ?></button>
                    </form>
                </div>
                <div class="in-row vsec cards" style="width:98%; margin: 20px 1%; padding: 10px;">
                    <?php
                    $date = new Date();
                    foreach($schoolYears as $s){
                        if(strcmp($schoolYear->schoolYearStatus, "n") != 0){
                            $date->fromInput($s->schoolYearStart);
                            $s->schoolYearStart = $date->longDate();
                            $date->fromInput($s->schoolYearEnd);
                            $s->schoolYearEnd = $date->longDate();
                            echo '<div class="in-card-wrapper col3">';
                                echo '<div class="in-card in-row">';
                                    echo '<div class="in-card-title col10">';
                                        echo '<form method="post" action="schoolyear.php">';
                                            echo '<input type="hidden" name="schoolyearid" value="'.$s->schoolYearId.'" />';
                                            echo '<button type="submit">'.$s->schoolYearTitle.'</button>';
                                        echo '</form>';
                                    echo '</div>';
                                    echo '<i class="in-card-icon fas fa-calendar col2"></i>';
                                    echo '<div class="col10 in-card-details">';
                                        echo '<ul class="in-row">';
                                            echo '<li class="in-row"><span>'.$dic->translate("Start Date").':</span>'.$s->schoolYearStart.'</li>';
                                            echo '<li class="in-row"><span>'.$dic->translate("End Date").':</span>'.$s->schoolYearEnd.'</li>';
                                        echo '</ul>';
                                    echo '</div>';
                                    echo '<div class="col2 in-card-edit">';
                                        echo '<form action="editschoolyear.php" method="post" class="in-row in-card-edit">';
                                            echo '<input type="hidden" name="schoolyearid" value="'.$s->schoolYearId.'" />';
                                            echo '<button type="submit"><i style="font-size:14px;" class="fas fa-pencil-alt"></i></button>';
                                        echo '</form>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    } 
                    ?>
                </div>
               
            </div>
           
        </div>
        <?php include("content/footer.php") ?>
        <!---->
    </body>
</html>