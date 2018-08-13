<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Mailer2.php");
   
    $dao = new DAO();
    include("content/session.php");
    ob_start();
    if(isset($_POST["classId"])){
        $class = $dao->get("inClass", $_POST["classId"], "classId");
    }else if(isset($_SESSION["classId"])){
        $class = $dao->get("inClass", $_SESSION["classId"], "classId");
        unset($_SESSION["classId"]);
    }else{
        $class = new inClass();
    }
    
    #$class = new inClass();
    $sows = $dao->listAll("SoW", "classId", $class->classId);
    
    
    /*
    $timestamp = time();
    $target_dir = "/var/www/i-nucleus.com/main/files/$timestamp/";
    $name = ($size+123456).".pdf";
    mkdir($target_dir);
    $filePath = $target_dir . $name;
    $mpdf = new mPDF("c");
    $mpdf->WriteHTML($invoice);
    $mpdf->Output($filePath, "F");
    */
    
    
    $dao->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        

        
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="in-drive in-row">
                    <div class="in-drive-header col12">
                        <div class="in-fl-l"><?php echo ($dic->translate("Class Details").": ".$class->classLabel) ?></div>
                        <?PHP
                            if(strcmp($_GET['sec'], "classrecord")==0){
                        ?>
                                <form action="newclassrecord.php" method="POST">
                                    <input type="hidden" name="classId" value="<?PHP echo($class->classId) ?>"/>
                                    <button class="in-fl-r widget"><?PHP echo($dic->translate("Add Class Record"))?></button>
                                </form>
                        <?PHP
                            }
                            if(strcmp($_GET['sec'], 'schemeofwork')==0){
                                if(sizeof($sows)==0){
                                    
                                
                        ?>
                                <form action="newschemeofwork.php" method="POST">
                                    <input type="hidden" name="classId" value="<?PHP echo($class->classId) ?>"/>
                                    <button class="in-fl-r widget"><?PHP echo($dic->translate("Add Scheme of Work"))?></button>
                                </form>
                        <?PHP
                                }else{
                        ?>
                                
                                <form action="editschemeofwork.php" method="POST">
                                    <input type="hidden" name="classId" value="<?PHP echo($class->classId) ?>"/>
                                    <button class="in-fl-r widget"><?PHP echo($dic->translate("Edit Scheme of Work"))?></button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="classId" value="<?PHP echo($class->classId) ?>"/>
                                    <button type="submit" name="export_pdf" value="export_pdf" style="margin: 0 5px;" class="in-fl-r widget"><?PHP echo($dic->translate("Export PDF"))?></button>
                                </form>
                        <?PHP
                        
                                }
                            }
                        ?>
                    </div>
                    <div class="in-drive-menu col3">
                        <ul>
                            <li class="in-row <?php echo((strcmp("attendingstudents", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                                <i class="in-fl-l fas fa-users"></i>
                                <form action="class.php?sec=attendingstudents" method="post">
                                    <input type="hidden" name="classId" value="<?php echo($class->classId) ?>"/>
                                    <button type="submit"><?php echo $dic->translate("Attending Students")?></button>
                                </form>
                            </li>
                            <li class="in-row <?php echo((strcmp("classrecord", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                                <i class="in-fl-l fas fa-pencil-alt"></i>
                                <form action="class.php?sec=classrecord" method="post">
                                    <input type="hidden" name="classId" value="<?php echo($class->classId) ?>"/>
                                    <button type="submit"><?php echo $dic->translate("Class Record")?></button>
                                </form>
                            </li>
                            <li class="in-row <?php echo((strcmp("schemeofwork", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                                <i class="in-fl-l fas fa-spinner"></i>
                                <form action="class.php?sec=schemeofwork" method="post">
                                    <input type="hidden" name="classId" value="<?php echo($class->classId) ?>"/>
                                    <button type="submit"><?php echo $dic->translate("Scheme of Work")?></button>
                                </form>
                            </li>
                            <li class="in-row <?php echo((strcmp("associatedcourses", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                                <i class="in-fl-l fab fa-leanpub"></i>
                                <form action="class.php?sec=associatedcourses" method="post">
                                    <input type="hidden" name="classId" value="<?php echo($class->classId) ?>"/>
                                    <button type="submit"><?php echo $dic->translate("Associated Courses")?></button>
                                </form>
                            </li>
                            <li class="in-row <?php echo((strcmp("classcommunication", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                                <i class="in-fl-l fas fa-comments"></i>
                                <form action="class.php?sec=classcommunication" method="post">
                                    <input type="hidden" name="classId" value="<?php echo($class->classId) ?>"/>
                                    <button type="submit"><?php echo $dic->translate("Class Communication")?></button>
                                </form>
                            </li>
                            
                        </ul>
                    </div>
                    <div class="in-drive-files col9" id="drop-area">
                        <?php
                            if(strcmp($_GET["sec"], "attendingstudents")==0){
                                include_once 'content/class/attendingstudents.php';
                            }
                            if(strcmp($_GET["sec"], "classrecord")==0){
                                include_once 'content/class/classrecord.php';
                            }
                            if(strcmp($_GET["sec"], "schemeofwork")==0){
                                include_once 'content/class/schemeofwork.php';
                            }
                            if(strcmp($_GET['sec'], 'associatedcourses')==0){
                                include_once 'content/class/associatedcourses.php';
                            }
                            if(strcmp($_GET['sec'], 'classcommunication')==0){
                                include_once 'content/class/classcommunication.php';
                            }
                        ?>
                    </div> 
                </div>
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
    <script type="text/javascript">
        $(document).ready(function(){
           $('#datepicker1') 
                   .datepicker({
                       format: 'dd/mm/yyyy',
                       startDate: '01 01 2010',
                       endDate:  '12 30 2050'

           });
        });
        $(document).ready(function(){
           $('#datepicker2') 
                   .datepicker({
                       format: 'dd/mm/yyyy',
                       startDate: '01 01 2010',
                       endDate:  '12 30 2050'

           });
        });
    </script>
</html>
<?php
    if(isset($_POST["export_pdf"])){
        ob_end_clean();
        /*$timestamp = time();
        $target_dir = "/var/www/i-nucleus.com/main/files/$timestamp/";
        $name = ($size+123456).".pdf";
        mkdir($target_dir);
        $filePath = $target_dir . $name;*/
        //var_dump($sow_table);
        $mpdf = new mPDF("c");
        $components = file_get_contents('css/components.css');
        $components .= " ".file_get_contents("css/grid.css");
        $mpdf->WriteHTML($components,1);
        $mpdf->WriteHTML($sow_pdf);
        $mpdf->Output("SchemeOfWork.pdf", "D");
    }
?>