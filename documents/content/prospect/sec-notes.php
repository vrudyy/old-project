<div class="in-container">
    
    <div class="col8" id="in-notes-sec">
        <div class="add-note" style="padding-top:0 ">
            <div class="add-form">
                <h3><?php echo($dic->translate("Add a Note"))?></h3>
                <form method="post">
                    <textarea rows="4" cols="50" name="text"></textarea>
                    <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId)?>"/>
                    <button type="submit" value="submit" name="newnote"><?php echo($dic->translate("Add Note"))?></button>
                </form>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div class="in-notes">
            <?php 
                for($j = sizeof($notes)-1; $j>=0; $j--){
                    $author = $dao->get("Contact", $notes[$j]->noteAuthor, "contactId");
                    echo '<div class="in-note">';
                    echo '<div class="in-note-text">';
                    echo $notes[$j]->noteText;
                    echo '</div>';
                    echo '<div class="in-note-footer">';
                    echo '<p>';
                    echo $dic->translate("Added by ");
                    echo $author->contactFirstName . " " . $author->contactLastName;
                    echo ' on ';
                    $date = date_create($notes[$j]->noteDate);
                    $date = date_format($date, "l jS F Y H:i:s");
                    echo $date;
                    echo '</p>';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="contactid" ';
                    echo 'value="';
                    echo $parentContact->contactId;
                    echo '"/>';
                    echo "<input type=\"hidden\" name=\"noteid\" value=\"".$notes[$j]->noteId."\"/>";
                    echo '<input type="hidden" name="prospectId" value="'.$prospect->prospectId.'"/>';
                    echo '<button type="submit" name="delete-note" value="delete-note">'.$dic->translate("Delete").'</button>';
                    echo '</form>';
                    echo '<div style="clear:both;"></div>';
                    echo '</div>';
                    echo '</div>';
                }
            
            ?>        
        </div>
    </div>
   
    <div style="clear: both;"></div>
</div>