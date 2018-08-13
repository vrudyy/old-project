<?php

$dao = new DAO();
$student = new Student();
$student = $dao->get("Student", $_POST["studentId"], "studentId");



$studentExamPerformances = $dao->listAll("StudentExamPerformance", "studentId", $student->studentId);

?>
<div id="class-record-wrapper" class="in-row in-class-cr-header-wrapper">
    <div class="col2 otk"><?PHP echo($dic->translate("Exam Date"))?></div>
    <div class="col2 otk"><?PHP echo($dic->translate("Qualification"))?></div>
    <div class="col2 otk"><?PHP echo($dic->translate("Exam Predicted Result"))?></div>
    <div class="col2 otk"><?PHP echo($dic->translate("Exam Actual Result"))?></div>
    <div class="col4 otk"><?PHP echo($dic->translate("Exam Comments"))?></div>
    <?PHP
    foreach($studentExamPerformances as $key => $value){
        #$value = new StudentExamPerformance();
        #$qualification = new Qualification();
        $qualification = $dao->get("Qualification", $value->qualificationId, "qualificationId");
        
        $date = new Date();
        
        if($key % 2 == 0){
            $style = "background:#e8f0ff;color:black;font-size: 14px;";
        }else{
            $style = "background:white;color:black;font-size: 14px;";
        }
        $examDate = $date->fromDBToInput($value->studentExamPerformanceDate);
        
    ?>
    <div class="in-row" style="<?PHP echo($style)?>">
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($examDate)?></div>
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($qualification->qualificationName)?></div>
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($value->studentExamPerformancePredicted)?></div>
        <div style="<?PHP echo($style)?>" class="col2"><?PHP echo($value->studentExamPerformanceAchieved)?></div>
        <div style="padding-left: 10px;text-align: left; <?PHP echo($style)?>" class="col4"><?PHP echo($value->studentExamPerformanceComments)?></div>
    </div>
    <?PHP
    }
    ?>
    
</div>

        