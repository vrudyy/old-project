<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    
    //starting the session and checking that the email is set
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //session email
    $accEmail = $_SESSION["email"]; 
    
    //initialising the DAOs
    $dao = new DAO();
    
    //getting the client and contact of the main user
    $contact = $dao->get("Contact", $accEmail, "contactEmail");
    $client = $dao->get("Client", $contact->contactId, "contactId");
    
    $dic = new Dictionary($contact->contactLanguage); 
    
    
    if(isset($_POST["submit"])){
        
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $userEmail = htmlspecialchars($_POST["userEmail"]);
        $phone = htmlspecialchars($_POST["phone"]);
        
        $firstNameError = Validator::isName($firstName, "Firstname");
        $lastNameError = Validator::isName($lastName, "Lastname");
        $userEmailError = Validator::isEmail($userEmail); 
        $phoneError = "";
        
        if(Validator::check($firstNameError, $lastNameError, $userEmailError, $phoneError) ){
            #$contact = new Contact();
            $contact->contactEmail = $userEmail;
            $contact->contactFirstName = $firstName;
            $contact->contactLastName = $lastName;
            $contact->contactPhone = $phone;
            $dao->update($contact);
            
            $_SESSION["email"] = $userEmail;
            ob_start();
            header('Location: '.'users.php');
            ob_end_flush();
            die();

        }
        
    }
     
     
    
    //closing the dao
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
                        <div class="in-row settingsSection vsec" style="border: none;">
                            <div class="col5">
                                
                                <h3><?php echo($dic->translate("Personal Details")) ?></h3>
                                
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("First Name")) ?></label>
                                    <input value="<?php echo $contact->contactFirstName ?>" class="col6" type="text" name="firstName">
                                    <p class="error"><?php echo $firstNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Last Name")) ?></label>
                                    <input value="<?php echo $contact->contactLastName ?>" class="col6" type="text" name="lastName">
                                    <p class="error"><?php echo $lastNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Email")) ?></label>
                                    <input value="<?php echo $contact->contactEmail ?>" class="col6" type="text" name="userEmail">
                                    <p class="error"><?php echo $userEmailError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Phone")) ?></label>
                                    <input value="<?php echo $contact->contactPhone ?>" class="col6" type="text" name="phone">
                                    <p class="error"><?php echo $phoneError ?></p>
                                </div>
                                
                            </div>
                        </div>
                        <button type="submit" name="submit" value="submit" class="widget left"><?php echo($dic->translate("Update")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 70px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>
