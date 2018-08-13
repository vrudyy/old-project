<?php

$dao = new DAO();
$student = new Student();
$student = $dao->get("Student", $_POST["studentId"], "studentId");
$studentClasses = $dao->listAll("StudentClass", "studentId", $student->studentId);
$studentContact = new Contact();
$studentContact = $dao->get("Contact", $student->contactId, "contactId");
$totalHoursAttended = 0;
$totalHoursMissed = 0;

if(isset($_POST["reset"])){
    unset($_POST["startDate"]);
    unset($_POST["endDate"]);
    unset($_POST["class"]);
}


$where = "WHERE (";
foreach($studentClasses as $key => $value){
    if($key != 0){
        if($_POST["class"] == $value->classId || $_POST["class"] == 0){
            if(strcmp($where, "WHERE (")!=0){
                $where .= ' OR ';
            }
        }
    }
    if(isset($_POST["class"]) ){
        if($_POST["class"] == $value->classId || $_POST["class"] == 0){
            $where .= '`classId` = '.$value->classId;
        }
    }else{
        $where .= '`classId` = '.$value->classId;
    }
    
    if(($key+1) == sizeof($studentClasses)){
        if(isset($_POST["startDate"]) && strcmp($_POST["startDate"],"")!=0){
            $date = new Date();
            $startDate = $_POST["startDate"];
            $where .= ") AND `classRecordDate` >= '".$date->fromInputToDB($_POST["startDate"])."'";
            if(isset($_POST["endDate"]) && strcmp($_POST["endDate"], "")!=0){
                $endDate = $_POST["endDate"];
                $where .= " AND `classRecordDate` <= '".$date->fromInputToDB($_POST["endDate"])."';";
            }else{
                $where .= ';';
            }
        }else if(isset($_POST["endDate"]) && strcmp($_POST["endDate"], "")!=0){
            $date = new Date();
            $endDate = $_POST["endDate"];
            $where .= ") AND `classRecordDate` <= '".$date->fromInputToDB($_POST["endDate"])."';";
        }else{
            $where .= ');';
        }
        
    }
}
$classRecords = $dao->listAllWhere("ClassRecord", $where);

if(sizeof($classRecords)!=0){
    $where = "WHERE `contactId` = $studentContact->contactId AND (";
    foreach($classRecords as $key => $value){
        $where .= "`classRecordId` = $value->classRecordId";
        if(($key + 1) != sizeof($classRecords)){
            $where .= " OR ";
        }else{
            $where .= ");";
        }
    }
#var_dump($classRecords);
#echo '<br><br><br>';
    $classStudent = $dao->listAllWhere("ClassStudent", $where);
}else{
    $classStudent = [];
}
#var_dump($classStudent);

$classStudentsRecordIds = [];
foreach($classStudent as $key => $value){
    #$value = new ClassStudent();
    if($value->classStudentAttendance != 3 && $value->classStudentAttendance != 5){
        array_push($classStudentsRecordIds, $value->classRecordId);
    }
}


