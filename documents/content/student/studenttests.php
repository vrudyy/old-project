<?php
/*
$dao = new DAO();
$student = new Student();
$student = $dao->get("Student", $_POST["studentId"], "studentId");
$studentContact = new Contact();
$studentContact = $dao->get("Contact", $student->contactId, "contactId");
$parentContact = new Contact();
$parentContact = $dao->get("Contact",$student->parentId,"contactId");
*/

$studentTests = $dao->listAll("StudentTest", "studentId", $student->studentId);

?>
<div id="class-record-wrapper" class="in-row in-class-cr-header-wrapper">
    <div class="col2 otk"><?PHP echo($dic->translate("Date"))?></div>
    <div class="col3 otk"><?PHP echo($dic->translate("Class"))?></div>
    <div class="col2 otk"><?PHP echo($dic->translate("Result"))?></div>
    <div class="col5 otk"><?PHP echo($dic->translate("Comments"))?></div>
    <?PHP
    foreach($studentTests as $key => $value){
        #$value = new StudentTest();
        #$class = new inClass();
        $class = $dao->get("inClass", $value->classId, "classId");
        
        $date = new Date();
        
        if($key % 2 == 0){
            $style = "background:#e8f0ff;color:black;font-size: 14px;";
        }else{
            $style = "background:white;color:black;font-size: 14px;";
        }
        $testDate = $date->fromDBToInput($value->studentTestDate);
        
    ?>
    <div class="in-row" style="<?PHP echo($style)?>">
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($testDate)?></div>
        <div style="<?PHP echo($style)?>" class="col3"><?PHP echo($class->classLabel)?></div>
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($value->studentTestResult)?></div>
        <div style="padding-left: 10px;text-align: left; <?PHP echo($style)?>" class="col5"><?PHP echo($value->studentTestComments)?></div>
    </div>
    <?PHP
    }
    ?>
    
</div>

        