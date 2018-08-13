<?php
$classStudents = $dao->listAll("StudentClass", "classId", $class->classId);
$students = [];
$parents = [];
foreach($classStudents as $c){
   $student = $dao->get("Student", $c->studentId, "studentId"); 
   $prospect = $dao->get("Prospect", $student->prospectId, 'prospectId');
   $parent = $dao->get("Contact", $prospect->parentId, 'contactId');;
   $student = $dao->get("Contact", $student->contactId, "contactId");
   array_push($students, $student);
   array_push($parents, $parent);
}

if(isset($_POST['sendEmail'])){
    $mailer = new Mailer2();
    
    if(isset($_POST['sendEmailToStudents'])){
        foreach($students as $s){
            $mailer->basicEmail($s->contactEmail, $contact->contactEmail, $_POST["emailSubject"], $_POST["emailMessage"]);
        }
    }
    if(isset($_POST['sendEmailToParents'])){
        foreach($parents as $p){
            $mailer->basicEmail($p->contactEmail, $contact->contactEmail, $_POST["emailSubject"], $_POST["emailMessage"]);
        }
    }
}

if(sizeof($students)!=0){
    

?>
<div>
    <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-beta.3/classic/ckeditor.js"></script>
    <form method="post">
        <div style="color: #2c89ba;font-weight: bold;float:left;"><?PHP echo($dic->translate("From").":")?></div>
        <div style="float:left;padding:0 0 0 25px"><?PHP echo($contact->contactFirstName." ".$contact->contactLastName)?></div>
        <div class="in-row" style="padding: 15px 0">
            <div style="color: #2c89ba;font-weight: bold;float:left;padding: 0 20px 0 0;"><?PHP echo($dic->translate("To").":")?></div>
            <div style="float:left;padding: 0 10px;">
                <input id="emailCheckboxStudents" style="float:left;" type="checkbox" name="sendEmailToStudents" value="sendEmailToStudents">
                <div style="float:left;padding: 0 10px;" ><?PHP echo($dic->translate("All Students"))?></div>
            </div>
            <div style="float:left;padding: 0 10px;">
                <input id="emailCheckboxParents" style="float:left;"  type="checkbox" name="sendEmailToParents" value="sendEmailToParents">
                <div style="float:left;padding: 0 10px;" ><?PHP echo($dic->translate("All Parents"))?></div>
            </div>
        </div>
        <div style="color: #2c89ba;font-weight: bold;float:left;clear:both;padding:0 0 15px 0;"><?PHP echo($dic->translate("CC").":")?></div>
        <div style="float:left;padding: 0 0 15px 20px;"><?PHP echo($client->clientEmail) ?></div>
        
        <div style="width: 100%;float:left;padding: 15px 0;clear: both;">
            <div style="color: #2c89ba;font-weight: bold;float:left;line-height: 30px;padding: 0 15px 0 0;" ><?PHP echo($dic->translate("Subject").":")?></div>
            <input id="emailSubject" style="width: 100%;height: 30px;font-size: 20px;padding: 5px 5px;float:left;"  type="text" name="emailSubject" >
        </div>
        <div style="width: 100%;color: #2c89ba;font-weight: bold;float:left;line-height: 30px;padding: 0 15px 0 0;" ><?PHP echo($dic->translate("Message").":")?></div>
        <input type="hidden" name="classId" value="<?PHP echo($class->classId) ?>"/>
        <textarea name="emailMessage" id="in-editor"><?php echo($text)?></textarea>
        <button id="sendEmailButton" type="submit" name="sendEmail" style="margin: 10px 0 0 0" class="widget"><?PHP echo($dic->translate("Send Email"))?></button>
    </form>
    
    <script>
       ClassicEditor
            .create( document.querySelector( '#in-editor' ) )
            .then( editor => {
                    console.log( editor );
            } )
            .catch( error => {
                    console.error( error );
            } );
            
        var emailCheckboxStudents = document.getElementById("emailCheckboxStudents");
        var emailCheckboxParents = document.getElementById("emailCheckboxParents");
        var sendEmailButton = document.getElementById("sendEmailButton");
        var emailSubject = document.getElementById("emailSubject");
        
        function checkBothCheckboxes(){
            if(emailSubject.value == ""){
                sendEmailButton.style.pointerEvents = "none";
                sendEmailButton.style.background = "gray";
            }else {
                if(!emailCheckboxStudents.checked && !emailCheckboxParents.checked ){
                    sendEmailButton.style.pointerEvents = "none";
                    sendEmailButton.style.background = "gray";
                }else{
                    sendEmailButton.style.pointerEvents = "auto";
                    sendEmailButton.style.background = "#2c89ba";
                }
            }
        }
        
        checkBothCheckboxes();
        
        emailSubject.addEventListener("change", function(e){
            checkBothCheckboxes();
        });
        emailCheckboxStudents.addEventListener("click", function(e){
            checkBothCheckboxes();
        });
        emailCheckboxParents.addEventListener("click", function(e){
            checkBothCheckboxes();
        });
    </script>
</div>
<?PHP 

}else{
    echo '<div class="class-no-student-title">';
        echo '<div>'.$dic->translate('There are currently no students in this class').'</div>';
    echo '</div>';
} 
?>

