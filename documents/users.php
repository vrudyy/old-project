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
    
    $dao = new DAO();
    
    $roles = $dao->listAll("Role");
    
    $accEmail = $_SESSION["email"]; 
    
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    
    $users = [];
    $u = $dao->listAll("User", "clientId", $client->clientId);
    
    
    foreach($dao->listAll("User", "clientId", $client->clientId) as $user){
        if($user->clientId == $client->clientId){
            array_push($users, $dao->get("Contact", $user->contactId, "contactId"));
        }
    }
    
    unset($u[0]);
    unset($users[0]);
    
    //initialising the dictionary
    $dic = new Dictionary($client->clientLanguage);
    
    //closing database connection
    $dao->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        <style>
            .content > div {
                font-size: 15px;
            }
            .addUserHeadings{
                margin: 20px 0 0 0;
                color: white;
            }
            .addUserHeadings > div{ 
                background: #579acc;
            }
            .mg > div{
                background: #e8f0ff;
                height: 41.8px;
                border-bottom: 1px solid gray;
            }
            .ug > div{
                background: white;
                border-bottom: 1px solid gray;
                height: 41.8px;
            }
            .content > h3 > a{
                margin: 2.5px 20px 0 0;
                height: 45px;
                line-height: 45px;
                display: block;
                float: right;
                padding: 0 10px;
                text-align: center;
            }
            button{
                color: #2c89ba;
                outline: none;
                text-decoration: none;
                font-size: 15px;
                font-weight: normal;
            }


        </style>
    </head>
    <body style="background: #f7f7f7;">
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <h3 style="height: 50px;line-height: 50px;background: #dddddd;padding: 0 0 0 25px;border-bottom: 1px solid gray;clear: both;">
                    <?php echo($dic->translate("Users")) ?>
                    <a class="widget" href="newuser.php"><?php echo($dic->translate("New User")) ?></a>
                </h3>
                <div style="width: 90%; margin: auto;">
                    <div class="in-row addUserHeadings">
                        <div class="col3">
                            <?php echo($dic->translate("Name")) ?>
                        </div>
                        <div class="col1">
                            <?php echo($dic->translate("Role")) ?>
                        </div>
                        <div class="col3">
                            <?php echo($dic->translate("Email")) ?>
                        </div>
                        <div class="col2">
                            <?php echo($dic->translate("Phone")) ?>
                        </div>
                        <div class="col3">
                            <?php echo($dic->translate("Last Login")) ?>
                        </div>
                    </div>
                    <div class="in-row mg">
                        <div class="col3">
                            <?php 
                                echo '<form action="profdetails.php" method="post">';
                                echo '<button type="submit">';
                                echo($contact->contactFirstName." ".$contact->contactLastName); 
                                echo '</button>';
                                echo '<span class="gnotif">  (Account Owner)</span>';
                                echo '</form>';
                            ?>
                        </div>
                        <div class="col1">
                            <?php echo($roles[0]->roleName) ?>
                        </div>
                        <div class="col3">
                            <?php echo($contact->contactEmail) ?>
                        </div>
                        <div class="col2">
                            <?php echo($contact->contactPhone) ?>
                        </div>
                        <div class="col3" style="font-size: 12px;">
                            <?php 
                                $date = date_create($contact->contactLoggedIn);
                                $date = date_format($date, "l jS F Y H:i:s");
                                echo($date);
                            ?>
                        </div>
                    </div>
                    <?php for($i = 1; $i<sizeof($users)+1;$i++){ ?>
                    
                        <div class="in-row <?php echo(($i%2==0)?"ug":"mg"); ?>">
                            <div class="col3">
                                <form action="updateuser.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $users[$i]->contactId ?>">
                                    <button type="submit">
                                        <?php echo($users[$i]->contactFirstName." ".$users[$i]->contactLastName) ?>
                                    </button>
                                </form>
                            </div>
                            <div class="col1">
                                <?php 
                                    $role = $dao->get("Role", $u[$i]->roleId, "roleId");
                                    echo($role->roleName); 
                                ?>
                            </div>
                            <div class="col3">
                                <?php echo($users[$i]->contactEmail) ?>
                            </div>
                            <div class="col2">
                                <?php echo($users[$i]->contactPhone) ?>
                            </div>
                            <div class="col3" style="font-size: 12px;">
                                <?php 
                                    if(!empty($users[$i]->contactLoggedIn)){
                                        $date = date_create($users[$i]->contactLoggedIn);
                                        $date = date_format($date, "l jS F Y H:i:s");
                                        echo($date);   
                                    }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div style="clear: both; height: 100px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
        <?php
            if(isset($_SESSION["popup"])){
                echo '<div id="popup">'.$dic->translate("The user has been added").'</div>';
                unset($_SESSION["popup"]);
            }
            
        ?>
        <script src="javascript/hide.js"></script>
    </body>
</html>