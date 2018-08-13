<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Address.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/old/createVCard.php");
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
    
    $con = $dao->get("Contact", $_POST["contactid"], "contactId");
    
    createVCard($con);
    $vcardNum = 13579 + $con->contactId;
    
    if(isset($_POST["delete-note"])){
        $noteId = $_POST["noteid"];
        $dao->delete("Note", "noteId", $noteId);
    }
    
    if(isset($_POST["submit"])){
        
        $text = htmlspecialchars($_POST["text"]);
        $textError = Validator::isEmpty($text);
        if(Validator::check($textError)){
            $note = new Note();
        
            $note->noteId = $dao->next("Note", "noteId");

            $note->noteDate = date("Y-m-d H:i:s");

            $note->noteAuthor = $contact->contactId;
            $note->noteText = $text;
            $note->noteContact = $con->contactId;
            $dao->add($note);
            
        }
    }
    
    //getting all the notes for this user
    $notes = $dao->listAll("Note", "noteContact", $con->contactId);
    
    
    
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
    //echo $_POST["contactid"];
    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        <link href="css/contact.css" rel="stylesheet" type="text/css"/>
        <style>
            #in-vcard-sec > div > div > div > p:nth-child(3){
                margin: 10px 0;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="home-title in-row">
                    <h3><?php echo($dic->translate("Contact")." :: ".$con->contactFirstName." ".$con->contactLastName) ?></h3>
                </div>
                <div class="in-container">
                    
                    
                    <div class="col8" id="in-notes-sec">
                        <div class="add-note">
                            <div class="add-form">
                                <h3><?php echo($dic->translate("Add a Note"))?></h3>
                                <form method="post">
                                    <textarea rows="4" cols="50" name="text"></textarea>
                                    <input type="hidden" name="contactid" value="<?php echo($con->contactId)?>">
                                    <button type="submit" value="submit" name="submit"><?php echo($dic->translate("Add Note"))?></button>
                                </form>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                        <div class="in-notes">
                            <?php 
                                for($j = sizeof($notes)-1; $j>=0; $j--){
                                    $author = $dao->get("Contact", $notes[$j]->noteAuthor, "contactId");
                                    echo '<div class="in-note">';
                                    echo '<div class="in-note-text">';
                                    echo $notes[$j]->noteText;
                                    echo '</div>';
                                    echo '<div class="in-note-footer">';
                                    echo '<p>';
                                    echo $dic->translate("Added by ");
                                    echo $author->contactFirstName . " " . $author->contactLastName;
                                    echo ' on ';
                                    $date = date_create($notes[$j]->noteDate);
                                    $date = date_format($date, "l jS F Y H:i:s");
                                    echo $date;
                                    echo '</p>';
                                    echo '<form method="post" action="contact.php">';
                                    echo '<input type="hidden" name="contactid" ';
                                    echo 'value="';
                                    echo $con->contactId;
                                    echo '"/>';
                                    echo "<input type=\"hidden\" name=\"noteid\" value=\"".$notes[$j]->noteId."\"/>";
                                    echo '<button type="submit" name="delete-note" value="delete-note">'.$dic->translate("Delete").'</button>';
                                    echo '</form>';
                                    echo '<div style="clear:both;"></div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            //closing connection;
                            $dao->close();
                            ?>        
                        </div>
                    </div>
                    
                    
                    
                    <div class="col4" id="in-vcard-sec">
                        <div class="in-contact-card">
                            <h3>
                                <?php
                                
                                    if( strlen($con->contactOrganisation) == 0 ){
                                        echo $con->contactFirstName . " " . $con->contactLastName;
                                    }else{
                                        echo $con->contactOrganisation;
                                    }
                                ?>
                            </h3>
                            <div class="in-contact-card-info">
                                <div>
                                    <?php 
                                        if( strlen($con->contactOrganisation) != 0 ){
                                            echo "<p>".$con->contactFirstName . " " . $con->contactLastName."</p>";
                                        }
                                        $address = new Address();
                                        $address->convertToObject($con->contactAddress);

                                        echo ( (strlen($address->firstline)!=0)? '<p>'.$address->firstline.'</p>' : "");
                                        echo ( (strlen($address->secondline)!=0)? '<p>'.$address->secondline.'</p>' : "");
                                        echo ( (strlen($address->thirdline)!=0)? '<p>'.$address->thirdline.'</p>' : "");
                                        echo ( (strlen($address->town)!=0)? '<p>'.$address->town.'</p>' : "");
                                        echo ( (strlen($address->zip)!=0)? '<p>'.$address->zip.'</p>' : "");
                                        echo ( (strlen($address->country)!=0)? '<p>'.$address->country.'</p>' : "");
                                    ?>

                                    <p><a href="mailto: <?php echo($con->contactEmail)?>"><?php echo($con->contactEmail)?></a></p>
                                    <h3><?echo $dic->translate("Phone")?></h3>
                                    <p><?php echo($con->contactPhone)?></p>
                                </div>
                                <form method="post" action="clients/<?php echo($client->clientId); ?>/contacts/vcards/<?php echo("$vcardNum");?>.vcf">
                                    <input type="hidden" name="contactid" value="<?php echo($_POST["contactid"]); ?>">
                                    <button type="submit" name="create-vcard" value="create-vcard"><?echo $dic->translate("Download")." vCard"?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>