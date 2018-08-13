<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    
    $classes = $dao->listAll("inClass", "clientId", $client->clientId);
    
    

    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="home-title in-row">
                    <h3><?php echo $dic->translate("Classes") ?></h3>
                    <a href="newclass.php"><?php echo $dic->translate("Add New Class") ?></a>
                </div>
                <div class="in-row vsec cards" style="width:98%; margin: 20px 1%; padding: 10px;">
                    <?php
                    foreach($classes as $c){
                        echo '<div class="in-card-wrapper col3">';
                            echo '<div class="in-card in-row">';
                                echo '<div class="in-card-title col10">';
                                    echo '<form method="post" action="class.php?sec=attendingstudents">';
                                        echo '<input type="hidden" name="classId" value="'.$c->classId.'" />';
                                        echo '<button type="submit">'.$c->classLabel.'</button>';
                                    echo '</form>';
                                echo '</div>';
                                echo '<i class="in-card-icon fas fa-users col2"></i>';
                                echo '<div class="col10 in-card-details">';
                                    echo '<ul class="in-row">';
                                        $branch = $dao->get("Branch", $c->branchId, "branchId");
                                        $room = $dao->get("Room", $c->roomId, "roomId");
                                        $user = $dao->get("User", $c->classAcademicCoordinator, "userId");
                                        $b = $dao->get("Contact", $user->contactId, "contactId");
                                        
                                        
                                        $tutor = $dao->get("Tutor", $c->tutorId, "tutorId");
                                        $tutorContact = $dao->get("Contact", $tutor->contactId, "contactId");
                                    
                                        echo '<li class="in-row"><span>'.$dic->translate("Branch").':</span>'.$branch->branchName.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Room").':</span>'.$room->roomName.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Class Tutor").':</span>'.$tutorContact->contactFirstName.' '.$tutorContact->contactLastName.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Academic Coordinator").':</span>'.$b->contactFirstName.' '.$b->contactLastName.'</li>';
                                    echo '</ul>';
                                echo '</div>';
                                echo '<div class="col2 in-card-edit">';
                                    echo '<form action="editclass.php" method="post" class="in-row in-card-edit">';
                                        echo '<input type="hidden" name="classId" value="'.$c->classId.'" />';
                                        echo '<button type="submit"><i style="font-size:14px;" class="fas fa-pencil-alt"></i></button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    } 
                    $dao->close();
                    ?>
                </div>
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>