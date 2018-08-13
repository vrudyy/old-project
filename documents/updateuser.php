<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    
    //starting the session and checking that the email is set
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //initialising the DAOs
    $dao = new DAO();
    
    //getting the main user email
    $accEmail = $_SESSION["email"]; 
    
    //getting the id of the updated user
    $id = htmlspecialchars($_POST["id"]);
    
    $userContact = $dao->get("Contact", $id, "contactId");
    $userC = $dao->get("User", $userContact->contactId, "contactId");
    
    $roles = $dao->listAllWhere("Role", "WHERE `roleId` != 1;");
    
    
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    
    $dic = new Dictionary($client->clientLanguage);
    
    
    if(isset($_POST["delete"])){
        $user = $dao->get("User", $userContact->contactId, 'contactId');
        $dao->delete("User", 'contactId', $userContact->contactId);
        ob_start();
        header('Location: '.'users.php');
        ob_end_flush();
        die();
    }
    
    if(isset($_POST["submit"])){
            //getting user input
            $firstName = htmlspecialchars($_POST["firstName"]);
            $lastName = htmlspecialchars($_POST["lastName"]);
            $userEmail = htmlspecialchars($_POST["userEmail"]);
            $phone = htmlspecialchars($_POST["phone"]);
            $role = htmlspecialchars($_POST["role"]);

            //checking for errors
            #$firstNameError = Validator::isName($firstName, "Firstname");
            #$lastNameError = Validator::isName($lastName, "Lastname");
            $userEmailError = Validator::isEmail($userEmail); 
            $phoneError = "";
            
            //if there are no errors
            if(strlen($firstNameError) == 0 && strlen($lastNameError) == 0 && strlen($userEmailError) == 0 && strlen($phoneError) == 0 ){

                $userContact->contactEmail = $userEmail;
                $userContact->contactFirstName = $firstName;
                $userContact->contactLastName = $lastName;
                $userContact->contactPhone = $phone;
                if($role == 4){
                    $tutor = new Tutor();
                    $tutor->clientId = $client->clientId;
                    $tutor->contactId = $userContact->contactId;
                    $tutor->tutorStatusId = 1;
                    $dao->add($tutor);
                }else{
                    $tutor = $dao->get("Tutor", $userContact->contactId, "contactId");
                    if($tutor->contactId != null){
                        $dao->delete("Tutor", "contactId", $userContact->contactId);
                    }
                }
                $userC->roleId = $role;
                $dao->update($userC);
                $dao->update($userContact);
                
                ob_start();
                header('Location: '.'users.php');
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
    <body style="background: #f7f7f7;">
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="settingswrapper">
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec" style="border: none;">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Update User")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("First Name")) ?></label>
                                    <input value="<?php echo $userContact->contactFirstName ?>" class="col6" type="text" name="firstName">
                                    <p class="error"><?php echo $firstNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Last Name")) ?></label>
                                    <input value="<?php echo $userContact->contactLastName ?>" class="col6" type="text" name="lastName">
                                    <p class="error"><?php echo $lastNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Email")) ?></label>
                                    <input value="<?php echo $userContact->contactEmail ?>" class="col6" type="text" name="userEmail">
                                    <p class="error"><?php echo $userEmailError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Phone")) ?></label>
                                    <input value="<?php echo $userContact->contactPhone ?>" class="col6" type="text" name="phone">
                                    <p class="error"><?php echo $phoneError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Role")) ?></label>
                                    
                                    <select name="role">
                                        <?php 
                                            foreach($roles as $r){
                                                if($r->roleId == $userC->roleId){
                                                    echo "<option selected value=\"".$r->roleId."\">".$r->roleName."</option>";
                                                }else{
                                                    echo "<option value=\"".$r->roleId."\">".$r->roleName."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                    
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <button type="submit" name="submit" value="submit" class="widget left"><?php echo($dic->translate("Update")) ?></button>
                        <button id="resetButton" class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                        <button type="submit" name="delete" value="delete" class="rwidget widget"><?php echo($dic->translate("Delete")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 70px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>