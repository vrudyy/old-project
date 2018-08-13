<?php
//$dao = new DAO();
//
//$student = new Student();
//$student = $dao->get("Student", $_POST["studentId"], "studentId");
//$studentContact = new Contact();
//$studentContact = $dao->get("Contact", $student->contactId, "contactId");
//$parentContact = new Contact();
//$parentContact = $dao->get("Contact", $student->parentId, "contactId");

$qualifications = $dao->listAll("Qualification", "clientId", $client->clientId);


if(isset($_POST["qualificationClicked"])){
    if(isset($_POST["tick"])){
        $student->addQualification($_POST["qualificationId"]);
    }else if(isset ($_POST["untick"])){
        $student->removeQualification($_POST["qualificationId"]);
    }
    $dao->update($student);
}
$studentQualificationArray = $student->getQualificationArray();

$studentQualifications = [];
$availableQualifications = [];

foreach($qualifications as $key => $value){
    #$value = new Qualification();
    if(in_array($value->qualificationId, $studentQualificationArray)){
        array_push($studentQualifications, $value);
    }else{
        array_push($availableQualifications, $value);
    }
}

if(sizeof($studentQualifications)!=0){
    echo '<div class="in-row" style="padding: 0 0 20px 0;border-bottom: 1px solid gray;">';
        echo '<div class="in-row in-class-courses-titles">'.$dic->translate("Student Qualifications").':</div>';
        foreach($studentQualifications as $key => $value){
            echo '<div class="col3 in-tick-wrapper">';
                echo '<div class="in-row in-tick" style="background:lightblue;">';
                    echo '<div class="col10 in-tick-title">'.$value->qualificationName.'</div>';
                    echo '<form class="col2 in-tick-input" method="post">';
                        echo '<input type="hidden" name="studentId" value="'.$student->studentId.'" />';
                        echo '<input type="hidden" name="qualificationId" value="'.$value->qualificationId.'"/>';
                        echo '<input type="hidden" name="untick" value="untick"/>';
                        echo '<button name="qualificationClicked"><i class="fas fa-check-circle"></i></button>';
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
    
}
if(sizeof($studentQualifications) == 0 && sizeof($availableQualifications)==0){
    echo '<div style="float:left;margin: 100px 0 0 0;  width: 100%;" class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are no qualification available').'</div>';
    echo '</div>';
}else if(sizeof($availableQualifications)==0){
    echo '<div style="float:left; margin: 100px 0 0 0;width: 100%;" class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are no more qualifications available').'</div>';
    echo '</div>';
}else{
    echo '<div class="in-row" style="'.(sizeof($studentQualifications)!=0?"padding: 20px 0 0 0;":"").'">';
        echo '<div class="in-row in-class-courses-titles">'.$dic->translate("Available Qualifications").':</div>';
        foreach($availableQualifications as $key => $value){
            echo '<div class="col3 in-tick-wrapper">';
                echo '<div class="in-row in-tick">';
                    echo '<div class="col10 in-tick-title">'.$value->qualificationName.'</div>';
                    echo '<form class="col2 in-tick-input" method="post">';
                        echo '<input type="hidden" name="studentId" value="'.$student->studentId.'" />';
                        echo '<input type="hidden" name="qualificationId" value="'.$value->qualificationId.'"/>';
                        echo '<input type="hidden" name="tick" value="tick"/>';
                        echo '<button name="qualificationClicked"><i class="fas fa-circle"></i></button>';
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
}


