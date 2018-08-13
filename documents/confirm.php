<?php
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");


$cisid = $_GET["cisid"];
$id = $_GET["id"];

$dao = new DAO();

$client = $dao->get("Client", $cisid, "clientId");
$contact = $dao->get("Contact", $client->contactId, "contactId");
$user = $dao->get("User", $contact->contactId, "contactId");

if(strcmp($id, md5($client->clientCompanyName))==0){
    $user->userActivate = 'y';
    $dao->update($user);
    ob_start();
    header('Location: '.'http://i-nucleus.com/main/thank-you.php');
    ob_end_flush();
    die();
}





$dao->close();
