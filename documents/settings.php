<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    
    //checking if the user is logged in
    session_start();
     if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //intialisng the DAO
    $dao = new DAO();
    
    $email = $_SESSION["email"]; 
    
    //setting up the contact and client object for account owner
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    //setting up the dictionary
    $dic = new Dictionary($client->clientLanguage);
    
    //closing DAO
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
                <div class="settings">
                    <div class="my_company in-row" style="border-bottom: 1px solid gray;"> 
                        <div class="col3">
                            <h3>
                                <?php echo($dic->translate("My Company")) ?>
                            </h3>
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3>
                                    <a href="details.php"><?php echo($dic->translate("Company Details")) ?></a>
                                </h3>
                                <p><?php echo($dic->translate("Edit your address details, contact and other company information.")) ?></p>
                            </div>
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3><a href="logo.php"><?php echo($dic->translate("Company Logo")) ?></a></h3>
                                <p><?php echo($dic->translate("Upload or change your company logo.")) ?></p>
                            </div>
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3><a href="users.php"><?php echo($dic->translate("Users")) ?></a></h3>
                                <p><?php echo($dic->translate("Add and manage users, passwords and control user access levels.")) ?></p>
                            </div>
                        </div>
                        <div class="col3">
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3><a href="branches.php"><?php echo($dic->translate("Branches")) ?></a></h3>
                                <p><?php echo($dic->translate("Add and manage company's branches.")) ?></p>
                            </div>
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3><a href="marketingchannels.php"><?php echo($dic->translate("Marketing Channels")) ?></a></h3>
                                <p><?php echo($dic->translate("Add and manage the marketing channels too see the efficiency of your sales.")) ?></p>
                            </div>
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3><a href="emailtemplates.php?sec=invoice"><?php echo($dic->translate("Email Templates")) ?></a></h3>
                                <p><?php echo($dic->translate("Manage the templates when sending an email.")) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="in-row academic" style="border-bottom: 1px solid gray;">
                        <div class="col3">
                            <h3>
                                <?php echo($dic->translate("Academic")) ?>
                            </h3>
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3><a href="schoolyears.php"><?php echo($dic->translate("School Years")) ?></a></h3>
                                <p><?php echo($dic->translate("Create and edit the school years and manage the periods within a school year.")) ?></p>
                            </div>
                        </div>
                        <div class="col3">
                            <div class="in-row subsection">
                                <h3><a href="educationlevels.php"><?php echo($dic->translate("Education Levels")) ?></a></h3>
                                <p><?php echo($dic->translate("Add the levels of your educational structure.")) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                 <div style="clear:both;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>