<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
   
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
    $room = $dao->get("Room", $_POST["roomid"], "roomId");
    $roomName = $room->roomName;
    $roomCapacity = $room->roomCapacity;
    $roomHourCost = $room->roomHourCost;
    $branch = $dao->get("Branch", $_POST["branchid"], "branchId");
    
    
    
    if(isset($_POST["submit"])){
        $roomName = htmlspecialchars($_POST["roomName"]);
        $roomCapacity = htmlspecialchars($_POST["roomCapacity"]);
        $roomHourCost = htmlspecialchars($_POST["roomHourCost"]);
        
        $roomNameError = $dic->translate(Validator::isEmpty($roomName, "Room Name"));
        $roomCapacityError = $dic->translate(Validator::isEmpty($roomCapacity, "Room Capacity"));
        $roomHourCostError = $dic->translate(Validator::isEmpty($roomHourCost, "Hourly Cost"));
        
        if(strlen($roomNameError) == 0 && strlen($roomCapacityError) == 0 && strlen($roomHourCostError) == 0 ){
            
            
            $room->roomName = $roomName;
            $room->roomCapacity = $roomCapacity;
            $room->roomHourCost = $roomHourCost;
            $dao->update($room);
            
            
            ob_start();
            header('Location: '.'branch.php?branchid='.$branch->branchId);
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
                                <h3><?php echo($branch->branchName . " :: " . $room->roomName) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Room Name")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $roomName ?>" class="col6" type="text" name="roomName">
                                    <p class="error"><?php echo $roomNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Room Capacity")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $roomCapacity ?>" class="col6" type="text" name="roomCapacity">
                                    <p class="error"><?php echo $roomCapacityError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Hourly Cost")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $roomHourCost ?>" class="col6" type="text" name="roomHourCost">
                                    <p class="error"><?php echo $roomHourCostError ?></p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="roomid" value="<?php echo $room->roomId?>"/>
                        <input type="hidden" name="branchid" value="<?php echo $branch->branchId?>"/>
                        <button name="submit" value="submit" type="submit" class="widget left"><?php echo($dic->translate("Update")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 120px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>