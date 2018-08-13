<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileStructure.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileUpload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/DriveFile.php");
   
    //checking if the user logged in
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    $sections = ["specification", "pastpapers", "examdates"];
    
    if(!in_array($_GET["sec"], $sections)){
        ob_start();
        header('Location: '.'qualifications.php');
        ob_end_flush();
        die();
    }
    
    
    //initialising the DAO
    $dao = new DAO();
    
    
    $qualificationId = $_POST["qualificationId"];
    
    
    
    //getting the client and contact of account owner
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    $qualification = $dao->get("Qualification", $qualificationId, "qualificationId");
    
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
        $file->fileStructureName = "qualification";
        $file->fileStructureId = $qualification->qualificationId;
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
        $file->fileStructureName = "qualification";
        $file->fileStructureId = $qualification->qualificationId;
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
        }else if(strcmp($_POST["submit"], "add-exam")==0){
            $exam = new Exam();
            $exam->qualificationId = $qualification->qualificationId;
            $date = new Date();
            $date->periodToDate($_POST["examDate"]);
            $exam->examDate = $date->toDB();
            $exam->examDescription = $_POST["examDescription"];
            $exam->examCode = $_POST["examCode"];
            $dao->add($exam);
        }
    }
    if(isset($_POST["folderName"])){
        $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
            . "AND `fileStructureName` = 'qualification' "
            . "AND `fileStructureId` = $qualification->qualificationId "
            . "AND `fileParentFolder` = '".$_POST["folderName"]."' "
            . "AND `fileIsDir` = 0");
    }else{
        $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
            . "AND `fileStructureName` = 'qualification' "
            . "AND `fileStructureId` = $qualification->qualificationId "
            . "AND `fileParentFolder` = '".$_GET["sec"]."' "
            . "AND `fileIsDir` = 0"); 
    }
    
    
    
    $folders = $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
            . "AND `fileStructureName` = 'qualification' "
            . "AND `fileStructureId` = $qualification->qualificationId "
            . "AND `fileParentFolder` = '".$_GET["sec"]."' "
            . "AND `fileIsDir` = 1");
    $exams = $dao->listAll("Exam", "qualificationId", $qualification->qualificationId);
    $dao->close();
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
        
    </head>
    <body>
        <div class="wrapper" style="background: rgb(250,250,250);">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="in-drive-wrapper in-row">
                    <div class="in-drive in-row">
                        <div class="in-drive-header col12">
                            <div style="font-size: 20.4px;" class="in-fl-l"><?php echo($dic->translate("Qualification Name: ") . $qualification->qualificationName) ?></div>
                            <?php 
                                if(strcmp("examdates", $_GET["sec"])==0){
                                    echo '<form method="post" class="in-fl-r">';
                                    echo '<input type="hidden" name="qualificationId" value="'.$qualification->qualificationId.'" />';
                                    echo '<button class="widget" type="submit" name="submit" value="new-exam-date">'.$dic->translate("New Exam Date").'</button>';
                                    echo '</form>';
                                }else{
                                    echo '<form class="in-fl-r" method="post">';
                                    echo '<button id="new-button" class="widget">'.$dic->translate("New").'</button>';
                                    echo '</form>';
                                }
                            ?>
                            <div class="in-course-button-menu" id="button-menu">
                                <ul>
                                    <li>
                                        <i class="in-fl-l fa fa-folder"></i>
                                        <form method="post">
                                            <input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId) ?>"/>
                                            <button style="line-height:15px; font-size:13px;" id="newFolderButton" class="in-fl-l" type="submit" name="submit" value="newfolder">
                                                <?php echo($dic->translate("New Folder")) ?>
                                            </button>
                                        </form>
                                    </li>
                                    <li><i class="in-fl-l fa fa-file-text"></i><form method="post"><input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId) ?>"/><button style="line-height:15px; font-size:13px;" id="newFileButton" class="in-fl-l" type="submit" name="submit" value="newfile"><?php echo($dic->translate("New File Upload")) ?></button></form></li>
                                </ul>
                            </div>
                        </div>
                        <div class="in-drive-menu col2">
                            <ul>
                                <li class="in-row <?php echo((strcmp("specification", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fas fa-compass"></i><form action="qualification.php?sec=specification" method="post"><input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId) ?>"/><button type="submit"><?php echo $dic->translate("Specification")?></button></form></li>
                                <li class="in-row <?php echo((strcmp("pastpapers", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fas fa-file"></i><form action="qualification.php?sec=pastpapers" method="post"><input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId) ?>"/><button type="submit"><?php echo $dic->translate("Past Papers")?></button></form></li>
                                <li class="in-row <?php echo((strcmp("examdates", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>"><i class="in-fl-l fa fa-calendar-check"></i><form action="qualification.php?sec=examdates" method="post"><input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId) ?>"/><button type="submit"><?php echo $dic->translate("Exam Dates")?></button></form></li>
                            </ul>
                        </div>
                        <div class="in-drive-files col10" id="drop-area">
                            <?php 
                                if(strcmp("examdates", $_GET["sec"])!=0){
                            ?>
                            <div class="in-drive-folder-section in-row">
                                <?php 
                                    if(sizeof($folders) != 0){
                                        echo("<div>".$dic->translate("Folders").": </div>");
                                        foreach($folders as $f){
                                            echo '<div class="in-drive-file-wrapper col2">';
                                            echo '<form class="in-row" method="post">';
                                            echo '<input type="hidden" name="qualificationId" value="'.$qualification->qualificationId.'"/>';
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
                                                    . '<input type="hidden" name="qualificationId" value="'.$qualification->qualificationId.'"/>'
                                                    . (isset($_POST["folderName"])? '<input type="hidden" name="folderName" value="'.$_POST["folderName"].'" >': "")
                                                    . '<input type="hidden" name="fileId" value="'.$f->fileId.'" />'
                                                    . '<a download href="'.substr($f->fileLocation.$f->fileName, 23).'" type="submit" name="submit" value="download-file" style=""><i class="fas fa-download"></i>'." ".$dic->translate("Download").'</a>'
                                                    . '<button type="submit" name="submit" value="delete-file" style="margin: 0 10px;"><i class="fas fa-trash"></i>'." ".''.$dic->translate("Delete File").'</button>'        
                                                    . '</form>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                        
                                    }
                                }else{
                                    if(strcmp($_POST["submit"], "new-exam-date")==0){
                                ?>
                                <!--  NEW_EXAM_FORM   -->
                                <form id="detailsForm" action="" method="post">
                                    <input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId)?>"/>
                                    <div class="in-row settingsSection vsec" style="border: none;">
                                        <div class="col12">
                                            <h3><?php echo($dic->translate("Exam Details")) ?></h3>
                                            <div class="settingsSubSection">
                                                <label class="col3"><?php echo($dic->translate("Exam Code/Exam Name")) ?> <span style="color:red;">*</span></label>
                                                <input required value="<?php echo $schoolYearTitle ?>" class="col4" type="text" name="examCode">
                                                <p class="error"><?php echo $schoolYearTitleError ?></p>
                                            </div>
                                            <div class="settingsSubSection">
                                                <label class="col3"><?php echo($dic->translate("Exam Date")) ?> <span style="color:red;">*</span></label>
                                                <div class="input-group input-append date col4" id="datepicker1" style="padding: 0;">
                                                    <input value="<?php echo $schoolYearStart ?>" required type="text" class="form-control" name="examDate" />
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <p class="error"><?php echo $schoolYearStartError ?></p>
                                            </div>
                                             <div class="settingsSubSection" >
                                                <label class="col3"><?php echo($dic->translate("Exam Description")) ?> <span style="color:red;">*</span></label>
                                                <textarea rows="4" cols="50" required="" class="col6" name="examDescription" style="height: 200px;"></textarea>
                                                <p class="error"><?php echo $schoolYearTitleError ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <button name="submit" value="add-exam" type="submit" class="widget left"><?php echo($dic->translate("Add Exam")) ?></button>
                                    <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                                </form>
                                <!--  END_NEW_EXAM_FORM   -->
                                <?php
                                    }else{
                                ?>
                                <div class="col8" style="padding: 0;margin: 0 16.6%;border-top: 1px solid gray;border-left: 1px solid gray;border-right: 1px solid gray;">
                                    <div class="in-row rooms">
                                        <div><?php echo($dic->translate("Exam Dates"))?></div>
                                    </div>
                                    <div class="in-row room roomHead">
                                        <ul class="in-row" style="color:white;">
                                            <li class="col3">
                                                <?echo $dic->translate("Exam Code/Exam Name")?>
                                            </li>
                                            <li class="col3">
                                                <?echo $dic->translate("Exam Date")?>
                                            </li>
                                            <li class="col6">
                                                <?echo $dic->translate("Exam Description")?>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php 
                                        for($i = 0; $i<sizeof($exams); $i++){
                                    ?>
                                    <div class="in-row room" style="border-bottom: 1px solid gray;">
                                        <ul class="in-row <?php echo(($i%2==0)? "ug": "mg")?>">
                                            <li class="col3">
                                                <?php echo($exams[$i]->examCode)?>
                                            </li>
                                            <li class="col3">
                                               <?php 
                                                    $date = new Date();
                                                    $date->fromInput($exams[$i]->examDate);
                                               echo($date->longDate())
                                               ?>
                                            </li>
                                            <li class="col6">
                                               <?php echo($exams[$i]->examDescription)?>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <?php
                                    }
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
                    <input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId)?>">
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
                    <input type="hidden" name="qualificationId" value="<?php echo($qualification->qualificationId)?>">
                    <input style="width: 50%; height: 30px; line-height: 30px; padding: 0 5px;" required name="folderName" type="text"/>
                    <button class="widget" type="submit" name="newFolder" value="submit"><?php echo("New Folder")?></button>
                </form>
            </div>
        </div>
        <?php include("content/footer.php") ?>
        <script type="text/javascript">
            $(document).ready(function(){
               $('#datepicker1') 
                       .datepicker({
                           format: 'D dd, MM yyyy',
                           startDate: '01 01 2010',
                           endDate:  '12 30 2050'

               });
            });
        </script>
        <script>
            var n = "not";
            
            var bNew = document.getElementById("new-button");
            var bMenu = document.getElementById("button-menu");
            var body = document.getElementsByTagName("BODY")[0];
            var uploadFileWrapper = document.getElementById("in-upload-file-wrapper");
            var uploadFile = document.getElementById("in-upload-file");
            var uploadFolderWrapper = document.getElementById("in-upload-folder-wrapper");
            var uploadFolder = document.getElementById("in-upload-folder");
            var newFolderButton = document.getElementById("newFolderButton");
            var newFileButton = document.getElementById("newFileButton");
            
            //console.log(newFileButton);
            //console.log(newFolderButton);
            
            newFileButton.addEventListener("click", function(e){
                e.preventDefault();
                uploadFileWrapper.style.display = "initial";
            });
            
            newFolderButton.addEventListener("click", function(e){
                e.preventDefault();
                uploadFolderWrapper.style.display = "initial";
            });
            
            uploadFileWrapper.style.display = "none";
            uploadFolderWrapper.style.display = "none";
                
                
            
            uploadFileWrapper.addEventListener("click", function(e){
                uploadFileWrapper.style.display = "none";
            });
            
            uploadFolderWrapper.addEventListener("click", function(e){
                uploadFolderWrapper.style.display = "none";
            });
            
            uploadFile.addEventListener("click", function(e){
                e.stopPropagation();
            });
            uploadFolder.addEventListener("click", function(e){
                e.stopPropagation();
            });
            
            
            
            
            body.addEventListener("click", function(e){
                console.log("body");
                if(n === "yes"){
                    bMenu.style.display = "none";
                    n = "not";
                }
            });
            
            bNew.addEventListener("click", function(e){
                e.preventDefault();
                e.stopPropagation();
                console.log("button");
                if(n === "not"){
                    bMenu.style.display = "initial";
                    n = "yes";
                }else{
                    bMenu.style.display = "none";
                    n = "not";
                }
            });
            
            
            $filesCol = document.getElementsByClassName("in-drive-nf-file");
            $files = Array.prototype.slice.call($filesCol);
            for(i = 0; i<$files.length; i++){
                $files[i].addEventListener("drag", function(e){
                   e.stopPropagation();
                   e.dataTransfer.effectAllowed = "copyMove";
                   $element = e.target;
                   while($files.indexOf($element) === -1){
                       $element = $element.parentElement;
                   }
                });
            } 
        </script>
    </body>
</html>