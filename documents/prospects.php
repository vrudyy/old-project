<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    if(isset($_POST["status"]) && $_POST["status"] != 0){
        $prospects = $dao->listAllWhere("Prospect", " WHERE `prospectStatus` = ".$_POST["status"]." AND `clientId` = '$client->clientId';");
    }else{
        $prospects = $dao->listAll("Prospect", "clientId", $client->clientId);
    }
    
    if(isset($_POST["course"]) && $_POST["course"] != "all"){
        $size = sizeof($prospects);
        for($i = 0; $i<$size; $i++){
            if(strpos($prospects[$i]->prospectCourses, $_POST["course"]) === false){
                unset($prospects[$i]);
            }
        }
    }
    
    
    $prospectStatuses = $dao->listAll("ProspectStatus");
    $courses = $dao->listAll('Course', 'clientId', $client->clientId);
    
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
                    <h3><?php echo $dic->translate("Prospects") ?></h3>
                    <?php
                        if(isset($_POST["filter"]) && $_POST["filter"]=='open'){
                    ?>
                        <form id="filter-form" method="post">
                    <?php
                        if(isset($_POST["course"])){
                            echo '<input type="hidden" name="course" value="'.$_POST["course"].'"/>';
                        }
                        if(isset($_POST["status"])){
                            echo '<input type="hidden" name="status" value="'.$_POST["status"].'"/>';
                        }
                    ?>
                            <button id="filter-button" name="filter" value="close">
                                <i class="fas fa-caret-up"></i>
                            </button>
                        </form>
                    <?php  
                        }else{
                    ?>
                        <form id="filter-form" method="post">
                            <button id="filter-button" name="filter" value="open">
                    <?php
                        if(isset($_POST["course"])){
                            echo '<input type="hidden" name="course" value="'.$_POST["course"].'"/>';
                        }
                        if(isset($_POST["status"])){
                            echo '<input type="hidden" name="status" value="'.$_POST["status"].'"/>';
                        }
                    ?>
                                <i class="fas fa-sort-down"></i>
                            </button>
                        </form>
                    <?php
                        }
                    ?>
                    <a href="newprospect.php"><?php echo $dic->translate("Add New") ?></a>
                </div>
                <?php
                    if(isset($_POST["filter"]) && $_POST["filter"]=='open'){
                        include_once 'content/prospect-filter.php';
                    }
                ?>
                <div class="in-row vsec cards" style="width:98%; margin: 20px 1%; padding: 10px;">
                    <?php
                    foreach($prospects as $p){
                        #$p = new Prospect();
                        $parent = $dao->get("Contact", $p->parentId, "contactId");
                        #$parent = new Contact();
                        $status = $dao->get("ProspectStatus", $p->prospectStatus, "prospectStatusId");
                        echo '<div class="in-card-wrapper col3">';
                            echo '<div class="in-card in-row">';
                                echo '<div class="in-card-title col10">';
                                    echo '<form method="post" action="prospect.php?sec=courses">';
                                        echo '<input type="hidden" name="prospectId" value="'.$p->prospectId.'" />';
                                        echo '<button type="submit">'.$parent->contactFirstName." ".$parent->contactLastName.'</button>';
                                    echo '</form>';
                                echo '</div>';
                                echo '<i class="in-card-icon fas fa-chart-line col2"></i>';
                                echo '<div class="col10 in-card-details">';
                                    echo '<ul class="in-row">';
                                        echo '<li class="in-row"><span>'.$dic->translate("Email").':</span>'.$parent->contactEmail.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Phone").':</span>'.$parent->contactPhone.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Status").':</span>'.$dic->translate($status->prospectStatusName).'</li>';
                                    echo '</ul>';
                                echo '</div>';
                                echo '<div class="col2 in-card-edit">';
                                    echo '<form action="editprospect.php" method="post" class="in-row in-card-edit">';
                                        echo '<input type="hidden" name="prospectId" value="'.$p->prospectId.'" />';
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