<div class="in-drive-folder-section in-row">
    <?php
        if(sizeof($folders) != 0){
            echo("<div>".$dic->translate("Folders").": </div>");
            foreach($folders as $f){
                echo '<div class="in-drive-file-wrapper col2">';
                echo '<form class="in-row" method="post">';
                echo '<input type="hidden" name="prospectId" value="'.$prospect->prospectId.'"/>';
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
                        . '<input type="hidden" name="prospectId" value="'.$prospect->prospectId.'"/>'
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