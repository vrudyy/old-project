<?php
$dao = new DAO();

$student = new Student();
$student = $dao->get("Student", $_POST["studentId"], "studentId");
$studentContact = new Contact();
$studentContact = $dao->get("Contact", $student->contactId, "contactId");
$parentContact = new Contact();
$parentContact = $dao->get("Contact",$student->parentId,"contactId");



if(isset($_POST["classClicked"])){
    if(isset($_POST["tick"])){
        $studentClass = new StudentClass();
        $studentClass->studentClassStatus = 1;
        $studentClass->classId = $_POST["classId"];
        $studentClass->studentId = $student->studentId;
        $dao->add($studentClass);
    }else if(isset ($_POST["untick"])){
        $dao->deleteWhere("StudentClass", "WHERE `classId` = ".$_POST["classId"]." AND `studentId` = ".$student->studentId.";");
    }
    
}

$classes = $dao->listAll("inClass", 'clientId', $client->clientId);
$studentClasses = $dao->listAll("StudentClass","studentId",$student->studentId);


$studentInClasses = [];
$otherClasses = $classes;


foreach($classes as $key => $class){
    foreach($studentClasses as $studentClass){
        if($class->classId == $studentClass->classId){
            array_push($studentInClasses, $class);
            unset($otherClasses[$key]);
            break;
        }
    }
}



if(sizeof($studentInClasses)!=0){
    echo '<div class="in-row" style="padding: 0 0 20px 0;border-bottom: 1px solid gray;">';
        echo '<div class="in-row in-class-courses-titles">'.$dic->translate("Attending Classes").':</div>';
        foreach($studentInClasses as $key => $c){
            echo '<div class="col3 in-tick-wrapper">';
                echo '<div class="in-row in-tick" style="background:lightblue;">';
                    echo '<div class="col10 in-tick-title">'.$c->classLabel.'</div>';
                    echo '<form class="col2 in-tick-input" method="post">';
                        echo '<input type="hidden" name="studentId" value="'.$student->studentId.'" />';
                        echo '<input type="hidden" name="classId" value="'.$c->classId.'"/>';
                        echo '<input type="hidden" name="untick" value="untick"/>';
                        echo '<button name="classClicked"><i class="fas fa-check-circle"></i></button>';
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
}
if(sizeof($studentInClasses) == 0 && sizeof($otherClasses)==0){
    echo '<div style="float:left;margin: 100px 0 0 0;  width: 100%;" class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are no classes available').'</div>';
    echo '</div>';
}else if(sizeof($otherClasses)==0){
    echo '<div style="float:left; margin: 100px 0 0 0;width: 100%;" class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are no more classes available').'</div>';
    echo '</div>';
}else{
    echo '<div class="in-row" style="'.(sizeof($studentInClasses)!=0?"padding: 20px 0 0 0;":"").'">';
        echo '<div class="in-row in-class-courses-titles">'.$dic->translate("Available Classes").':</div>';
        foreach($otherClasses as $c){
            echo '<div class="col3 in-tick-wrapper">';
                echo '<div class="in-row in-tick">';
                    echo '<div class="col10 in-tick-title">'.$c->classLabel.'</div>';
                    echo '<form class="col2 in-tick-input" method="post">';
                        echo '<input type="hidden" name="studentId" value="'.$student->studentId.'" />';
                        echo '<input type="hidden" name="classId" value="'.$c->classId.'"/>';
                        echo '<input type="hidden" name="tick" value="tick"/>';
                        echo '<button name="classClicked"><i class="fas fa-circle"></i></button>';
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
}