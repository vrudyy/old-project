<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
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
    $branch = $dao->get("Branch", $_GET["branchid"], "branchId");
    
    if($branch->clientId != $client->clientId){
        ob_start();
        header('Location: '.'branches.php');
        ob_end_flush();
        die();
    }
    
    
    
    $rooms = $dao->listAll("Room", "branchId", $branch->branchId);
    
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
                <div class="home-title in-row">
                    <h3><?php echo $branch->branchName ?></h3>
                    <form action="newroom.php" method="post">
                        <input type="hidden" name="branchid" value="<?php echo $branch->branchId ?>"/>
                        <button type="submit"><?php echo $dic->translate("New Room") ?></button>
                    </form>
                </div>
                <div class="in-row vsec" style="border-radius: 0;width: 90%;margin: 20px 5%;">
                    <h3 class="rooms" style="margin-bottom: 0;"><?php echo $dic->translate("Rooms") ?></h3>
                    <div class="room in-row roomHead">
                        <h3 class="col6 r b"><?php echo $dic->translate("Room Name") ?></h3>
                        <h3 class="col3 r b"><?php echo $dic->translate("Capacity") ?></h3>
                        <h3 class="col3 b"><?php echo $dic->translate("Hourly Cost") ?></h3>
                    </div>
                    <?php
                        foreach($rooms as $key => $r){
                            if($key % 2 ==0) {
                                echo "<div class=\"room in-row mg\">";
                            }else{
                                echo "<div class=\"room in-row ug\">"; 
                            }
                            echo "<form action=\"updateroom.php\" method=\"post\" class=\"col6 b r\">";
                            echo "<input type=\"hidden\" name=\"roomid\" value=\"$r->roomId\">";
                            echo "<input type=\"hidden\" name=\"branchid\" value=\"$branch->branchId\">";
                            echo "<button type=\"submit\">";    
                            echo $r->roomName;    
                            echo "</button>";        
                            echo "</form>"; 
                            //echo "<h3 class=\"col6 r b\">".$r->roomName."</h3>";
                            echo "<h3 class=\"col3 r b\">".$r->roomCapacity."</h3>";
                            echo "<h3 class=\"col3 b\">".$r->roomHourCost."</h3>";
                            echo "</div>";
                        }
                    ?> 
                </div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>