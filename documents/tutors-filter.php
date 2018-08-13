<?php



echo '<div class="tutor-filter-wrapper in-row">';
    echo '<div class="tutor-filter in-row">';
        echo '<div class="tutor-filter-title in-row">'.$dic->translate("Status").': </div>';
        echo '<form class="tutor-filter-status in-row" method="post">';
            echo '<input type="hidden" name="filter" value="open" />';
            if(isset($_POST["course"])){
                echo '<input name="course" type="hidden" value="'.$_POST["course"].'" />';
            }
            if(!isset($_POST["status"]) || $_POST["status"] == 0){
                echo '<button class="tutor-filter-selected" name="status" value="0">All<i class="fas fa-check-circle"></i></button>';
                foreach($tutorStatuses as $t){
                    echo '<button name="status" value="'.$t->tutorStatusId.'">'.$t->tutorStatusStatus.'<i class="fas fa-circle"></i></button>';
                } 
            }else{
                echo '<button name="status" value="0">All<i class="fas fa-circle"></i></button>';
                foreach($tutorStatuses as $t){
                    if($t->tutorStatusId == $_POST["status"]){
                        echo '<button class="tutor-filter-selected" name="status" value="'.$t->tutorStatusId.'">'.$t->tutorStatusStatus.'<i class="fas fa-check-circle"></i></button>';
                    }else{
                        echo '<button name="status" value="'.$t->tutorStatusId.'">'.$t->tutorStatusStatus.'<i class="fas fa-circle"></i></button>';
                    }
                }
            }
            
        echo '</form>';
        echo '<div class="tutor-filter-title in-row">'.$dic->translate("Approved Courses").': </div>';
        echo '<form class="tutor-filter-courses in-row" method="post">';
            echo '<input type="hidden" name="filter" value="open" />';
            
            if(isset($_POST["status"])){
                echo '<input name="status" type="hidden" value="'.$_POST["status"].'" />';
            }
            if(isset($_POST["course"])){
                if($_POST["course"] != 0){
                    echo '<button name="course" value="all">All<i class="fas fa-circle"></i></button>';
                    foreach($courses as $c){
                        if($_POST["course"] == $c->courseId){
                            echo '<button class="tutor-filter-selected" name="course" value="'.$c->courseId.'">'.$c->courseName.'<i class="fas fa-check-circle"></i></button>';
                        }else{
                            echo '<button name="course" value="'.$c->courseId.'">'.$c->courseName.'<i class="fas fa-circle"></i></button>';
                        }
                    }
                }else{
                    echo '<button class="tutor-filter-selected" name="course" value="all">All<i class="fas fa-check-circle"></i></button>';
                    foreach($courses as $c){
                        echo '<button name="course" value="'.$c->courseId.'">'.$c->courseName.'<i class="fas fa-circle"></i></button>';
                    } 
                }
            }else{
                echo '<button class="tutor-filter-selected" name="course" value="all">All<i class="fas fa-check-circle"></i></button>';
                foreach($courses as $c){
                    echo '<button name="course" value="'.$c->courseId.'">'.$c->courseName.'<i class="fas fa-circle"></i></button>';
                }
            }
        echo '</form>';
    echo '</div>';
echo '</div>';