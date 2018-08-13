<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include_once("content/session.php");
    include_once 'content/new-prospect-error-placeholders.php';
    include_once 'content/new-prospect-placeholders.php';
    
    $class = $dao->get("inClass", $_POST["classId"], "classId");
    
    if(isset($_POST["save-sow"])){
        foreach($_POST as $key => $value){
            if(strpos($key, "pl") !== false){
                $i = substr($key, 2); 
                $sow = new SoW();
                $sow->classId = $class->classId;
                $sow->sowInovativeTeaching = $_POST["ita".$i];
                $sow->sowNotes = $_POST["n".$i];
                $sow->sowPlannedSyllabus = $_POST["pl".$i];
                $sow->sowResources = $_POST["fl".$i];
                $sow->sowWeekDate = $_POST["d".$i];
                $sow->sowLesson = $_POST["le".$i];
                $dao->add($sow);        
            }
        }
        $_SESSION["classId"] = $class->classId;
        ob_start();
        header('Location: '.'class.php?sec=schemeofwork');
        ob_end_flush();
        die();
    }
    if(isset($_POST["save-files"])){
        foreach($_POST as $key => $value){
            if(strpos($key, "add-resource") !== false){
                unset($_POST[$key]);
            }
        }
    }
    $i = 1;
    if(isset($_POST["new"])){
        while(true){
            if(array_key_exists("pl".$i, $_POST)){
                $i++;
            }else{
                $_POST["pl".$i] = "";
                $_POST["d".$i] = "";
                $_POST["n".$i] = "";
                $_POST["ita".$i] = "";
                $_POST["le".$i] = "";
                $_POST["fl".$i] = "";
                $i++;
                break;
            }
        }
    }
    
    if(isset($_POST["del"])){
        $num = $_POST["del"];
        unset($_POST["pl".$num]);
        unset($_POST["n".$num]);
        unset($_POST["lta".$num]);
    }
    $files = [];
    $courses = explode(";", $class->classAssoicatedCourses);
    foreach($courses as $key => $value){
        if(strcmp($value, "")!=0){
            $file = $dao->listAllWhere("File", 'WHERE `fileStructureName` = "course"  AND fileIsDir = 0 AND `fileStructureId` = '.$value);
            foreach($file as $f){
                array_push($files, $f);
            }
        }
    }
    
    if(isset($_POST["file-remove"])){
        $_POST["fl".$_POST["j"]] = str_replace($_POST["file-remove"].";", "", $_POST["fl".$_POST["j"]]);
    }
    if(isset($_POST["file-add"])){
        $_POST["fl".$_POST["j"]] .= $_POST["file-add"].";";
    }
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
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <form method="post" action="newschemeofwork.php#new">
                    <input type="hidden" name="classId" value="<?PHP echo($_POST["classId"])?>"/>
                    <div class="in-row in-sow-wrapper">
                        <div class="in-row in-sow">
                            <div style="font-size: 18px;" class="in-sow-heading"><?PHP echo($dic->translate("Scheme of Work"))?></div>
                            <div class="in-row in-sow-headers">
                                <div class="col2"><?PHP echo($dic->translate("Date"))?></div>
                                <div class="col3"><?PHP echo($dic->translate("Planned Syllabus"))?></div>
                                <div class="col4"><?PHP echo($dic->translate("Notes"))?></div>
                                <div class="col3"><?PHP echo($dic->translate("Teaching Resources"))?></div>
                            </div>
                            <div class="in-row in-sow-rows">
                                <?PHP 
                                    foreach($_POST as $key => $value){
                                        if(strpos($key, "add-resource") !== false){
                                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
                                            
                                            $resFilesIds = explode(";", $_POST["fl".$value]); 
                                            
                                            echo '<div style="position: absolute;background: rgba(221, 221, 222, 0.41);top: 0;bottom: 0;right: 0;left: 0;z-index: 10;">';
                                               echo '<div class="in-cknt">';
                                                   if(sizeof($files)==0){
                                                       echo '<div style="padding: 10px;">'.$dic->translate("No files available on the associated courses for this class").'</div>';
                                                   }
                                                   foreach($files as $fa){
                                                       if(in_array($fa->fileId, $resFilesIds)){
                                                            echo '<div class="col12 in-ptka">';
                                                                echo '<div style="border: 1px solid black;width: 100%;">';
                                                                    echo '<div>'.$fa->fileName.'</div>';
                                                                    echo '<input type="hidden" name="j" value="'.$value.'"/>';
                                                                    echo '<button name="file-remove" value="'.$fa->fileId.'"><i class="fas fa-check-circle"></i></button>';
                                                                echo '</div>';
                                                            echo '</div>'; 
                                                       }else{
                                                            echo '<div class="col12 in-ptka">';
                                                                echo '<div style="background:white;border: 1px solid black;width: 100%;">';
                                                                    echo '<div>'.$fa->fileName.'</div>';
                                                                    echo '<input type="hidden" name="j" value="'.$value.'"/>';
                                                                    echo '<button name="file-add" value="'.$fa->fileId.'"><i class="fas fa-circle"></i></button>';
                                                                echo '</div>';
                                                            echo '</div>';
                                                       }

                                                   }
                                                   echo '<button name="save-files" id="in-btsn">'.$dic->translate("Save").'</button>';
                                                   
                                               echo '</div>';
                                           echo '</div>';
                                           break;
                                        }
                                        
                                 }
                                    $d = 1;
                                    foreach($_POST as $key => $value){
                                        if(strpos($key, "pl") !== false){
                                            $j = substr($key, 2);
                                            
                                ?>
                                            <div class="in-row in-sow-row">
                                                <div class="col2 input-group input-append date" id="datepicker<?PHP echo($d);$d++;?>">
                                                    <input value="<?PHP echo($_POST["d".$j])?>" style="height: 109px;" type="text" class="form-control col6" name="d<?PHP echo($j)?>" />
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <div class="col3 in-txt"><textarea name="pl<?PHP echo($j)?>"><?PHP echo($_POST["pl".$j])?></textarea></div>
                                                <div class="col4 in-txt"><textarea name="n<?PHP echo($j)?>"><?PHP echo($_POST["n".$j])?></textarea></div>
                                                <div class="col3" style="padding: 0;">
                                                    <div class="in-row in-sow-row-res" style="overflow: auto;height: 80px;border: none;">
                                                        <?PHP 
                                                            $resources = "";
                                                            $files3 = explode(";", $_POST["fl".$j]);
                                                            $files2 = [];
                                                            foreach($files3 as $value){
                                                                if(strcmp($value, "")!=0){
                                                                   
                                                                    $file2 = $dao->get("File", $value, "fileId");
                                                                    array_push($files2, $file2);
                                                                }
                                                            }
                                                            
                                                            foreach($files2 as $fa){
                                                               $resources .= $fa->fileId.";";
                                                                
                                                        ?>
                                                                <div style="height: 20px;border: none;text-align: left;line-height: normal;" class="in-row in-sow-row-ress"><?PHP echo($fa->fileName)?></div>
                                                        <?PHP
                                                        
                                                            }
                                                            echo '<input type="hidden" name="fl'.$j.'" value="'.$resources.'"/>';
                                                            
                                                        ?>
                                                    </div>
                                                    <button value="<?PHP echo($j)?>" name="add-resource<?PHP echo($j)?>" style="background: #2c89ba;color: white;font-weight: 100;height: 30px;line-height: 20px;font-size: 14px;width: 100%;"><?PHP echo($dic->translate("Add Resource"))?></button>
                                                    <?PHP 
                                                        
                                                    
                                                    ?>
                                                </div>
                                            </div>
                                            <button name="del" value="<?PHP echo($j)?>"><i class="fas fa-trash"></i></button>
                                <?PHP
                                        }    
                                    }
                                ?>
                            </div>
                        </div>
                        <button id="new" style="margin: 10px 0 0 0;" class="widget" type="submit" name="new" value="new"><?PHP echo($dic->translate("Add New Lesson"))?></button>
                        <button style="margin: 10px 0 0 0;" class="widget" type="submit" name="save-sow" value="save"><?PHP echo($dic->translate("Save"))?></button>
                    </div>
                </form>
            </div>
        </div>
        <?PHP $dao->close(); ?>
        <?php include("content/footer.php") ?>
    </body>
    <script type="text/javascript">
        $inSowRows = document.getElementsByClassName("in-sow-row");
        for(i = 0; i<$inSowRows.length; i++){
            ext(i+1);
        }
        
        function ext(num){
            $(document).ready(function(){
                $string = "#datepicker"+num;
                $($string) 
                        .datepicker({
                            format: 'D dd, MM yyyy',
                            startDate: '01 01 2010',
                            endDate:  '12 30 2050'

                });
             });
        }
    </script>
</html>