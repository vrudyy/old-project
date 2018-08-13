<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Sort.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/old/createVCard.php");
    
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
    
    $contacts = $dao->listAll("Contact", "clientId", $client->clientId, "ASC", "contactLastName");
    
    exportContacts($contacts);
    
    $con = [];
    $availableLetters = "";
    for($j = 0; $j<sizeof($contacts); $j++){
        if( strpos($availableLetters, substr($contacts[$j]->contactLastName, 0, 1) ) === false ){
            $availableLetters .= substr($contacts[$j]->contactLastName, 0, 1);
        }
    }
    
    if(isset($_GET["letter"])){
        $letter = $_GET["letter"];
        foreach($contacts as $c){
            $firstCharacter = strtoupper(substr($c->contactLastName, 0, 1));
            if(strcmp($letter, $firstCharacter) == 0){
                array_push($con, $c);
            }
        }
        $contacts = $con;
    }
    
    $per_page = 25;
    if(isset($_GET["perpage"])){
        $per_page = $_GET["perpage"];
    }
    
    $pp = [];
    $pc = [];
    
    
    
    for($j = 0; $j<sizeof($contacts); $j++){
        
        
        array_push($pc, $contacts[$j]);
        if(sizeof($pc) == $per_page || $j+1 == sizeof($contacts)){
            array_push($pp, $pc);
            $pc = [];
        }
    }

    $contacts = $pp[0];
    if(isset($_GET["page"])){
        $contacts = $pp[$_GET["page"]-1];
    }
    
    //closing connection;
    $dao->close();
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
    //having all of the
   $alphabet = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
   
    
    
    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        <link href="css/gstyle.css" rel="stylesheet" type="text/css"/>
        <style>
            .search ul .dis{
                pointer-events: none;
                color: gray;
            }
            .search{
                clear: both;
                padding: 1px 0;
            }
            .search ul button{
                color: #2c89ba;
                float: left;
                height: 40px;
                line-height: 40px;
                margin: 0 10px;
                outline: none;
                font-size: 21px;
            }
            .search ul button:hover{
                color: #96beff;
            }
            .search ul{
                clear: both;
            }
            .search > .row{
                margin: 10px 1% 0 1%;
                height: 40px;
                line-height: 40px;
            }
            .pagination{
                min-height: 50px;
                margin: 0 1% 15px 1%;
            }
            .pagination form{
                float: left;
                height: 50px;
                line-height: 50px;
                padding: 0 15px;
            }
            .pagination select{
                height: 30px;
            }
            .pagination p{
                float: left;
                height: 50px;
                line-height: 50px;
                font-size: 15px;
                color: #2c89ba;
                padding-right: 15px;
            }
            .pagination ul button{
                background: #2c89ba;
                padding: 6px 10px;
            }
            .pagination ul button:hover{
                background: #96beff;
            }
            .pagination ul form{
                padding: 0;
            }
            #selected{
                background: #96beff;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="home-title in-row">
                    <h3><?php echo $dic->translate("Contacts") ?></h3>
                    <form action="addcontact.php" method="post">
                        <button type="submit"><?php echo $dic->translate("Add Contact") ?></button>
                    </form>
                    <form action="<?php echo("clients/$client->clientId/contacts/vcards/allcontacts.vcf"); ?>" method="post">
                        <button type="submit" name="in-contacts-export-all" value="in-contacts-export-all"><?php echo $dic->translate("Export Contacts") ?></button>
      
                    </form>
                </div>
                <div class="search row">
                    <div class="vsec row">
                        <ul>
                            <?php 
                                
                                echo "<li><form action=\"contacts.php\" method=\"post\">";
                                echo ((isset($_GET["perpage"]))? "<input type=\"hidden\" name=\"perpage\" value=\"".($_GET["perpage"])."\">" : "");
                                echo "<button style=\"margin-right: 10px;\">".$dic->translate("Show all")."</button></form></li>";
                                foreach($alphabet as $l){
                                    echo "<li><form method=\"get\">";
                                    echo ((isset($_GET["perpage"]))? "<input type=\"hidden\" name=\"perpage\" value=\"".($_GET["perpage"])."\">" : "");
                                    echo ((isset($_GET["page"]))? "<input type=\"hidden\" name=\"page\" value=\"1\">" : "");
                                    echo "<input type=\"hidden\" name=\"letter\" value=\"$l\"/><button";
                                    if(strpos($availableLetters, $l) === false){
                                        echo ' class="dis"';
                                    }
                                    echo ">$l</button></form></li>";
                                }
                                
                            ?>
                        </ul>
                    </div>
                    <div style="clear: both;"></div>
                </div>
                <div class="in-row vsec cards" style="width:98%; margin: 20px 1%; padding: 10px;">
                    <?php
                    foreach($contacts as $c){
                        echo '<div class="in-card-wrapper col3">';
                            echo '<div class="in-card in-row">';
                                echo '<div class="in-card-title col10">';
                                    echo '<form method="post" action="contact.php">';
                                        echo '<input type="hidden" name="contactid" value="'.$c->contactId.'" />';
                                        echo '<button type="submit">'.$c->contactFirstName.' '.$c->contactLastName.'</button>';
                                    echo '</form>';
                                echo '</div>';
                                echo '<i class="in-card-icon fas fa-user col2"></i>';
                                echo '<div class="col10 in-card-details">';
                                    echo '<ul class="in-row">';
                                        echo '<li class="in-row"><span>'.$dic->translate("Email").':</span>'.$c->contactEmail.'</li>';
                                        echo '<li class="in-row"><span>'.$dic->translate("Phone").':</span>'.$c->contactPhone.'</li>';
                                    echo '</ul>';
                                echo '</div>';
                                echo '<div class="col2 in-card-edit">';
                                    echo '<form action="editcontact.php" method="post" class="in-row in-card-edit">';
                                        echo '<input type="hidden" name="contactid" value="'.$c->contactId.'" />';
                                        echo '<button type="submit"><i style="font-size:14px;" class="fas fa-pencil-alt"></i></button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    } 
                    ?>
                </div>
               
            </div>
            <div class="in-row vsec pagination">
                <form>
                    <select id="num-per-page" name="num-per-page">
                        <option <?php echo ($per_page == 25) ? "selected" : ""?> value="25">25</option>
                        <option <?php echo ($per_page == 50) ? "selected" : ""?> value="50">50</option>
                        <option <?php echo ($per_page == 100) ? "selected" : ""?> value="100">100</option>
                    </select>
                </form>
                <p>per page</p>
                <ul>
                    <?php
                    
                        if(sizeof($pp)> 9){
                            echo (($_GET["page"] > 1)? "<li>": '<li style="pointer-events: none;">');
                            echo "<form method=\"get\">";
                            echo ((isset($_GET["perpage"]))? "<input type=\"hidden\" name=\"perpage\" value=\"".($_GET["perpage"])."\">" : "");
                            echo ((isset($_GET["letter"]))? "<input type=\"hidden\" name=\"letter\" value=\"".($_GET["letter"])."\">" : "");
                            echo ((isset($_GET["page"]))? "<input type=\"hidden\" name=\"page\" value=\"".($_GET["page"] - 1)."\">" : "");
                            echo '<button type="submit" style="color:white;">';
                            echo $dic->translate("prev");
                            echo "</button>";
                            echo "</form>";
                            echo "</li>";
                        }
                        if(sizeof($pp) > 9){
                            $start = ($_GET["page"] >= 5) ? (($_GET["page"] >= (sizeof($pp)-4))? sizeof($pp) - 9 :($_GET["page"] - 5)) : 0;
                            $end = ($_GET["page"] <= 5) ? 9 : ((($_GET["page"]+4)>sizeof($pp)) ? sizeof($pp) : $_GET["page"] + 4);
                            for($v = $start; $v<$end;$v++){
                                echo "<li><form method=\"get\">";
                                echo ((isset($_GET["perpage"]))? "<input type=\"hidden\" name=\"perpage\" value=\"".($_GET["perpage"])."\">" : "");
                                echo ((isset($_GET["letter"]))? "<input type=\"hidden\" name=\"letter\" value=\"".($_GET["letter"])."\">" : "");

                                echo "<input type=\"hidden\" name=\"page\" value=\"".($v+1)."\"><button style=\"color:white;\"";
                                if(isset($_GET["page"]) && $_GET["page"] == $v+1){
                                    echo "id=\"selected\"";
                                }
                                echo ">".($v+1)."</button></form></li>";
                            }
                        }else{
                            for($v = 0; $v<sizeof($pp);$v++){
                            echo "<li><form method=\"get\">";
                            echo ((isset($_GET["perpage"]))? "<input type=\"hidden\" name=\"perpage\" value=\"".($_GET["perpage"])."\">" : "");
                            echo ((isset($_GET["letter"]))? "<input type=\"hidden\" name=\"letter\" value=\"".($_GET["letter"])."\">" : "");
                            
                            echo "<input type=\"hidden\" name=\"page\" value=\"".($v+1)."\"><button style=\"color:white;\" ";
                            if(isset($_GET["page"]) && $_GET["page"] == $v+1){
                                echo "id=\"selected\"";
                            }
                            echo ">".($v+1)."</button></form></li>";
                            }
                        }
                        if(sizeof($pp)> 9){
                            echo (($_GET["page"] < sizeof($pp))? "<li>": '<li style="pointer-events: none;">');
                            echo "<form method=\"get\">";
                            echo ((isset($_GET["perpage"]))? "<input type=\"hidden\" name=\"perpage\" value=\"".($_GET["perpage"])."\">" : "");
                            echo ((isset($_GET["letter"]))? "<input type=\"hidden\" name=\"letter\" value=\"".($_GET["letter"])."\">" : "");
                            echo ((isset($_GET["page"]))? "<input type=\"hidden\" name=\"page\" value=\"".($_GET["page"] + 1)."\">" : "");
                            echo '<button type="submit" style="color:white;">';
                            echo $dic->translate("next");
                            echo "</button>";
                            echo "</form>";
                            echo "</li>";
                        }
                    ?>
                </ul>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
        <script>
            $select = document.getElementById("num-per-page");
            $select.addEventListener("change", function(e){
                window.location.replace("http://i-nucleus.com/main/contacts.php?perpage="+$select.value);
            });
            
        </script>
    </body>
</html>