<?php
$dao = new DAO();
$classStudents = $dao->listAll("StudentClass", "classId", $class->classId);
$students = [];
foreach($classStudents as $c){
   $student = $dao->get("Student", $c->studentId, "studentId"); 
   $student = $dao->get("Contact", $student->contactId, "contactId");
   array_push($students, $student);
}
if(sizeof($students)==0){
    echo '<div class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are currently no students in this class').'</div>';
    echo '</div>';
}
foreach($students as $s){
    echo '<div class="in-card-wrapper col3">';
        echo '<div style="height: 45px;" class="in-card in-row">';
            echo '<div class="in-card-title col9">';
                echo '<div style="font-size: 16px; font-weight: bold;" class="in-row">';
                    echo '<div type="submit">'.$s->contactFirstName.' '.$s->contactLastName.'</div>';
                echo '</div>';
            echo '</div>';
            echo '<i class="in-card-icon fas fa-user col3"></i>';
            
        echo '</div>';
    echo '</div>';
} 
