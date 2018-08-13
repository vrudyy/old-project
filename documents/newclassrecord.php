<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include_once("content/session.php");
    
    $class = $dao->get("inClass", $_POST["classId"], "classId");
    
    $classStudents = $dao->listAll("StudentClass", "classId", $class->classId);
    $students = [];
    foreach($classStudents as $c){
       $student = $dao->get("Student", $c->studentId, "studentId"); 
       $student = $dao->get("Contact", $student->contactId, "contactId");
       array_push($students, $student);
    }
    
    //initiallising error variables
    $classDateError = "";
    $classHoursError = "";
    $syllabusTaughtError = "";
    $homeworkAssignedError = "";
    
    //initiallising placeholder variables
    $classDate = date("D d, F Y");
    
    $syllabusTaught = "";
    $homeworkAssigned = "";
    
    $rooms = $dao->listAll("Room", "branchId", $class->branchId);
    
    
    if(isset($_POST["add"])){
        $classDateError = "";
        $syllabusTaughtError = Validator::isEmpty($_POST["syllabusTaught"], "Syllabus Taught");
        $homeworkAssignedError = Validator::isEmpty($_POST["homeworkAssigned"], "Homework Assigned");
        
        $classDate = $_POST["classDate"];
        $syllabusTaught = $_POST["syllabusTaught"];
        $homeworkAssigned = $_POST["homeworkAssigned"];
        
        if(Validator::check($classDateError, $classHoursError, $syllabusTaughtError, $homeworkAssignedError)){
            $classRecord = new ClassRecord();
            $classRecord->classId = $class->classId;
            $date = new Date();
            $date->periodToDate($_POST["classDate"]);
            $classRecord->classRecordDate = $date->toDB();
            $classRecord->classRecordHomework = $_POST["homeworkAssigned"];
            $classRecord->classRecordHours = $_POST["hours"].".".$_POST["minutes"];
            $classRecord->classRecordRoom = $_POST["room"];
            $classRecord->classRecordSyllabus = $_POST["syllabusTaught"];
            $classRecord->classRecordTutor = $class->tutorId;
            $id = $dao->add($classRecord);
            
            
            foreach($students as $key => $s){
                if(isset($_POST["hw".$key])){
                    $cs = new ClassStudent();
                    $cs->contactId = $s->contactId;
                    $cs->classStudentAttendance = $_POST["atten".$key];
                    $cs->classStudentHomework = $_POST["hw".$key];
                    $cs->classStudentConcentration = $_POST["conc".$key];
                    $cs->classStudentParticipation = $_POST["part".$key];
                    $cs->classStudentComments = $_POST["comm".$key];
                    $cs->classId = $class->classId;
                    $cs->classRecordId = $id;
                    $dao->add($cs);
                }else{
                    break;
                }
            }
            
            
            
            $_SESSION["classId"] = $class->classId;
            ob_start();
            header('Location: '.'class.php?sec=classrecord');
            ob_end_flush();
            die();
             
             
        }
    }
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
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="in-row in-settings-wrapper">
                    <form method="post" class="in-row">
                        <div style="padding-top: 20px;" class="in-row in-settings">
                            <input type="hidden" name="classId" value="<?PHP echo($class->classId)?>"/>
                            <div class="col6">
                                <h3 style="margin: 0 0 10px 0;"><?PHP echo($dic->translate("Class record"))?></h3>
                                <div class="in-setting-sec in-row">
                                    <label class="col3"><?php echo($dic->translate("Class Date")) ?> <span style="color:red;">*</span></label>
                                    <div style="padding: 0;" class="col6 input-group input-append date" id="datepicker1">
                                        <input value="<?php echo($classDate)?>" required type="text" class="form-control col6" name="classDate" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <p class="error"><?php echo $classDateError ?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Room").":")?></label>
                                    <select class="col6 p5" name="room">
                                        <?php
                                            
                                            foreach($rooms as $r){
                                                #$r = new Room();
                                                echo '<option value="'.$r->roomId.'">'.$r->roomName.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label required class="col3 m0"><?php echo($dic->translate("Class Hours").":")?><span class="cred ml5">*</span></label>
                                    <label><?php echo($dic->translate("Hours"))?></label>
                                    <select name="hours">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                    <label><?php echo($dic->translate("Minutes"))?></label>
                                    <select name="minutes">
                                        <option value="0">0</option>
                                        <option value="05">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                        <option value="55">55</option>
                                        
                                    </select>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Syllabus Taught").":")?><span class="cred ml5">*</span></label>
                                    <textarea required style="resize: none;width: 75%;min-height: 100px;" class="col6 p5" type="text" name="syllabusTaught" value=""><?php echo($syllabusTaught)?></textarea>
                                    <p class="col8 in-error m0"><?php echo($syllabusTaughtError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Homework Assigned").":")?><span class="cred ml5">*</span></label>
                                    <textarea required style="resize: none;width: 75%;min-height: 100px;" class="col6 p5" type="text" name="homeworkAssigned" value=""><?php echo($homeworkAssigned)?></textarea>
                                    <p class="col8 in-error m0"><?php echo($homeworkAssignedError)?></p>
                                </div>
                                <h3><?PHP echo($dic->translate("Attending Students"))?></h3>
                            </div>
                            <div class="in-row" style="font-weight: 700;text-align: center;">
                                <div class="col2"><?PHP echo($dic->translate("Student Name"))?></div>
                                <div class="col2"><?PHP echo($dic->translate("Attendance"))?></div>
                                <div class="col2"><?PHP echo($dic->translate("Homework"))?></div>
                                <div class="col2"><?PHP echo($dic->translate("Concentration"))?></div>
                                <div class="col2"><?PHP echo($dic->translate("Participation"))?></div>
                                <div class="col2"><?PHP echo($dic->translate("Comments"))?></div>
                                <div id="classRecordStudent" class="in-row">
                                    <?PHP 
                                    foreach($students as $key => $s){
                                       # $s = new Contact();
                                ?>
                                
                                <div style="clear: both;" class="col2"><?PHP echo($s->fullName())?></div>
                                <div class="col2">
                                    <select name="atten<?php echo($key)?>">
                                        <option value="1"><?PHP echo($dic->translate("Present"))?></option>
                                        <option value="2"><?PHP echo($dic->translate("Late"))?></option>
                                        <option value="3"><?PHP echo($dic->translate("Absent"))?></option>
                                        <option value="4"><?PHP echo($dic->translate("Justified Late"))?></option>
                                        <option value="5"><?PHP echo($dic->translate("Justified Absent"))?></option>
                                    </select>
                                </div>
                                <div class="col2">
                                    <select name="hw<?php echo($key)?>">
                                        <option value="1"><?PHP echo($dic->translate("Poor"))?></option>
                                        <option value="2"><?PHP echo($dic->translate("Satisfactory"))?></option>
                                        <option selected value="3"><?PHP echo($dic->translate("Good"))?></option>
                                        <option value="4"><?PHP echo($dic->translate("Excellent"))?></option>
                                    </select>
                                </div>
                                <div class="col2">
                                    <select name="conc<?php echo($key)?>">
                                        <option value="1"><?PHP echo($dic->translate("Poor"))?></option>
                                        <option value="2"><?PHP echo($dic->translate("Satisfactory"))?></option>
                                        <option selected value="3"><?PHP echo($dic->translate("Good"))?></option>
                                        <option value="4"><?PHP echo($dic->translate("Excellent"))?></option>
                                    </select>
                                </div>
                                <div class="col2">
                                    <select name="part<?php echo($key)?>">
                                        <option value="1"><?PHP echo($dic->translate("Poor"))?></option>
                                        <option value="2"><?PHP echo($dic->translate("Satisfactory"))?></option>
                                        <option selected value="3"><?PHP echo($dic->translate("Good"))?></option>
                                        <option value="4"><?PHP echo($dic->translate("Excellent"))?></option>
                                    </select>
                                </div>
                                <div class="col2">
                                    <textarea style="height: 60px;" name="comm<?php echo($key)?>"></textarea>
                                </div>
                                
                                <?PHP 
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                        <button class="widget mt10" type="submit" name="add" value="add"><?php echo($dic->translate("Add Class Record"))?></button>
                        <button class="widget cwidget mt10" type="reset" value="reset" name="reset"><?php echo($dic->translate("Clear"))?></button>
                    </form>
                </div>
                <div style="clear: both; height: 120px;"></div> 
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
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