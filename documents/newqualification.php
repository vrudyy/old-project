 <?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    
    //starts the session and checks if it is set
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //initialises the DAO
    $dao = new DAO();
    
    //gets the session email, contact, client and company name
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    
    //create a dictionary
    $dic = new Dictionary($client->clientLanguage);
    //when the user submits the form
    
    if(isset($_POST["submit"])){
        
        
        //gets the form data
        $qualificationName = htmlspecialchars($_POST["qualificationName"]);
        
        //checks if the input data is correct
        $qualificationNameError = Validator::isEmpty($qualificationName, "Qualification Name");
        
        
        //if there are no input errors
        if(Validator::check($qualificationNameError)){
            
            $qualification = new Qualification();
            $qualification->qualificationName = $qualificationName;
            $qualification->clientId = $client->clientId;
            $dao->add($qualification);
            $_SESSION["popup"] = "The qualification has been added!";
            
            //redirects to the settings page
            ob_start();
            header('Location: '.'qualifications.php');
            ob_end_flush();
            die();
             
        }
        
    }
    
    //closes the connection to the database
    $dao->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
    </head>
    <body style="background: #f7f7f7;">
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="settingswrapper">
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec">
                            <div class="col5">
                                <h3><?php echo($dic->translate("New Qualification")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Qualification Name")) ?> <span style="color:red;">*</span></label>
                                    <input class="col6" type="text" name="qualificationName">
                                    <p class="error col10"><?php echo $qualificationNameError ?></p>
                                </div>
                            </div>
                        </div>
                        <button class="widget left" type="submit" name="submit" value="submit"><?php echo($dic->translate("Add Qualification")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 100px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>