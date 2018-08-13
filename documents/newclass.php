<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include_once("content/session.php");
    
    $branches = $dao->listAll("Branch", "clientId", $client->clientId);
    $tutors = $dao->listAll("Tutor", "clientId", $client->clientId);
    $classCoordinators = $dao->listAllWhere("User", " WHERE `clientId` = $client->clientId AND (`roleId` = 2 OR `roleId` = 4);");
    
    $classLabel = "";
    
    if(isset($_POST["add"])){
        $classLabel = $_POST['classLabel'];
        $branch = $_POST['classBranch'];
        $room = $_POST['classRoom'];
        $tutor = $_POST['classTutor'];
        $academicCoordinator = $_POST['classAcademicCoordinator'];
        
        
        $classLabelError = Validator::isEmpty($classLabel, "Class Label");
        $class = $dao->listAllWhere("inClass", "WHERE `clientId` = $client->clientId AND `branchId` = $branch AND upper(`classLabel`) like '%". strtoupper($classLabel)."%'");
        if(sizeof($class)>0){
            $classLabelError = $dic->translate("There is already a class with that name. Consider a different class label");
        }
        
        if($branch == 0){
            $classBranchError = $dic->translate("Please Select a branch");
        }
        
        if($room == 0){
            $classRoomError = $dic->translate("Please Select a room");
        }
        
        if($tutor == 0){
            $classTutorError = $dic->translate("Please Select a tutor");
        }
        
        if($academicCoordinator == 0){
            $academicCoordinatorError = $dic->translate("Please Select an Academic Coordinator");
        }
        if(Validator::check($classLabelError, $classBranchError, $classRoomError, $classTutorError, $academicCoordinatorError)){
            $class = new inClass();
            $class->classAcademicCoordinator = $academicCoordinator;
            $class->branchId = $branch;
            if(isset($_POST['isPrivate'])){
                $class->classIsPrivate = 1;
            }else{
                $class->classIsPrivate = 0; 
            }
            $class->classLabel = $classLabel;
            $class->classStatus = 1;
            $class->roomId = $room;
            $class->tutorId = $tutor;
            $class->clientId = $client->clientId;
            $dao->add($class);
            ob_start();
            header('Location: '.'classes.php');
            ob_end_flush();
            die();
        }
        
    }
    
   
    

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <?php include("content/head.php") ?>
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
                <div class="in-row in-settings-wrapper">
                    <form method="post" class="in-row">
                        <input id="cknt" type="hidden" name="cknt" value="<?php echo($client->clientId)?>"/>
                        <div class="in-row in-settings">
                            <div class="col6">
                                <h3><?php echo($dic->translate("Class Details")) ?></h3>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Class Label").":")?><span class="cred ml5">*</span></label>
                                    <input required class="col5 p5" type="text" name="classLabel" value="<?php echo($classLabel)?>"/>
                                    <p class="col8 in-error m0"><?php echo($classLabelError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Class Branch").":")?><span class="cred ml5">*</span></label>
                                    <select class="col5 p5" name="classBranch" id="classBranch">
                                        <?php 
                                            echo '<option value="0">'.$dic->translate("Select Class Branch").'</option>';
                                            foreach($branches as $b){
                                                echo '<option value="'.$b->branchId.'">'.$b->branchName.'</option>';
                                            }
                                        ?>
                                    </select>
                                    <p class="col8 in-error m0"><?php echo($classBranchError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Room").":")?><span class="cred ml5">*</span></label>
                                    <select required class="col5 p5" name="classRoom" id="classRoom">
                                        <option value="0"><?php echo($dic->translate("Please Select Branch First"))?></option>
                                    </select>
                                    <p class="col8 in-error m0"><?php echo($classRoomError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Tutor").":")?><span class="cred ml5">*</span></label>
                                    <select required class="col5 p5" name="classTutor">
                                        <?php 
                                            echo '<option value="0">'.$dic->translate("Select Class Tutor").'</option>';
                                            foreach($tutors as $t){
                                                $tutorContact = $dao->get("Contact", $t->contactId, "contactId");
                                                echo '<option value="'.$t->tutorId.'">'.$tutorContact->contactFirstName.' '.$tutorContact->contactLastName.'</option>';
                                            }
                                        ?>
                                    </select>
                                    <p class="col8 in-error m0"><?php echo($classTutorError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Academic Coordinator").":")?><span class="cred ml5">*</span></label>
                                    <select required class="col5 p5" name="classAcademicCoordinator">
                                        <?php 
                                            echo '<option value="0">'.$dic->translate("Select Class Academic Coordinator").'</option>';
                                            foreach($classCoordinators as $c){
                                                $userContact = $dao->get("Contact", $c->contactId, "contactId");
                                                echo '<option value="'.$c->userId.'">'.$userContact->contactFirstName.' '.$userContact->contactLastName.'</option>';
                                            }
                                        ?>
                                    </select>
                                    <p class="col8 in-error m0"><?php echo($academicCoordinatorError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("This is a private class"))?></label>
                                    <input class="col5 p5" type="checkbox" name="isPrivate" value="1"/>
                                    <p class="col8 in-error m0"></p>
                                </div>
                            </div>
                            
                        </div>
                        <button class="widget mt10" type="submit" name="add" value="add"><?php echo $dic->translate("Add Class")?></button>
                        <button class="widget cwidget mt10" type="reset" value="reset" name="reset"><?php echo $dic->translate("Clear")?></button>
                    </form>
                </div>
                <div style="clear: both; height: 120px;"></div> 
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
    <script src="javascript/date.js"></script>
    <script>
        var classBranch = document.getElementById("classBranch");
        var cknt = document.getElementById("cknt");
        classBranch.addEventListener("change", function(e){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
               document.getElementById("classRoom").innerHTML = this.responseText;
              }
            };
            xhttp.open("GET", "php/javascript/getRoomOptions.php?cknd="+classBranch.value+"&cknt="+cknt.value, true);
            xhttp.send(); 
        });
    </script>
</html>
<?php
    $dao->close();
?>