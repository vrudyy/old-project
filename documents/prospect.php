<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileStructure.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileUpload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/DriveFile.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    
    
    if(!isset($_POST["prospectId"])){
        if(!isset($_SESSION["prospectId"])){
            ob_start();
            header('Location: '.'prospects.php');
            ob_end_flush();
            die();
        }
    }
    
    if(!isset($_POST["prospectId"])){
        $prospect = $dao->get("Prospect", $_SESSION["prospectId"], "prospectId");
    }else{
        $prospect = $dao->get("Prospect", $_POST["prospectId"], "prospectId");
    }
    
    $prospectContact = $dao->get("Contact", $prospect->parentId, "contactId");

    if(strcmp($_GET["sec"],"reminders")==0){
        if(isset($_POST["markComplete"])){
            $reminder = $dao->get("Reminder", $_POST["reminderId"], "reminderId");
            $reminder->reminderStatus = 1;
            $dao->update($reminder);
        }
    }
    
    if(strcmp($_GET["sec"],"courses")==0){
        $courses = $dao->listAll("Course", "clientId", $client->clientId, "ASC", "courseName");
        if(isset($_POST["tick"])){
            if(strcmp($_POST["tick"], "untick")==0){
                $prospect->removeCourse($_POST["courseId"]);
                $dao->update($prospect);
            }else if(strcmp($_POST["tick"], "tick")==0){
                $prospect->addCourse($_POST["courseId"]);
                $dao->update($prospect);
            }
        }
    }
    
    if(strcmp($_GET["sec"], "documents")==0){
        
        if(isset($_POST["submit"])){
            if(strcmp($_POST["submit"], "delete-file")==0){
                $fileId = $_POST["fileId"];
                $d_file = $dao->get("File", $fileId, "fileId");
                $dao->delete("File", "fileId", $fileId);
                $fileName = substr($d_file->fileLocation.$d_file->fileName, 23);
                unlink($fileName);
                $folderName = substr($d_file->fileLocation, 23);
                $folderName = substr($folderName, 0, strlen($folderName)-1);
                rmdir($folderName);
            }
        }
        
       
        if(isset($_POST["newFile"])){
            $df = new DriveFile();
            $path = $df->newFile($_FILES["file"]);
            $file = new File();
            $file->clientId = $client->clientId;
            $file->fileAuthor = $contact->contactId;
            $file->fileIsDir = 0;
            $file->fileLocation = $path;
            $file->fileName = $_FILES["file"]["name"];
            if(isset($_POST["folderName"])){
                $file->fileParentFolder = $_POST["folderName"];
            }else{
                $file->fileParentFolder = "documents";
            }
            $file->fileUploadDate = date("Y-m-d H:i:s");
            $file->fileStructureName = "prospect";
            $file->fileStructureId = $prospect->prospectId;
            $dao->add($file);
        }
         
        if(isset($_POST["newFolder"])){
            $file = new File();
            $file->clientId = $client->clientId;
            $file->fileAuthor = $contact->contactId;
            $file->fileIsDir = 1;
            $file->fileLocation = "";
            $file->fileName = $_POST["folderName"];
            $file->fileParentFolder = "documents";
            $file->fileUploadDate = date("Y-m-d H:i:s");
            $file->fileStructureName = "prospect";
            $file->fileStructureId = $prospect->prospectId;
            $dao->add($file);
        }
        
        if(isset($_POST["folderName"])){
            $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
                . "AND `fileStructureName` = 'prospect' "
                . "AND `fileStructureId` = $prospect->prospectId "
                . "AND `fileParentFolder` = '".$_POST["folderName"]."' "
                . "AND `fileIsDir` = 0");
        }else{
            $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
                . "AND `fileStructureName` = 'prospect' "
                . "AND `fileStructureId` = $prospect->prospectId "
                . "AND `fileParentFolder` = 'documents' "
                . "AND `fileIsDir` = 0"); 
        }
        $folders = $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
                . "AND `fileStructureName` = 'prospect' "
                . "AND `fileStructureId` = $prospect->prospectId "
                . "AND `fileParentFolder` = 'documents' "
                . "AND `fileIsDir` = 1");
         
    }
    
    if(strcmp($_GET["sec"], "notes")==0){
        
        
        if(isset($_POST["delete-note"])){
            $noteId = $_POST["noteid"];
            $dao->delete("Note", "noteId", $noteId);
        }

        if(isset($_POST["newnote"])){
            $text = htmlspecialchars($_POST["text"]);
            $textError = Validator::isEmpty($text);
            if(Validator::check($textError)){
                $note = new Note();


                $note->noteDate = date("Y-m-d H:i:s");

                $note->noteAuthor = $contact->contactId;
                $note->noteText = $text;
                
                $note->noteContact = $prospectContact->contactId;
                $dao->add($note);
            }
        }
        $notes = $dao->listAll("Note", "noteContact", $prospectContact->contactId);
    }
    
    $reminders = $dao->listAllWhere("Reminder", "WHERE `prospectId` = $prospect->prospectId AND `reminderStatus` = 0;");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <?php include("content/head.php") ?>
        <link href="css/contact.css" rel="stylesheet" type="text/css"/>
        <style>
            #in-vcard-sec > div > div > div > p:nth-child(3){
                margin: 10px 0;
            }
            #in-notes-sec{
                background: none;
                border: none;
            }
            body > div.wrapper > div.content > form > div.in-row.in-invoice > div:nth-child(7) > div{
                float: left;
                width: 100%;
                margin: 20px 0 0 0;
            }
            body > div.wrapper > div.content > form > div.in-row.in-invoice > div:nth-child(7) > div > div.ck.ck-editor__main > div{
                min-height: 150px;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="in-drive-header col12">
                    <div class="in-fl-l"><?php echo ($dic->translate("Prospect's Details: ").$prospectContact->contactFirstName." ".$prospectContact->contactLastName) ?></div>
                    <?php
                        if(strcmp($_GET["sec"], "documents")==0){
                    ?>
                            <form class="in-fl-r" method="post">
                                <input type="hidden" name="prospectId" value="<?php echo $prospect->prospectId?>"/>
                                <button id="new-button" class="widget"><?php echo $dic->translate("New") ?></button>
                            </form>
                            <div class="in-course-button-menu" id="button-menu">
                                <ul>
                                    <li>
                                        <i class="in-fl-l fa fa-folder"></i>
                                        <form method="post">
                                            <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId) ?>"/>
                                            <button id="newFolderButton" class="in-fl-l" type="submit" name="submit" value="newfolder">
                                                <?php echo($dic->translate("New Folder")) ?>
                                            </button>
                                        </form>
                                    </li>
                                    <li><i class="in-fl-l fa fa-file-text"></i><form method="post"><input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId) ?>"/><button id="newFileButton" class="in-fl-l" type="submit" name="submit" value="newfile"><?php echo($dic->translate("New File Upload")) ?></button></form></li>
                                </ul>
                            </div>
                    <?php
                        }
                        if(strcmp($_GET["sec"], "reminders")==0){
                    ?>
                        <form class="in-fl-r" method="post" action="newreminder.php">
                            <input type="hidden" name="prospectId" value="<?php echo $prospect->prospectId?>"/>
                            <button class="widget"><?php echo $dic->translate("Add New Reminder") ?></button>
                        </form>
                    <?php
                        }
                        if(strcmp($_GET["sec"], "invoices")==0){
                            echo '<form class="in-fl-r" method="post" action="newinvoice.php">';
                                echo '<input type="hidden" name="prospectId" value="'.$prospect->prospectId.'" />';
                                echo '<button name="newinvoice" value="newinvoice" class="widget">'.$dic->translate("Add New Invoice").'</button>';
                            echo '</form>';
                        }
                    ?>
                </div>
                <div class="in-drive-menu col2">
                    <ul>
                        <li class="in-row <?php echo((strcmp("courses", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fab fa-leanpub"></i>
                            <form action="prospect.php?sec=courses" method="post">
                                <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Courses of Interest")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("reminders", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fa-bell"></i>
                            <form action="prospect.php?sec=reminders" method="post">
                                <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Reminders")." (".sizeof($reminders).")"?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("notes", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fas fa-pen-square"></i>
                            <form action="prospect.php?sec=notes" method="post">
                                <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Notes")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("documents", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fa-copy"></i>
                            <form action="prospect.php?sec=documents" method="post">
                                <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Documents")?></button>
                            </form>
                        </li>
                        <li class="in-row <?php echo((strcmp("invoices", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                        <i class="in-fl-l fas fa-dollar-sign"></i>
                            <form action="prospect.php?sec=invoices" method="post">
                                <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId) ?>"/>
                                <button type="submit"><?php echo $dic->translate("Invoices")?></button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="in-drive-files col10" id="drop-area">
                    <?php
                        if(strcmp($_GET["sec"], "courses")==0){
                            include_once 'content/prospect/sec-courses.php';
                        }
                        if(strcmp($_GET["sec"], "reminders")==0){
                            include_once 'content/prospect/sec-reminders.php';
                        }
                        if(strcmp($_GET["sec"], "notes")==0){
                            include_once 'content/prospect/sec-notes.php';
                        }
                        if(strcmp($_GET['sec'], 'documents')==0){
                            include_once 'content/prospect/sec-documents.php';
                        }
                        if(strcmp($_GET['sec'], 'invoices')==0){
                            include_once 'content/prospect/sec-invoice-list.php';
                        }
                    ?>
                </div> 
            </div>
        </div>
        <?php 
            if(strcmp($_GET["sec"], "documents")==0){
                
        ?>
            <div class="in-upload-file-wrapper" id="in-upload-file-wrapper" style="display:none;">
                <div class="in-upload-file" id="in-upload-file">
                    <div><?php echo("Upload File:")?></div>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId)?>">
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
                        <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId)?>">
                        <input style="width: 50%; height: 30px; line-height: 30px; padding: 0 5px;" required name="folderName" type="text"/>
                        <button class="widget" type="submit" name="newFolder" value="submit"><?php echo("New Folder")?></button>
                    </form>
                </div>
            </div>
        <?php
            }
            $dao->close();
        ?>
        <?php include("content/footer.php") ?>
    </body>
    <script src="javascript/files.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
           $('#datepicker1') 
                   .datepicker({
                       format: 'D dd, MM yyyy',
                       startDate: '01 01 2010',
                       endDate:  '12 30 2050'

           });
        });
        $(document).ready(function(){
           $('#datepicker2') 
                   .datepicker({
                       format: 'D dd, MM yyyy',
                       startDate: '01 01 2010',
                       endDate:  '12 30 2050'

           });
        });
    </script>
</html>