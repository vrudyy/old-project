<?php

require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");

$dao = new DAO();
$rooms = $dao->listAll("Room", "branchId", $_GET["cknd"]);
$client = $dao->get("Client", $_GET["cknt"], "clientId");
$dic = new Dictionary($client->clientLanguage);

if($_GET["cknd"] == 0){
    echo '<option value="0">'.$dic->translate("Please select Branch first").'</option>';
}else{
    echo '<option value="0">'.$dic->translate("Please select a room").'</option>';
    foreach($rooms as $r){
        echo '<option value="'.$r->roomId.'">'.$r->roomName.'</option>';
    } 
}
    $dao->close();
?>
