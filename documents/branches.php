<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    
    include("content/database.php");
    include("content/session.php");
    
    $branches = $dao->listAll("Branch", "clientId", $client->clientId);
    
    

    
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
                    <h3><?php echo $dic->translate("Branches") ?></h3>
                    <a href="newbranch.php"><?php echo $dic->translate("New Branch") ?></a>
                </div>
                <div class="in-row vsec cards" style="width:98%; margin: 20px 1%; padding: 10px;">
                    <?php
                    foreach($branches as $b){
                        $manager = $dao->get("Contact", $b->managerId, "contactId");
                        echo '<div class="in-card-wrapper col3">';
                            echo '<div class="in-card in-row">';
                                echo '<div class="in-card-title col10">';
                                    echo '<form method="get" action="branch.php">';
                                        echo '<input type="hidden" name="branchid" value="'.$b->branchId.'" />';
                                        echo '<button type="submit">'.$b->branchName.'</button>';
                                    echo '</form>';
                                echo '</div>';
                                echo '<i class="in-card-icon fas fa-university col2"></i>';
                                echo '<div class="col10 in-card-details">';
                                    echo '<ul class="in-row">';
                                        echo '<li class="in-row"><span>'.$dic->translate("Manager").':</span>'.$manager->contactFirstName.' '.$manager->contactLastName.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Email").':</span>'.$manager->contactEmail.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Phone").':</span>'.$manager->contactPhone.'</li>';
                                    echo '</ul>';
                                echo '</div>';
                                echo '<div class="col2 in-card-edit">';
                                    echo '<form action="editbranch.php" method="post" class="in-row in-card-edit">';
                                        echo '<input type="hidden" name="branchid" value="'.$b->branchId.'" />';
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