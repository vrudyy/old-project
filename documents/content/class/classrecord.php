<?php

/*
 * 
 * HERE IS THE TABLE HEADERS
 * 
 */
#$dao = new DAO();
$classRecords = $dao->listAll("ClassRecord", "classId", $class->classId);


?>
<div id="class-record-wrapper" class="in-row in-class-cr-header-wrapper">
    <div class="col2 otk"><?PHP echo($dic->translate("Class Date"))?></div>
    <div class="col1 otk"><?PHP echo($dic->translate("Class Location"))?></div>
    <div class="col2 otk"><?PHP echo($dic->translate("Class Tutor"))?></div>
    <div class="col1 otk"><?PHP echo($dic->translate("Class Hours"))?></div>
    <div class="col3 otk"><?PHP echo($dic->translate("Syllabus Taught"))?></div>
    <div class="col3 otk"><?PHP echo($dic->translate("Homework Assigned"))?></div>
    <?PHP
    foreach($classRecords as $key => $cr){
        $date = new Date();
        #$cr = new ClassRecord();
        if($cr->classRecordRoom != 0){
            $room = $dao->get("Room", $cr->classRecordRoom, "roomId");
        }else{
            $room = new Room();
            $room->roomName = "Not Available";
        }
        $tutor = $dao->get("Tutor", $cr->classRecordTutor, "tutorId");
        $tutorContact = $dao->get("Contact", $tutor->contactId, "contactId");
        if($key % 2 == 0){
            $style = "background:#e8f0ff;color:black;font-size: 14px;";
        }else{
            $style = "background:white;color:black;font-size: 14px;";
        }
        $date->fromInput($cr->classRecordDate);
        $date = $date->longDate();
        
    ?>
    <div class="in-row" style="<?PHP echo($style)?>">
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($date)?></div>
        <div style="<?PHP echo($style)?>" class="col1"><?PHP echo($room->roomName)?></div>
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($tutorContact->fullName())?></div>
        <div style="<?PHP echo($style)?>" class="col1"><?PHP echo($cr->classRecordHours)?></div>
        <div style="display:block;text-align: left;<?PHP echo($style)?>" class="col3"><?PHP echo($cr->classRecordSyllabus)?></div>
        <div style="display:block;text-align: left;<?PHP echo($style)?>" class="col3"><span style="width: 80%; display: block; "><?PHP echo($cr->classRecordHomework)?></span><form style="float: right;" method="post" action="editclassrecord.php"><input type="hidden" name="classRecordId" value="<?PHP echo($cr->classRecordId)?>" /><button><i class="fas fa-pencil-alt"></i></button></form></div>
    </div>
    <?PHP
    }
    ?>
    
</div>

        