?>
<form style="padding: 10px; background: white; border: 1px solid lightgray;" class="in-row" method="post">
    <input type="hidden" name="studentId" value="<?PHP echo($student->studentId)?>"/>
    <div class="in-row">
        <div class="col4">
            <label style="font-size: 15px;" class="col4"><?php echo($dic->translate("Start Date")) ?></label>
            <div style="padding: 5px;" class="col8 input-group input-append date" id="datepicker1">
                <input value="<?php echo $startDate ?>" type="text" class="form-control" name="startDate" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <p class="error"><?php echo $startDateError ?></p>
        </div>
        <div class="col4">
            <label style="font-size: 15px;" class="col4"><?php echo($dic->translate("End Date")) ?></label>
            <div style="padding: 5px;" class="col8 input-group input-append date" id="datepicker2">
                <input value="<?php echo $endDate ?>" type="text" class="form-control" name="endDate" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <p class="error"><?php echo $endDateError ?></p>
        </div>
    </div>
    <div class="in-row">
        <div class="col4">
            <label style="font-size: 15px;" class="col4"><?php echo($dic->translate("Class")) ?></label>
            <div class="col4" style="padding: 5px;">
                <select name="class">
                    <option value="0"><?PHP echo($dic->translate("All"))?></option>
                    <?PHP
                        foreach($studentClasses as $key => $value){
                            $selected = '';
                            #$value = new StudentClass();
                            #$class = new inClass();
                            $class = $dao->get("inClass", $value->classId, "classId");
                            if(isset($_POST["class"]) && $_POST["class"] == $value->classId){
                                $selected = "selected";
                            }
                            echo '<option '.$selected.' value="'.$class->classId.'">'.$class->classLabel.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <button style="font-weight: 500;" class="widget" type="widget" name="filter"><?PHP echo($dic->translate("Filter"))?></button>
    <button class="widget cwidget mt10" type="submit" value="reset" name="reset"><?php echo($dic->translate("Clear"))?></button>
</form>
<div class="in-row" id="class-record-wrapper">
    <div class="in-row">
        <div class="col2 otk"><?PHP echo($dic->translate("Class Date"))?></div>
        <div class="col2 otk"><?PHP echo($dic->translate("Class Label"))?></div>
        <div class="col3 otk"><?PHP echo($dic->translate("Syllabus Taught"))?></div>
        <div class="col3 otk"><?PHP echo($dic->translate("Class Location"))?></div>
        <div class="col2 otk"><?PHP echo($dic->translate("Class Hours"))?></div>
    </div> 
</div>
<?PHP
$otk = 'background: #2c89ba;
    padding: 10px 0;
    text-align:center;
    color: white;
    font-size: 16px;';

$hours_report_pdf = '<div style="background: #2c89ba;width: 100%; float: left;">';
    $hours_report_pdf .= '<div style="'.$otk.'float: left;width: 16.6%;">'.$dic->translate("Class Date").'</div>';
    $hours_report_pdf .= '<div style="'.$otk.'float: left;width: 16.6%;">'.$dic->translate("Class Label").'</div>';
    $hours_report_pdf .= '<div style="'.$otk.'float: left;width: 25%;">'.$dic->translate("Syllabus Taught").'</div>';
    $hours_report_pdf .= '<div style="'.$otk.'float: left;width: 25%;">'.$dic->translate("Class Location").'</div>';
    $hours_report_pdf .= '<div style="'.$otk.'float: left;width: 16.6%;">'.$dic->translate("Class Hours").'</div>';
$hours_report_pdf .= '</div>';

$content_type = 'box-sizing: border-box;text-align: center;padding: 10px 0;';

foreach($classRecords as $key => $value){
    #$value = new ClassRecord();
    $date = new Date();
    $class = new inClass();
    $class = $dao->get("inClass", $value->classId, "classId");
    $room = new Room();
    $room = $dao->get("Room", $value->classRecordRoom, "roomId");
    $color = '';
    if(!in_array($value->classRecordId, $classStudentsRecordIds)){
       $color = 'color:#e50404;';
       $totalHoursMissed += $value->classRecordHours;
    }else{
        $totalHoursAttended += $value->classRecordHours;
    }
    if($key % 2 == 0){
        $style = "background:#e8f0ff;font-size: 14px;";
    }else{
        $style = "background:white;font-size: 14px;";
    }
    
    $hours_report_pdf .= '<div style="'.$content_type.$style.$color.'width: 100%; float: left;">';
        $hours_report_pdf .= '<div style="'.$content_type.'float: left;width: 16.6%;">'.$date->fromDBToInput($value->classRecordDate).'</div>';
        $hours_report_pdf .= '<div style="'.$content_type.'float: left;width: 16.6%;">'.$class->classLabel.'</div>';
        $hours_report_pdf .= '<div style="'.$content_type.'float: left;width: 25%;">'.$value->classRecordSyllabus.'</div>';
        $hours_report_pdf .= '<div style="'.$content_type.'float: left;width: 25%;">'.$room->roomName.'</div>';
        $hours_report_pdf .= '<div style="'.$content_type.'float: left;width: 16.6%;">'.$dic->translate("Class Hours").'</div>';
    $hours_report_pdf .= '</div>';
    
    $time = explode('.', $value->classRecordHours);
    $hours = $time[0];
    $minutes = strlen($time[1]."") == 1 ? $time[1]."0" : $time[1];
    
    echo '<div class="in-row" style="'.$style.$color.'">';
        echo '<div style="'.$style.'" class="col2">'.$date->fromDBToInput($value->classRecordDate).'</div>';
        echo '<div style="'.$style.'" class="col2">'.$class->classLabel.'</div>';
        echo '<div style="'.$style.'" class="col3">'.$value->classRecordSyllabus.'</div>';
        echo '<div style="'.$style.'" class="col3">'.$room->roomName.'</div>';
        echo '<div style="'.$style.'" class="col2">'.$hours.':'.$minutes.'</div>';
    echo '</div>';
    $style = '';
    
}

$attendedArray = (explode(".", $totalHoursAttended));
if(strlen($attendedArray[1]) == 1){
    $attendedArray[1] = $attendedArray[1]."0";
}
if(sizeof($attendedArray)==2){
    $hours = explode(".", ($attendedArray[1] / 60))[0];
    $minutes = $attendedArray[1] - ($hours*60);
    if($minutes < 10){
        $minutes = "0".$minutes;
    }
    $totalHoursAttended = ($attendedArray[0]+$hours).":".$minutes;
}


$missedArray = (explode(".", $totalHoursMissed));
if(strlen($missedArray[1]) == 1){
    $missedArray[1] = $missedArray[1]."0";
}
if(sizeof($missedArray)==2){
    $hours = explode(".", ($missedArray[1] / 60))[0];
    $minutes = $missedArray[1] - ($hours * 60);
    if($minutes < 10){
        $minutes = "0".$minutes;
    }
    $totalHoursMissed = ($missedArray[0]+$hours).":".$minutes;
}

$hours_report_pdf .= '<div style="width: 100%;float:left;font-size: 15px; background: #efefef;">';
    $hours_report_pdf .= '<div style="width: 100%;float:left;">';
        $hours_report_pdf .= '<div style="height: 20px;width: 58.3%;float:left;"></div>';
        $hours_report_pdf .= '<div style="padding: 10px 0;text-align:center;height: 20px;width: 25%%;float:left;">'.$dic->translate("Total Hours Attended").'</div>';
        $hours_report_pdf .= '<div style="padding: 10px 0;text-align:center;width: 16.6%;float:left;">'.$totalHoursAttended.'</div>';
    $hours_report_pdf .= '</div>';
    $hours_report_pdf .= '<div style="text-align:center;width: 100%;float:left;color: #e50404;">';
        $hours_report_pdf .= '<div style="height: 20px;width: 58.3%;float:left;"></div>';
        $hours_report_pdf .= '<div style="padding: 10px 0;text-align:center;width: 25%%;float:left;">'.$dic->translate("Total Hours Missed").'</div>';
        $hours_report_pdf .= '<div style="padding: 10px 0;text-align:center;width: 16.6%;float:left;">'.$totalHoursMissed.'</div>';
    $hours_report_pdf .= '</div>';
$hours_report_pdf .= '</div>';


?>
<div class="in-row" style="font-size: 15px; background: #efefef;">
    <div class="in-row">
        <div class="col7"></div>
        <div class="col3"><?PHP echo($dic->translate("Total Hours Attended"))?></div>
        <div class="col2"><?PHP echo($totalHoursAttended)?></div>
    </div>
    <div class="in-row" style="color: #e50404;">
        <div class="col7"></div>
        <div class="col3"><?PHP echo($dic->translate("Total Hours Missed"))?></div>
        <div class="col2"><?PHP echo($totalHoursMissed)?></div>
    </div>
</div>
<?PHP


?>