<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Mailer2.php");
    
    //checks the session variables
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    $accEmail = $_SESSION["email"]; 
    
    //initialises the DAOs
    $dao = new DAO();
   
    //gets the contact and the client of the currecnt user
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    //sets up the dictionary
    $dic = new Dictionary($clientt->clientLanguage);
    //getting all available roles
    $roles = $dao->listAllWhere("Role", "WHERE `roleId` != 1;");
    
    if(isset($_POST["submit"])){
        //getting the user inputs
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $userEmail = htmlspecialchars($_POST["userEmail"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $role = htmlspecialchars($_POST["role"]);
        
        //valdiating the errors
        $firstNameError = Validator::isEmpty($firstName, "Firstname");
        $lastNameError = Validator::isEmpty($lastName, "Lastname");
        $userEmailError = Validator::isEmail($userEmail); 
        $phoneError = "";
        
        //if there are no errors
        if(strlen($firstNameError) == 0 && strlen($lastNameError) == 0 && strlen($userEmailError) == 0 && strlen($phoneError) == 0 ){
            
            //adding a new contact
            $userContact = new Contact();
            $userContact->contactEmail = $userEmail;
            $userContact->contactFirstName = $firstName;
            $userContact->contactLastName = $lastName;
            $userContact->contactPhone = $phone;
            $userContact->clientId = $client->clientId;
            $cId = $dao->add($userContact);
            
            
            
            //adding a new user
            $newuser = new User();
            $newuser->userId = $dao->next("User", "userId");
            $newuser->clientId = $client->clientId;
            $newuser->contactId = $cId;
            $newuser->roleId = $role;
            $dao->add($newuser);
            
            
            $mailer = new Mailer2();
            $mailer->newUser($userContact, "https://www.i-nucleus.com/main/resetpass.php?email=".$userEmail);
            
            $_SESSION["popup"] = 1;
            ob_start();
            header('Location: '.'users.php');
            ob_end_flush();
            die();
        }
        
    }
    
    //closes the connections
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
                                <h3><?php echo($dic->translate("User Details")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("First Name")) ?></label>
                                    <input value="<?php echo $firstName ?>" class="col6" type="text" name="firstName">
                                    <p class="error"><?php echo $firstNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Last Name")) ?></label>
                                    <input value="<?php echo $lastName ?>" class="col6" type="text" name="lastName">
                                    <p class="error"><?php echo $lastNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Email")) ?></label>
                                    <input value="<?php echo $userEmail ?>" class="col6" type="text" name="userEmail">
                                    <p class="error"><?php echo $userEmailError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Phone")) ?></label>
                                    <input value="<?php echo $phone ?>" class="col6" type="text" name="phone">
                                    <p class="error"><?php echo $phoneError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Role")) ?></label>
                                    
                                    <select name="role">
                                        <?php 
                                            foreach($roles as $roles){
                                                echo "<option value=\"".$roles->roleId."\">".$roles->roleName."</option>";
                                            }
                                        ?>
                                    </select>
                                    
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <button name="submit" value="submit" type="submit" class="widget left"><?php echo($dic->translate("Add User")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 70px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
        <script src="javascript/resetForm.js" ></script>
    </body>
</html>