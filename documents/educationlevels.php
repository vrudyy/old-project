<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    
    
    
    
    if(isset($_POST["delete"])){
        $dao->delete("EducationLevel", "educationLevelId", $_POST["educationLevelId"]);
    }
    $els = $dao->listAll("EducationLevel", "clientId", $client->clientId, "ASC", "educationLevelId");
    
    $dao->close();
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
                    <h3><?php echo $dic->translate("Education Levels") ?></h3>
                    <a href="neweducationlevel.php"><?php echo $dic->translate("Add New") ?></a>
                </div>
                <div class="col4" style="border-radius: 0;">
                    <div class="room in-row roomHead">
                        <h3 class="col3 r b"><?php echo $dic->translate("Level") ?></h3>
                        <h3 class="col6 r b"><?php echo $dic->translate("Level Name") ?></h3>
                    </div>
                    <?php
                        foreach($els as $key => $e){
                            #$e = new EducationLevel();
                            if($key % 2 ==0) {
                                echo "<div class=\"room in-row mg\">";
                            }else{
                                echo "<div class=\"room in-row ug\">"; 
                            }
                                echo "<h3 class=\"col3 r b\">".($key+1)."</h3>";
                                echo '<div class="col9 b r" style="padding: 0;">';
                                    echo '<form style="padding: 5px;" class="col11" action="editeducationlevel.php" method="post">';
                                        echo "<input type=\"hidden\" name=\"educationLevelId\" value=\"$e->educationLevelId\">";
                                        echo '<button type="submit" name="editeducationlevel" value="true">';
                                        echo $e->educationLevelName;
                                        echo '</button>';
                                    echo '</form>';
                                    echo '<form style="" method="post" class="col1">';
                                        echo "<input type=\"hidden\" name=\"educationLevelId\" value=\"$e->educationLevelId\">";
                                        echo '<button style="font-size: 15px; float:right;" type="submit" name="delete" value="delete">';    
                                        echo '<i class="fas fa-trash"></i>';    
                                        echo "</button>";        
                                    echo "</form>"; 
                                echo '</div>';
                            echo "</div>";
                        }
                    ?> 
                </div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>