
<?php
$date = new Date();
foreach($reminders as $r){
    #$r = new Reminder;
    echo '<div class="in-card-wrapper col3">';
        echo '<div class="in-card in-row" style="height: 130px;">';
            echo '<div title="'.$r->reminderDescription.'" style="height: 90px; padding: 5px 10px; overflow: hidden;" class="in-card-title col9">';
                echo '<div style="font-size: 14px;">';
                    echo $r->reminderDescription;
                echo '</div>';
            echo '</div>';
            echo '<i class="in-card-icon fas fa-bell col3"></i>';
            echo '<div style="font-size: 12px;" class="col9 in-card-details">';
                echo $dic->translate("Date").":  ";
                echo $date->longDate($r->reminderDate);
            echo '</div>';
            echo '<div class="col3 in-card-edit" style="padding: 0; font-size: 11px;margin: 0;">';
                echo '<form method="post">';
                    echo '<input type="hidden" name="reminderId" value="'.$r->reminderId.'" />';
                    echo '<input type="hidden" name="prospectId" value="'.$prospect->prospectId.'" />';
                    echo '<button name="markComplete" value="markComplete" style="text-align: center;">';
                        echo $dic->translate("Mark as Complete");
                    echo '</button>';
                echo '</form>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
} 
$dao->close();
?>

