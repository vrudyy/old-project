<?php

require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");

$dao = new DAO();
$date = new Date();

$schoolYearId = htmlspecialchars($_GET["schoolYearId"]);

$schoolYear = $dao->get("SchoolYear", $schoolYearId, "schoolYearId");
 
$periods = $dao->listAll("Period", "schoolYearId", $schoolYearId);

$response = "[";
$response .= '{"id": 1, "text": "'.$schoolYear->schoolYearTitle.'", "start_date": "'.$date->getNormalFromDB($schoolYear->schoolYearStart).'", "end_date": "'.$date->getNormalFromDB($schoolYear->schoolYearEnd).'"} ';
if(sizeof($periods)!=0){
     $response.=",";
}
for($i = 0; $i<sizeof($periods); $i++){
    
    if($i + 1 != sizeof($periods)){
        $response .= '{"id": '.($i+2).', "text": "'.$periods[$i]->periodTitle.'", "start_date": "'.$date->getNormalFromDB($periods[$i]->periodStart).'", "end_date": "'.$date->getNormalFromDB($periods[$i]->periodEnd).'"}, ';
    }else{
        $response .= '{"id": '.($i+2).', "text": "'.$periods[$i]->periodTitle.'", "start_date": "'.$date->getNormalFromDB($periods[$i]->periodStart).'", "end_date": "'.$date->getNormalFromDB($periods[$i]->periodEnd).'"}';
    }
 
}
$response .= "]";
$dao->close();

echo $response;
#echo '{"name": 1}';





