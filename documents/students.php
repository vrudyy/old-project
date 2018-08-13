<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    
    
    $students = $dao->listAll("Student", 'clientId', $client->clientId);
    

    
    
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
                    <h3><?php echo $dic->translate("Students") ?></h3>
                    <a href="newstudent.php"><?php echo $dic->translate("Add New Student") ?></a>
                </div>
                <div class="in-row vsec cards" style="width:98%; margin: 20px 1%; padding: 10px;">
                    <?php
                    foreach($students as $key => $value){
                        #$value = new Student();
                        $studentContact = $dao->get("Contact", $value->contactId, "contactId");
                        if($value->branchId != 0){
                            $branch = $dao->get("Branch", $value->branchId, "branchId");
                        }else{
                            $branch = new Branch();
                            $branch->branchName = $dic->translate("Not Set");
                        }
                        #$branch = new Branch();
                        
                        #$studentContact = new Contact();
                        $status = $value->studentStatus == 1 ? $dic->translate("Current") : $dic->translate("Past");
                        echo '<div class="in-card-wrapper col3">';
                            echo '<div class="in-card in-row">';
                                echo '<div class="in-card-title col10">';
                                    echo '<form method="post" action="student.php?sec=profile">';
                                        echo '<input type="hidden" name="studentId" value="'.$value->studentId.'" />';
                                        echo '<button type="submit">'.$studentContact->fullName().'</button>';
                                    echo '</form>';
                                echo '</div>';
                                echo '<i class="in-card-icon fas fa-user col2"></i>';
                                echo '<div class="col10 in-card-details">';
                                    echo '<ul class="in-row">';
                                        echo '<li class="in-row"><span>'.$dic->translate("Email").':</span>'.$studentContact->contactEmail.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Mobile").':</span>'.$studentContact->contactPhone.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Branch").':</span>'.$branch->branchName.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Status").':</span>'.$status.'</li>';
                                    echo '</ul>';
                                echo '</div>';
                                echo '<div class="col2 in-card-edit">';
                                    echo '<form style="margin: 10px 0 0 0;" action="editstudent.php" method="post" class="in-row in-card-edit">';
                                        echo '<input type="hidden" name="studentId" value="'.$value->studentId.'" />';
                                        echo '<button type="submit"><i style="margin:10px 0 0 0;font-size:14px;" class="fas fa-pencil-alt"></i></button>';
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