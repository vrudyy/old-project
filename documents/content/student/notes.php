<?PHP
/*
$student = new Student();
$student = $dao->get("Student", $_POST["studentId"], "studentId");
$studentContact = new Contact();
$studentContact = $dao->get("Contact", $student->contactId, "contactId");
$parentContact = new Contact();
$parentContact = $dao->get("Contact",$student->parentId,"contactId");
*/

if(strcmp($_POST["newnote"], "newnote") == 0){
    $note = new Note();
    $note->noteAuthor = $contact->contactId;
    $note->noteContact = $studentContact->contactId;
    $note->noteDate = date("Y-m-d H:i:s");
    $note->noteText = htmlspecialchars($_POST["text"]);
    $dao->add($note);
    var_dump($dao);
}
if(isset($_POST["delete-note"])){
    $dao->delete("Note", "noteId", $_POST["noteId"]);
}
$notes = $dao->listAll("Note", "noteContact", $studentContact->contactId);
#var_dump($_POST);
?>


<div class="in-container">
    
    <div class="col8" id="in-notes-sec">
        <div class="add-note" style="padding-top:0 ">
            <div class="add-form">
                <h3><?php echo($dic->translate("Add a Note"))?></h3>
                <form method="post">
                    <textarea rows="4" cols="50" name="text"></textarea>
                    <input type="hidden" name="studentId" value="<?php echo($student->studentId)?>">
                    <button type="submit" value="newnote" name="newnote"><?php echo($dic->translate("Add Note"))?></button>
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
                    echo '<input type="hidden" name="studentId" ';
                    echo 'value="';
                    echo $student->studentId;
                    echo '"/>';
                    echo "<input type=\"hidden\" name=\"noteId\" value=\"".$notes[$j]->noteId."\"/>";
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