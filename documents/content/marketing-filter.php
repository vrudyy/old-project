<?php



echo '<div class="tutor-filter-wrapper in-row">';
    echo '<div class="tutor-filter in-row">';
        echo '<div class="tutor-filter-title in-row">'.$dic->translate("Status").': </div>';
        echo '<form class="tutor-filter-status in-row" method="post">';
            echo '<input type="hidden" name="filter" value="open" />';
            if(isset($_POST["marketingChannel"])){
                echo '<input name="marketingChannel" type="hidden" value="'.$_POST["marketingChannel"].'" />';
            }
            if(!isset($_POST["status"]) || $_POST["status"] == 2){
                echo '<button class="tutor-filter-selected" name="status" value="2">All<i class="fas fa-check-circle"></i></button>';
                for($i = 0; $i<sizeof($statuses);$i++){
                    echo '<button name="status" value="'.$i.'">'.$statuses[$i].'<i class="fas fa-circle"></i></button>';
                } 
            }else{
                echo '<button name="status" value="2">All<i class="fas fa-circle"></i></button>';
                for($i = 0; $i<sizeof($statuses);$i++){
                    if($i == $_POST["status"]){
                        echo '<button class="tutor-filter-selected" name="status" value="'.$i.'">'.$statuses[$i].'<i class="fas fa-check-circle"></i></button>';
                    }else{
                        echo '<button name="status" value="'.$i.'">'.$statuses[$i].'<i class="fas fa-circle"></i></button>';
                    }
                }
            }
            
        echo '</form>';
        echo '<div class="tutor-filter-title in-row">'.$dic->translate("Marketing Channels").': </div>';
        echo '<form class="tutor-filter-courses in-row" method="post">';
            echo '<input type="hidden" name="filter" value="open" />';
            
            if(isset($_POST["status"])){
                echo '<input name="status" type="hidden" value="'.$_POST["status"].'" />';
            }
            if(isset($_POST["marketingChannel"])){
                if($_POST["marketingChannel"] != 0){
                    echo '<button name="marketingChannel" value="all">All<i class="fas fa-circle"></i></button>';
                    foreach($mc as $m){
                        if($_POST["marketingChannel"] == $m->marketingChannelId){
                            echo '<button class="tutor-filter-selected" name="marketingChannel" value="'.$m->marketingChannelId.'">'.$m->marketingChannelName.'<i class="fas fa-check-circle"></i></button>';
                        }else{
                            echo '<button name="marketingChannel" value="'.$m->marketingChannelId.'">'.$m->marketingChannelName.'<i class="fas fa-circle"></i></button>';
                        }
                    }
                }else{
                    echo '<button class="tutor-filter-selected" name="marketingChannel" value="all">All<i class="fas fa-check-circle"></i></button>';
                    foreach($mc as $m){
                        echo '<button name="marketingChannel" value="'.$m->marketingChannelId.'">'.$m->marketingChannelName.'<i class="fas fa-circle"></i></button>';
                    } 
                }
            }else{
                echo '<button class="tutor-filter-selected" name="marketingChannel" value="all">All<i class="fas fa-check-circle"></i></button>';
                foreach($mc as $m){
                    echo '<button name="marketingChannel" value="'.$m->marketingChannelId.'">'.$m->marketingChannelName.'<i class="fas fa-circle"></i></button>';
                }
            }
        echo '</form>';
    echo '</div>';
echo '</div>';