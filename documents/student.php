<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/old/createVCard.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileStructure.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileUpload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/DriveFile.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
    
    ob_start();
    
    $dao = new DAO();
    include("content/session.php");
    
    $student = new Student();
    
    if(!isset($_POST["studentId"])){
        $student = $dao->get("Student", $_SESSION["studentId"], "studentId");
    }else{
        $student = $dao->get("Student", $_POST["studentId"], "studentId");
    }
    $studentContact = new Contact();
    $studentContact = $dao->get("Contact", $student->contactId, "contactId");
    
    

    
    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <?php include("content/head.php") ?>
        

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>
        <link href="css/contact.css" rel="stylesheet" type="text/css"/>
        <style>
            #in-vcard-sec > div > div > div > p:nth-child(3){
                margin: 10px 0;
            }
            #in-notes-sec{
                background: none;
                border: none;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div style="height: 60px;" class="in-drive-header col12">
                    <div class="in-fl-l"><?php echo ($dic->translate("Student's Details").": ".$studentContact->fullName()) ?></div>
                    <?php
                        if(strcmp($_GET["sec"], "studentfiles")==0){
                    ?>
                            <form class="in-fl-r" method="post">
                                <input type="hidden" name="studentId" value="<?php echo $student->studentId?>"/>
                                <button id="new-button" class="widget"><?php echo $dic->translate("New") ?></button>
                            </form>
                            <div class="in-course-button-menu" id="button-menu">
                                <ul>
                                    <li>
                                        <i class="in-fl-l fa fa-folder"></i>
                                        <form method="post">
                                            <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                            <button id="newFolderButton" class="in-fl-l" type="submit" name="submit" value="newfolder">
                                                <?php echo($dic->translate("New Folder")) ?>
                                            </button>
                                        </form>
                                    </li>
                                    <li><i class="in-fl-l fa fa-file-text"></i><form method="post"><input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/><button id="newFileButton" class="in-fl-l" type="submit" name="submit" value="newfile"><?php echo($dic->translate("New File Upload")) ?></button></form></li>
                                </ul>
                            </div>
                    <?php
                        }
                        if(strcmp($_GET["sec"], "studenttests")==0){
                    ?>
                            <form action="newstudenttest.php" method="POST">
                                <input type="hidden" name="studentId" value="<?PHP echo($student->studentId) ?>"/>
                                <button class="in-fl-r widget"><?PHP echo($dic->translate("Add New Test Result"))?></button>
                            </form>
                    <?PHP
                        }
                    ?>
                    <?php
                        
                        if(strcmp($_GET["sec"], "studentexams")==0){
                    ?>
                            <form action="newstudentexam.php" method="POST">
                                <input type="hidden" name="studentId" value="<?PHP echo($student->studentId) ?>"/>
                                <button class="in-fl-r widget"><?PHP echo($dic->translate("Add New Exam Result"))?></button>
                            </form>
                    <?PHP
                        }
                        if(strcmp($_GET["sec"], "hoursreport")==0){
                    ?>
                            <form method="POST">
                                <input type="hidden" name="studentId" value="<?PHP echo($student->studentId) ?>"/>
                            <?PHP
                                if(isset($_POST["startDate"])){
                                    echo '<input type="hidden" name="startDate" value="'.$_POST["startDate"].'"/>';
                                }
                                if(isset($_POST["endDate"])){
                                    echo '<input type="hidden" name="endDate" value="'.$_POST["endDate"].'"/>';
                                }
                                if(isset($_POST["startDate"])){
                                    echo '<input type="hidden" name="class" value="'.$_POST["class"].'"/>';
                                }
                            ?>
                                <button type="submit" name="export_pdf" value="export_pdf" style="margin: 0 5px;" class="in-fl-r widget"><?PHP echo($dic->translate("Export PDF"))?></button>
                            </form>
                    <?PHP
                        }
                    ?>
                </div>
                <div class="in-drive-menu col3">
                    <ul>
                        <li class="in-row <?php echo((strcmp("profile", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fa-user"></i>
                            <form action="student.php?sec=profile" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Student Profile")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("attendingclasses", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fa-users"></i>
                            <form action="student.php?sec=attendingclasses" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Attending Classes")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("qualifications", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fa-mortar-board"></i>
                            <form action="student.php?sec=qualifications" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Qualifications")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("hoursreport", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fa fa-file-text"></i>
                            <form action="student.php?sec=hoursreport" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Hours Report")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("studenttests", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fa fa-tasks"></i>
                            <form action="student.php?sec=studenttests" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Student Tests")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("studentexams", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fa fa-history"></i>
                            <form action="student.php?sec=studentexams" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Student Exams")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("classperformance", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fa fa-bar-chart"></i>
                            <form action="student.php?sec=classperformance" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Class Performance")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("studentfiles", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fa fa-folder-open"></i>
                            <form action="student.php?sec=studentfiles" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Student Files")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("notes", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fas fa-pen-square"></i>
                            <form action="student.php?sec=notes" method="post">
                                <input type="hidden" name="studentId" value="<?php echo($student->studentId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Notes")?></button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="in-drive-files col9" id="drop-area">
                    <?php
                        if(strcmp($_GET["sec"], "profile")==0){
                            include_once 'content/student/profile.php';
                        }
                        if(strcmp($_GET["sec"], "attendingclasses")==0){
                            include_once 'content/student/attendingclasses.php';
                        }
                        if(strcmp($_GET["sec"], "qualifications")==0){
                            include_once 'content/student/qualifications.php';
                        }
                        if(strcmp($_GET['sec'], 'notes')==0){
                            include_once 'content/student/notes.php';
                        }
                        if(strcmp($_GET['sec'], 'studentfiles')==0){
                            include_once 'content/student/studentfiles.php';
                        }
                        if(strcmp($_GET['sec'], 'studenttests')==0){
                            include_once 'content/student/studenttests.php';
                        }
                        if(strcmp($_GET['sec'], 'studentexams')==0){
                            include_once 'content/student/studentexams.php';
                        }
                        if(strcmp($_GET['sec'], 'hoursreport')==0){
                            include_once 'content/student/hoursreport.php';
                        }//
                    ?>
                </div> 
            </div>
        </div>
        <?php include("content/footer.php") ?>
        <?php 
            if(strcmp($_GET["sec"], "studentfiles")==0){
                
        ?>
            <div class="in-upload-file-wrapper" id="in-upload-file-wrapper" style="display:none;">
                <div class="in-upload-file" id="in-upload-file">
                    <div><?php echo("Upload File:")?></div>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="studentId" value="<?php echo($student->studentId)?>">
                        <?php 
                            if(isset($_POST["folderName"])){
                                echo '<input type="hidden" name="folderName" value="'.$_POST["folderName"].'"/>';
                            }

                        ?>
                        <input required name="file" type="file"/>
                        <button class="widget" type="submit" name="newFile" value="submit"><?php echo("Upload")?></button>
                    </form>
                </div>
            </div>
            <div class="in-upload-file-wrapper" id="in-upload-folder-wrapper" style="display:none;">
                <div class="in-upload-file" id="in-upload-folder">
                    <div><?php echo("New Folder:")?></div>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="studentId" value="<?php echo($student->studentId)?>">
                        <input style="width: 50%; height: 30px; line-height: 30px; padding: 0 5px;" required name="folderName" type="text"/>
                        <button class="widget" type="submit" name="newFolder" value="submit"><?php echo("New Folder")?></button>
                    </form>
                </div>
            </div>
        <?php
            }
        ?>
        
    </body>
    <script src="javascript/files.js"></script>
    <script src="javascript/date.js"></script>
</html>
<?PHP
$dao->close();
$load = 'load';
if(isset($_POST["export_pdf"])){
    if(isset($load)){
        ob_clean();
        require_once './mpdf60/mpdf.php';
        /*$timestamp = time();
        $target_dir = "/var/www/i-nucleus.com/main/files/$timestamp/";
        $name = ($size+123456).".pdf";
        mkdir($target_dir);
        $filePath = $target_dir . $name;*/
        //var_dump($sow_table);
        $lang = 'c';
        if(strcmp($client->clientLanguage,"Greek")==0){
            $lang = 'el';
        }
        $mpdf = new mPDF($lang);
        $components = file_get_contents('css/components.css');
        $components .= " ".file_get_contents("css/grid.css");
        $mpdf->WriteHTML($components,1);
        $mpdf->WriteHTML($hours_report_pdf);
        $mpdf->Output("HoursReport.pdf", "D");
    }
}
       
?>