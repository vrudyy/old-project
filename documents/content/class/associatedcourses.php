<?php
#$dao = new DAO();
#$class = new inClass();
$courses = $dao->listAll("Course", 'clientId', $client->clientId);

if(isset($_POST["courseClicked"])){
    if(isset($_POST["tick"])){
        $class->addCourse($_POST['courseId']);
    }else if(isset ($_POST["untick"])){
        $class->removeCourse($_POST['courseId']);
    }
    $dao->update($class);
}
$classCourses = $class->getCourseArray();

$associatedCourses = [];
$availableCourses = [];

foreach($courses as $cs){
    if(in_array($cs->courseId, $classCourses)){
        array_push($associatedCourses, $cs);
    }else{
        array_push($availableCourses, $cs);
    }
}

if(sizeof($associatedCourses)!=0){
    echo '<div class="in-row" style="padding: 0 0 20px 0;border-bottom: 1px solid gray;">';
        echo '<div class="in-row in-class-courses-titles">'.$dic->translate("Associated Courses").':</div>';
        foreach($associatedCourses as $c){
            echo '<div class="col3 in-tick-wrapper">';
                echo '<div class="in-row in-tick" ';
                echo(in_array($c->courseId, $classCourses)) ? 'style="background:lightblue;"' : "";
                echo '>';
                    echo '<div class="col10 in-tick-title">'.$c->courseName.'</div>';
                    echo '<form class="col2 in-tick-input" method="post">';
                        echo '<input type="hidden" name="courseId" value="'.$c->courseId.'" />';
                        echo '<input type="hidden" name="classId" value="'.$class->classId.'"/>';
                        if(in_array($c->courseId, $classCourses)){
                            echo '<input type="hidden" name="untick" value="untick"/>';
                            echo '<button name="courseClicked"><i class="fas fa-check-circle"></i></button>';
                        }else{
                            echo '<input type="hidden" name="tick" value="tick"/>';
                            echo '<button name="courseClicked"><i class="fas fa-circle"></i></button>';
                        }
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
    
}
if(sizeof($associatedCourses) == 0 && sizeof($availableCourses)==0){
    echo '<div style="float:left;margin: 100px 0 0 0;  width: 100%;" class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are no courses available').'</div>';
    echo '</div>';
}else if(sizeof($availableCourses)==0){
    echo '<div style="float:left; margin: 100px 0 0 0;width: 100%;" class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are no more courses available').'</div>';
    echo '</div>';
}else{
    echo '<div class="in-row" style="'.(sizeof($associatedCourses)!=0?"padding: 20px 0 0 0;":"").'">';
        echo '<div class="in-row in-class-courses-titles">'.$dic->translate("Available Courses").':</div>';
        foreach($availableCourses as $c){
            echo '<div class="col3 in-tick-wrapper">';
                echo '<div class="in-row in-tick" ';
                echo(in_array($c->courseId, $classCourses)) ? 'style="background:lightblue;"' : "";
                echo '>';
                    echo '<div class="col10 in-tick-title">'.$c->courseName.'</div>';
                    echo '<form class="col2 in-tick-input" method="post">';
                        echo '<input type="hidden" name="courseId" value="'.$c->courseId.'" />';
                        echo '<input type="hidden" name="classId" value="'.$class->classId.'"/>';
                        if(in_array($c->courseId, $classCourses)){
                            echo '<input type="hidden" name="untick" value="untick"/>';
                            echo '<button name="courseClicked"><i class="fas fa-check-circle"></i></button>';
                        }else{
                            echo '<input type="hidden" name="tick" value="tick"/>';
                            echo '<button name="courseClicked"><i class="fas fa-circle"></i></button>';
                        }
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';
}


