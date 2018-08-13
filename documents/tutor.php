<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileStructure.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileUpload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/DriveFile.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
   
    include("content/database.php");
    include("content/session.php");
    $dao = new DAO();
    
    
    $tutor = $dao->get("Tutor", $_POST["tutorId"], "tutorId");
    $tutorContact = $dao->get("Contact", $tutor->contactId, "contactId");
    
    
    
    if(strcmp($_GET["sec"],"approvedcourses")==0){
        $courses = $dao->listAll("Course", "clientId", $client->clientId, "ASC", "courseName");
        if(isset($_POST["tick"])){
            if(strcmp($_POST["tick"], "untick")==0){
                $tutor->removeCourse($_POST["courseId"]);
                $dao->update($tutor);
            }else if(strcmp($_POST["tick"], "tick")==0){
                $tutor->addCourse($_POST["courseId"]);
                $dao->update($tutor);
            }
        }
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
                $note->noteContact = $tutorContact->contactId;
                $dao->add($note);
            }
        }
        $notes = $dao->listAll("Note", "noteContact", $tutorContact->contactId);
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
            $file->fileStructureName = "tutor";
            $file->fileStructureId = $tutor->tutorId;
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
            $file->fileStructureName = "tutor";
            $file->fileStructureId = $tutor->tutorId;
            $dao->add($file);
        }
        
        if(isset($_POST["folderName"])){
        $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
            . "AND `fileStructureName` = 'tutor' "
            . "AND `fileStructureId` = $tutor->tutorId "
            . "AND `fileParentFolder` = '".$_POST["folderName"]."' "
            . "AND `fileIsDir` = 0");
        }else{
            $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
                . "AND `fileStructureName` = 'tutor' "
                . "AND `fileStructureId` = $tutor->tutorId "
                . "AND `fileParentFolder` = 'documents' "
                . "AND `fileIsDir` = 0"); 
        }
        $folders = $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
                . "AND `fileStructureName` = 'tutor' "
                . "AND `fileStructureId` = $tutor->tutorId "
                . "AND `fileParentFolder` = 'documents' "
                . "AND `fileIsDir` = 1");
    }
    
    if(strcmp($_GET['sec'], 'fees')==0){
        $rate = $tutor->tutorRate;
        if(isset($_POST["tutor-rate"])){
            $newRate = $_POST["rate"];
            $tutor->tutorRate = $newRate;
            $dao->update($tutor);
        }
    }
    
    
?>
<!DOCTYPE html>
<html>
    <head>
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
        </style>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="in-drive in-row">
                    <div class="in-drive-header col12">
                        <div class="in-fl-l"><?php echo ($dic->translate("Tutor's Details").": ".$tutorContact->contactFirstName." ".$tutorContact->contactLastName) ?></div>
                        <?php
                            if(strcmp($_GET["sec"], "documents")==0){
                        ?>
                                <form class="in-fl-r" method="post">
                                    <input type="hidden" name="tutorId" value="<?php echo $tutor->tutorId?>"/>
                                    <button id="new-button" class="widget"><?php echo $dic->translate("New") ?></button>
                                </form>
                                <div class="in-course-button-menu" id="button-menu">
                                    <ul>
                                        <li>
                                            <i class="in-fl-l fa fa-folder"></i>
                                            <form method="post">
                                                <input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId) ?>"/>
                                                <button id="newFolderButton" class="in-fl-l" type="submit" name="submit" value="newfolder">
                                                    <?php echo($dic->translate("New Folder")) ?>
                                                </button>
                                            </form>
                                        </li>
                                        <li><i class="in-fl-l fa fa-file-text"></i><form method="post"><input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId) ?>"/><button id="newFileButton" class="in-fl-l" type="submit" name="submit" value="newfile"><?php echo($dic->translate("New File Upload")) ?></button></form></li>
                                    </ul>
                                </div>
                        <?php
                            }
                        
                        ?>
                    </div>
                    <div class="in-drive-menu col2">
                        <ul>
                            <li class="in-row <?php echo((strcmp("approvedcourses", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fab fa-leanpub"></i><form action="tutor.php?sec=approvedcourses" method="post"><input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId) ?>"/><button type="submit"><?php echo $dic->translate("Approved Courses")?></button></form></li>
                            <li class="in-row <?php echo((strcmp("documents", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fas fa-copy"></i><form action="tutor.php?sec=documents" method="post"><input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId) ?>"/><button type="submit"><?php echo $dic->translate("Documents")?></button></form></li>
                            <li class="in-row <?php echo((strcmp("notes", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fas fas fa-pen-square"></i><form action="tutor.php?sec=notes" method="post"><input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId) ?>"/><button type="submit"><?php echo $dic->translate("Notes")?></button></form></li>
                            <li class="in-row <?php echo((strcmp("fees", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fas fas fa-money-bill-alt"></i><form action="tutor.php?sec=fees" method="post"><input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId) ?>"/><button type="submit"><?php echo $dic->translate("Fees")?></button></form></li>
                        </ul>
                    </div>
                    <div class="in-drive-files col10" id="drop-area">
                        <?php
                            if(strcmp($_GET["sec"], "approvedcourses")==0){
                                include_once 'content/tutor-courses.php';
                            }
                            if(strcmp($_GET["sec"], "documents")==0){
                                include_once 'content/tutor-files.php';
                            }
                            if(strcmp($_GET["sec"], "notes")==0){
                                include_once 'content/tutor-notes.php';
                            }
                            if(strcmp($_GET['sec'], 'fees')==0){
                                include_once 'content/tutor-fees.php';
                            }
                        ?>
                    </div> 
                </div>
            </div>
            
        </div>
        <?php 
            if(strcmp($_GET["sec"], "documents")==0){
                
        ?>
            <div class="in-upload-file-wrapper" id="in-upload-file-wrapper" style="display:none;">
                <div class="in-upload-file" id="in-upload-file">
                    <div><?php echo($dic->translate("New File Upload"))?></div>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId)?>">
                        <?php 
                            if(isset($_POST["folderName"])){
                                echo '<input type="hidden" name="folderName" value="'.$_POST["folderName"].'"/>';
                            }

                        ?>
                        <input required name="file" type="file"/>
                        <button class="widget" type="submit" name="newFile" value="submit"><?php echo($dic->translate("Upload"))?></button>
                    </form>
                </div>
            </div>
            <div class="in-upload-file-wrapper" id="in-upload-folder-wrapper" style="display:none;">
                <div class="in-upload-file" id="in-upload-folder">
                    <div><?php echo($dic->translate("Folder name"))?></div>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId)?>">
                        <input style="width: 50%; height: 30px; line-height: 30px; padding: 0 5px;" required name="folderName" type="text"/>
                        <button class="widget" type="submit" name="newFolder" value="submit"><?php echo($dic->translate("Create"))?></button>
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
</html>