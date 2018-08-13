<?php
$tutorCourses = $tutor->getCourseArray();
foreach($courses as $c){
    echo '<div class="col3 in-tick-wrapper">';
        echo '<div class="in-row in-tick" ';
        echo(in_array($c->courseId, $tutorCourses)) ? 'style="background:lightblue;"' : "";
        echo '>';
            echo '<div class="col10 in-tick-title">'.$c->courseName.'</div>';
            echo '<form class="col2 in-tick-input" method="post">';
                echo '<input type="hidden" name="tutorId" value="'.$tutor->tutorId.'" />';
                echo '<input type="hidden" name="courseId" value="'.$c->courseId.'"/>';
                if(in_array($c->courseId, $tutorCourses)){
                    echo '<input type="hidden" name="tick" value="untick"/>';
                    echo '<button><i class="fas fa-check-circle"></i></button>';
                }else{
                    echo '<input type="hidden" name="tick" value="tick"/>';
                    echo '<button><i class="fas fa-circle"></i></button>';
                }
            echo '</form>';
        echo '</div>';
    echo '</div>';
}