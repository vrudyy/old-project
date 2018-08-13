<?PHP
$dao = new DAO();
$student = new Student();
$student = $dao->get("Student", $_POST["studentId"], "studentId");
$studentContact = new Contact();
$studentContact = $dao->get("Contact", $student->contactId, "contactId");
$parentContact = new Contact();
$parentContact = $dao->get("Contact",$student->parentId,"contactId");




        
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
        $file->fileParentFolder = "studentfiles";
    }
    $file->fileUploadDate = date("Y-m-d H:i:s");
    $file->fileStructureName = "student";
    $file->fileStructureId = $student->studentId;
    $dao->add($file);
}

if(isset($_POST["newFolder"])){
    $file = new File();
    $file->clientId = $client->clientId;
    $file->fileAuthor = $contact->contactId;
    $file->fileIsDir = 1;
    $file->fileLocation = "";
    $file->fileName = $_POST["folderName"];
    $file->fileParentFolder = "studentfiles";
    $file->fileUploadDate = date("Y-m-d H:i:s");
    $file->fileStructureName = "student";
    $file->fileStructureId = $student->studentId;
    $dao->add($file);
}

if(isset($_POST["folderName"])){
    $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
        . "AND `fileStructureName` = 'student' "
        . "AND `fileStructureId` = $student->studentId "
        . "AND `fileParentFolder` = '".$_POST["folderName"]."' "
        . "AND `fileIsDir` = 0");
}else{
    $files= $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
        . "AND `fileStructureName` = 'student' "
        . "AND `fileStructureId` = $student->studentId "
        . "AND `fileParentFolder` = 'studentfiles' "
        . "AND `fileIsDir` = 0"); 
}
$folders = $dao->listAllWhere("File", "WHERE `clientId` = $client->clientId "
                . "AND `fileStructureName` = 'student' "
                . "AND `fileStructureId` = $student->studentId "
                . "AND `fileParentFolder` = 'studentfiles' "
                . "AND `fileIsDir` = 1");       
         





?>

<div class="in-drive-folder-section in-row">
    <?php
        if(sizeof($folders) != 0){
            echo("<div>".$dic->translate("Folders").": </div>");
            foreach($folders as $f){
                echo '<div class="in-drive-file-wrapper col2">';
                echo '<form class="in-row" method="post">';
                echo '<input type="hidden" name="studentId" value="'.$student->studentId.'"/>';
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
            $df = new DriveFile();
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
            echo '<div draggable="false" class="in-row in-drive-nf-file" style="width: 49%;margin: 10px 0.5%;border: 1px solid black;background:white;">';
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
                        . '<input type="hidden" name="studentId" value="'.$student->studentId.'"/>'
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