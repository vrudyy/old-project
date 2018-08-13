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
    
    $managers = [$contact];
    $secretaries = [];
    $contacts = [];
    $users = $dao->listAll("User", "clientId", $client->clientId);
    foreach($users as $u){
        array_push($contacts, $dao->get("Contact", $u->contactId, "contactId"));
    }
    foreach($contacts as $c){
        if($c->roleId == 1){
            array_push($managers, $c);
        }
        if($c->roleId == 2){
            array_push($secretaries, $c);
        }
    }
    
    //setting up input values
    $branchName = "";
    $firstline = "";
    $secondline = "";
    $thirdline = "";
    $town = "";
    $region = "";
    $zip = "";
    $branchPhone = "";
    
    //setting up error messages
    $branchNameError = "";
    $firstlineError = "";
    $secondlineError = "";
    $thirdlineError = "";       
    $townError = "";        
    $regionError = "";        
    $zipError = "";       
    $branchPhoneError = "";
    
    //if the user press submit
    if(isset($_POST["submit"])){
        //get input data
        $branchName = htmlspecialchars($_POST["branchName"]);
        $firstline = htmlspecialchars($_POST["firstline"]);
        $secondline = htmlspecialchars($_POST["secondline"]);
        $thirdline = htmlspecialchars($_POST["thirdline"]);
        $town = htmlspecialchars($_POST["town"]);
        $region = htmlspecialchars($_POST["region"]);
        $zip = htmlspecialchars($_POST["zip"]);
        $branchPhone = htmlspecialchars($_POST["branchPhone"]);
        $branchEmail = htmlspecialchars($_POST["branchEmail"]);
        $manager = htmlspecialchars($_POST["manager"]);
        $secretary = htmlspecialchars($_POST["secretary"]);
        
        //validate input data
        $branchNameError = Validator::isName($branchName, "Branch Name");
        $firstlineError = Validator::isEmpty($firstline, "Address");
        $secondlineError = "";
        $thirdlineError = "";       
        $townError = Validator::isEmpty($town, "Town");        
        $regionError = "";        
        $zipError = Validator::isEmpty($zip, "Post/Zip Code");       
        $branchPhoneError = Validator::isEmpty($branchPhone, "Phone number");
        $branchEmailError = Validator::isEmpty($branchEmail, "Branch Email");
        
        //if there are no errors
        if(strlen($branchNameError) == 0 && strlen($firstlineError) == 0 && strlen($townError) == 0 && strlen($zipError) == 0 && strlen($branchPhoneError) == 0 && strlen($branchEmailError) == 0 ){
            $branch = new Branch();
            $branch->branchId = $dao->next("Branch", "branchId");
            $branch->branchName = $branchName;
            
            $address = new Address();
            $address->firstline = $firstline;
            $address->secondline = $secondline;
            $address->thirdline = $thirdline;
            $address->town = $town;
            $address->region = $region;
            $address->zip = $zip;
            
            $branch->branchAddress = $address->convertToDB();
            $branch->branchActive = "1";
            $branch->branchPhone = $branchPhone;
            $branch->branchEmail = $branchEmail;
            $branch->clientId = $client->clientId;
            $branch->managerId = $manager;
            $branch->secretaryId = $secretary;
            
            $dao->add($branch);
            ob_start();
            header('Location: '.'branches.php');
            ob_end_flush();
            die();
        }
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
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec" style="border: none;">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Branch Details")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Branch Name")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $branchName ?>" class="col6" type="text" name="branchName">
                                    <p class="error"><?php echo $branchNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Branch Address")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $firstline ?>" class="col6" type="text" name="firstline">
                                    <p class="error"><?php echo $firstlineError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"></label>
                                    <input value="<?php echo $secondline ?>" class="col6" type="text" name="secondline">
                                    <p class="error"><?php echo $secondlineError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"></label>
                                    <input value="<?php echo $thirdline ?>" class="col6" type="text" name="thirdline">
                                    <p class="error"><?php echo $thirdlineError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Town")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $town ?>" class="col6" type="text" name="town">
                                    <p class="error"><?php echo $townError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Region or State")) ?></label>
                                    <input value="<?php echo $region ?>" class="col6" type="text" name="region">
                                    <p class="error"><?php echo $regionError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Post/Zip Code")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $zip ?>" class="col6" type="text" name="zip">
                                    <p class="error"><?php echo $zipError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Branch Phone")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $branchPhone ?>" class="col6" type="text" name="branchPhone">
                                    <p class="error"><?php echo $branchPhoneError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Branch Email")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $branch->branchEmail ?>" class="col6" type="text" name="branchEmail">
                                    <p class="error"><?php echo $branchEmailError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Manager")) ?></label>
                                    
                                    <select name="manager">
                                        <?php 
                                           
                                            foreach($managers as $m){
                                                echo "<option value=\"".$m->contactId."\">".$m->contactFirstName." ".$m->contactLastName."</option>";
                                            }
                                        ?>
                                    </select>
                                    
                                    <p class="error"></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Secretary")) ?></label>
                                    
                                    <select name="secretary">
                                        <?php 
                                            echo "<option>".$dic->translate("Not Available")."</option>";
                                            foreach($secretaries as $s){
                                                echo "<option value=\"".$s->contactId."\">".$s->contactFirstName." ".$s->contactLastName."</option>";
                                            }
                                        ?>
                                    </select>
                                    
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <button name="submit" value="submit" type="submit" class="widget left"><?php echo($dic->translate("Add Branch")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 120px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>