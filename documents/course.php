<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileStructure.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileUpload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/DriveFile.php");
   
    //checking if the user logged in
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //initialising the DAO
    $dao = new DAO();
    
    $courseId = $_POST["courseId"];
    
    
    
    //getting the client and contact of account owner
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    $course = $dao->get("Course", $courseId, "courseId");
    
    //closing connection;
    
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
    
    $df = new DriveFile();
    
    if(isset($_POST["newFile"])){
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
            $file->fileParentFolder = $_GET["sec"];
        }
        $file->fileUploadDate = date("Y-m-d H:i:s");
        $file->fileStructureName = "course";
        $file->fileStructureId = $course->courseId;
        $dao->add($file);
    }
    
    if(isset($_POST["newFolder"])){
        $file = new File();
        $file->clientId = $client->clientId;
        $file->fileAuthor = $contact->contactId;
        $file->fileIsDir = 1;
        $file->fileLocation = "";
        $file->fileName = $_POST["folderName"];
        $file->fileParentFolder = $_GET["sec"];
        $file->fileUploadDate = date("Y-m-d H:i:s");
        $file->fileStructureName = "course";
        $file->fileStructureId = $course->courseId;
        $dao->add($file);
    }
    
    if(isset($_POST["submit"])){
        //this is when the user clicks to remove file
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
    
    if(isset($_POST["folderName"])){
        $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
            . "AND `fileStructureName` = 'course' "
            . "AND `fileStructureId` = $course->courseId "
            . "AND `fileParentFolder` = '".$_POST["folderName"]."' "
            . "AND `fileIsDir` = 0");
    }else{
        $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
            . "AND `fileStructureName` = 'course' "
            . "AND `fileStructureId` = $course->courseId "
            . "AND `fileParentFolder` = '".$_GET["sec"]."' "
            . "AND `fileIsDir` = 0"); 
    }
    
    
    
    $folders = $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
            . "AND `fileStructureName` = 'course' "
            . "AND `fileStructureId` = $course->courseId "
            . "AND `fileParentFolder` = '".$_GET["sec"]."' "
            . "AND `fileIsDir` = 1");
    
    $dao->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        
    </head>
    <body>
        <div class="wrapper" style="background: rgb(250,250,250);">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="in-drive-wrapper in-row">
                    <div class="in-drive in-row">
                        <div class="in-drive-header col12">
                            <div class="in-fl-l"><?php echo($dic->translate("Course Name").": ".$course->courseName) ?></div>
                            <form class="in-fl-r" method="post">
                                <button id="new-button" class="widget"><?php echo $dic->translate("New") ?></button>
                            </form>
                            <div class="in-course-button-menu" id="button-menu">
                                <ul>
                                    <li>
                                        <i class="in-fl-l fa fa-folder"></i>
                                        <form method="post">
                                            <input type="hidden" name="courseId" value="<?php echo($course->courseId) ?>"/>
                                            <button id="newFolderButton" class="in-fl-l" type="submit" name="submit" value="newfolder">
                                                <?php echo($dic->translate("New Folder")) ?>
                                            </button>
                                        </form>
                                    </li>
                                    <li><i class="in-fl-l fa fa-file-text"></i><form method="post"><input type="hidden" name="courseId" value="<?php echo($course->courseId) ?>"/><button id="newFileButton" class="in-fl-l" type="submit" name="submit" value="newfile"><?php echo($dic->translate("New File Upload")) ?></button></form></li>
                                </ul>
                            </div>
                        </div>
                        <div class="in-drive-menu col2">
                            <ul>
                                <li class="in-row <?php echo((strcmp("teachingnotes", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fa fa-file-text"></i><form action="course.php?sec=teachingnotes" method="post"><input type="hidden" name="courseId" value="<?php echo($course->courseId) ?>"/><button type="submit"><?php echo $dic->translate("Teaching Notes")?></button></form></li>
                                <li class="in-row <?php echo((strcmp("tests", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fa fa-tasks"></i><form action="course.php?sec=tests" method="post"><input type="hidden" name="courseId" value="<?php echo($course->courseId) ?>"/><button type="submit"><?php echo $dic->translate("Tests")?></button></form></li>
                                <li class="in-row <?php echo((strcmp("revisionnotes", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fa fa-refresh"></i><form action="course.php?sec=revisionnotes" method="post"><input type="hidden" name="courseId" value="<?php echo($course->courseId) ?>"/><button type="submit"><?php echo $dic->translate("Revision Notes")?></button></form></li>
                                <li class="in-row <?php echo((strcmp("revisionexercises", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fa fa-random"></i><form action="course.php?sec=revisionexercises" method="post"><input type="hidden" name="courseId" value="<?php echo($course->courseId) ?>"/><button type="submit"><?php echo $dic->translate("Revision Exercises")?></button></form></li>
                                <li class="in-row <?php echo((strcmp("other", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fab fa-bitbucket"></i><form action="course.php?sec=other" method="post"><input type="hidden" name="courseId" value="<?php echo($course->courseId) ?>"/><button type="submit"><?php echo $dic->translate("Other")?></button></form></li>
                            </ul>
                        </div>
                        <div class="in-drive-files col10" id="drop-area">
                            <div class="in-drive-folder-section in-row">
                                <?php 
                                    if(sizeof($folders) != 0){
                                        echo("<div>".$dic->translate("Folders").": </div>");
                                        foreach($folders as $f){
                                            echo '<div class="in-drive-file-wrapper col2">';
                                            echo '<form class="in-row" method="post">';
                                            echo '<input type="hidden" name="courseId" value="'.$course->courseId.'"/>';
                                            echo '<input type="hidden" name="folderName" value="'.$f->fileName.'"/>';
                                            echo '<button class="in-drive-file in-row';
                                            if(isset($_POST["folderName"]) && strcmp($f->fileName, $_POST["folderName"])==0){
                                                echo " in-drive-file-selected";
                                            }
                                            echo '">';
                                            echo '<i class="in-fl-l fas fa-folder"></i>';
                                            echo '<div class="in-fl-l" title="'.$f->fileName.'">'.$f->fileName.'</div>';
                                            echo '</button>';
                                            echo '</form>';
                                            echo '</div>';
                                         }
                                    }
                                ?>
                            </div>
                            <div class="in-drive-file-section in-row">
                                <?php echo("<div>".$dic->translate("Files").": </div>")?>
                                <?php
                                    
                                    foreach($files as $f){
                                        $url = $df->getImageLocation($df->getExtension($f->fileName));
                                        $fileSize = filesize(substr($f->fileLocation.$f->fileName,23));
                                        if($fileSize > 1000000){
                                            $fileSize = round($fileSize/1000000, 1);
                                            $fileSize .= " MB";
                                        }elseif($fileSize > 1000){
                                            $fileSize = round($fileSize/1000, 1);
                                            $fileSize .= " kB";
                                        }else{
                                            $fileSize .= " byte";
                                        }
                                        echo '<div draggable="true" class="in-row in-drive-nf-file" style="width: 49%;margin: 10px 0.5%;border: 1px solid black;background:white;">';
                                            echo '<div>';
                                                echo '<div class="in-row">';
                                                    echo '<img draggable="false" class="col1" style="height: 70px; width: 60px;" src="'.$url.'"/>';
                                                    echo '<div title="'.$f->fileName.'" style="overflow:hidden;line-height: 50px;height:50px;" class="col0">'.$f->fileName.'</div>';
                                                echo '</div>';
                                                echo '<div>';
                                                    echo('<div class="col12" style="font-size: 12px;padding: 0 5px;">'.
                                                            $dic->translate("Uploaded on : ").
                                                            $f->fileUploadDate.
                                                            ' '.$dic->translate("by").' '.
                                                            $contact->contactFirstName.' '.$contact->contactLastName.
                                                            ' | '.$dic->translate("Size").' : '.$fileSize.
                                                            '</div>');
                                                    echo '<form class="in-row" method="post" style="padding: 10px 5px 5px 5px;">'
                                                    . '<input type="hidden" name="courseId" value="'.$course->courseId.'"/>'
                                                    . (isset($_POST["folderName"])? '<input type="hidden" name="folderName" value="'.$_POST["folderName"].'" >': "")
                                                    . '<input type="hidden" name="fileId" value="'.$f->fileId.'" />'
                                                    . '<a download href="'.substr($f->fileLocation.$f->fileName, 23).'" type="submit" name="submit" value="download-file" style=""><i class="fas fa-download"></i>'." ".$dic->translate("Download").'</a>'
                                                    . '<button type="submit" name="submit" value="delete-file" style="margin: 0 10px;"><i class="fas fa-trash"></i>'." ".''.$dic->translate("Delete File").'</button>'        
                                                    . '</form>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                        
                                    }

                                ?>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="in-upload-file-wrapper" id="in-upload-file-wrapper">
            <div class="in-upload-file" id="in-upload-file">
                <div><?php echo("Upload File:")?></div>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="courseId" value="<?php echo($course->courseId)?>">
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
        <div class="in-upload-file-wrapper" id="in-upload-folder-wrapper">
            <div class="in-upload-file" id="in-upload-folder">
                <div><?php echo("New Folder:")?></div>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="courseId" value="<?php echo($course->courseId)?>">
                    <input style="width: 50%; height: 30px; line-height: 30px; padding: 0 5px;" required name="folderName" type="text"/>
                    <button class="widget" type="submit" name="newFolder" value="submit"><?php echo("New Folder")?></button>
                </form>
            </div>
        </div>
        <?php include("content/footer.php") ?>
        <script src="javascript/files.js"></script>
    </body>
</html>