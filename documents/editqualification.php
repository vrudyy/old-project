<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Address.php");
   
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
    $qualification = $dao->get("Qualification", $_POST["qualificationId"], "qualificationId");
    
    
    if(isset($_POST["submit"])){
        if(strcmp($_POST["submit"], "delete")==0){
            $dao->delete("Qualification", "qualificationId", $qualification->qualificationId);
        }else if(strcmp($_POST["submit"], "update")==0){
            $qualificationName = $_POST["qualificationName"];
            $qualificationNameError = Validator::isEmpty($qualificationName, "Qualification Name");
            if(Validator::check($qualificationNameError)){
                $qualification->qualificationName = $qualificationName;
                $dao->update($qualification);
                
            }
        }
        ob_start();
        header('Location: '.'qualifications.php');
        ob_end_flush();
        die();
    }
    
    //closing connection;
    $dao->close();
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
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
                    <form action="" method="post">
                        <div class="in-row settingsSection vsec" style="border: none;">
                            <div class="col12">
                                <h3><?php echo($dic->translate("Update Qualification")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col2"><?php echo($dic->translate("Qualification Name")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $qualification->qualificationName ?>" class="col3" type="text" name="qualificationName">
                                    <p class="error col5"><?php echo $qualificationNameError ?></p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="qualificationId" value="<?php echo($_POST["qualificationId"])?>"/>
                        <button name="submit" value="update" type="submit" class="widget left"><?php echo($dic->translate("Update")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                        <button class="widget rwidget" name="submit" type="submit" value="delete"><?php echo($dic->translate("Delete")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 120px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>