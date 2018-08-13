<?php
$student = new Student();
$student = $dao->get("Student", $_POST["studentId"], "studentId");
$studentContact = new Contact();
$studentContact = $dao->get("Contact", $student->contactId, "contactId");
$parentContact = new Contact();
$parentContact = $dao->get("Contact",$student->parentId,"contactId");


$contacts = [$studentContact, $parentContact];
exportStudent($contacts);
?>
<div class="in-row in-student-profile-wrapper">
    <div class="in-row in-student-profile">
        <div class="in-row in-student-profile-section">
            <div class="col3 in-student-pic"><img src="img/Blank-profile.png" alt="<?PHP echo($dic->translate("profile picture"))?>"/></div>
            <div class="col9 in-student-name">
                <?PHP 
                    
                    echo($studentContact->fullName());
                    echo '<div style="font-size: 16px;">'.$studentContact->contactEmail.'</div>';
                    echo '<div style="font-size: 16px;">'.$studentContact->contactPhone.'</div>';
                ?>
            </div>
        </div>
        <div class="in-row in-student-profile-section">
            <div class="col3 in-student-titles"><?PHP echo($dic->translate("Address"))?></div>
            <div class="col9">
                <?PHP 
                    $address = new Address();
                    $address->convertToObject($studentContact->contactAddress);
                    echo '<div>'.$address->firstline.'</div>';
                    echo '<div>'.$address->secondline.'</div>';
                    echo '<div>'.$address->thirdline.'</div>';
                    echo '<div>'.$address->town.'</div>';
                    echo '<div>'.$address->region.'</div>';
                    echo '<div>'.$address->zip.'</div>';
                    #echo($studentContact->contactAddress);
                ?>
            </div>
        </div>
        <div class="in-row in-student-profile-section">
            <div class="col3 in-student-titles"><?PHP echo($dic->translate("Parent's/Guardian's details"))?></div>
            <div class="col9">
                <?PHP 
                    echo '<div>'.($parentContact->fullName()).'</div>';
                    echo '<div>'.($parentContact->contactEmail).'</div>';
                    echo '<div>'.($parentContact->contactPhone).'</div>';
                ?>
            </div>
        </div>
        <div class="in-row in-student-profile-section">
            <div class="col3 in-student-titles"><?PHP echo($dic->translate("Address"))?></div>
            <div class="col9">
                <?PHP 
                    $address->convertToObject($parentContact->contactAddress);
                    echo '<div>'.$address->firstline.'</div>';
                    echo '<div>'.$address->secondline.'</div>';
                    echo '<div>'.$address->thirdline.'</div>';
                    echo '<div>'.$address->town.'</div>';
                    echo '<div>'.$address->region.'</div>';
                    echo '<div>'.$address->zip.'</div>';
                    #echo($parentContact->fullName());
                ?>
            </div>
        </div>
        <div class="in-row in-student-profile-section">
            <div class="col3 in-student-titles"><?PHP echo($dic->translate("School"))?></div>
            <div class="col9">
                <div><?PHP echo($student->studentSchool)?></div>
                <div>
                    <?PHP
                        $educationLevel = new EducationLevel();
                        $educationLevel = $dao->get("EducationLevel",$student->educationLevelId,"educationLevelId");
                        //echo $dic->translate("Education Level").": ";
                        echo($educationLevel->educationLevelName);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <form action="<?php echo("clients/$client->clientId/contacts/vcards/student-parent.vcf"); ?>" method="post">
        <button style="margin-top: 5px;" class="widget" type="submit" name="in-contacts-export-all" value="in-contacts-export-all"><?php echo $dic->translate("Download")." vCards" ?></button>
    </form>
</div>
