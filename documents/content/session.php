<?php

session_start();
if(!isset($_SESSION["email"])){
    ob_start();
    header('Location: '.'login.php');
    ob_end_flush();
    die();
}

$contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
$user = $dao->get("User", $contact->contactId, "contactId");
$client = $dao->get("Client", $user->clientId, "clientId");
$dic = new Dictionary($client->clientLanguage);