<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    
    $el = $dao->get("EducationLevel", $_POST["educationLevelId"], "educationLevelId");
    $name = $el->educationLevelName;
    $nameError = "";
    
    if(isset($_POST["submit"])){
        $name = $_POST["name"];
        $nameError = Validator::isEmpty($name, "Educational Level Name");
        
        if(Validator::check($nameError)){
            $el->educationLevelName = $name;
            $dao->update($el);
            ob_start();
            header('Location: '.'educationlevels.php');
            ob_end_flush();
            die();
        }
        
    }
    
    
    

    
    
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
                <div class="settingswrapper">
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec" style="border: none;">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Education Level")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("First Name")) ?></label>
                                    <input value="<?php echo $name ?>" class="col6" type="text" name="name">
                                    <p class="error"><?php echo $nameError ?></p>
                                </div>
                                
                            </div>
                        </div>
                        <input type="hidden" name="educationLevelId" value="<?php echo($el->educationLevelId)?>"/>
                        <button name="submit" value="submit" type="submit" class="widget left"><?php echo($dic->translate("Update")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>



