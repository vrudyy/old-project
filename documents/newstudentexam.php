<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    $student = new Student();
    $student = $dao->get("Student", $_POST["studentId"], "studentId");
    #var_dump($student);
    #var_dump($studentClasses);
    
    
    $examDate = "";
    $examActualResult = "";
    $examPredictedResult = "";
    $examComments = "";
    
    $examActualResultError = "";
    $examPredictedResultError = "";
    $examCommentsError = "";
    
    
    if(isset($_POST["add"])){
        $examDate = $_POST["examDate"];
        $examActualResult = $_POST["examActualResult"];
        $examPredictedResult = $_POST["examPredictedResult"];
        $examComments = $_POST["examComments"];
        $qualificationId = $_POST["qualification"];
        
        $examActualResultError = Validator::isEmpty($examActualResult, "Exam Actual Result");
        $examPredictedResultError = Validator::isEmpty($examPredictedResult, "Exam Predicted Result");
        $examCommentsError = Validator::isEmpty($examComments, "Exam Comments");
        
        if(Validator::check($examActualResultError, $examPredictedResultError, $examCommentsError)){
            $studentExamPerformance = new StudentExamPerformance();
            $studentExamPerformance->qualificationId = $qualificationId;
            $studentExamPerformance->studentExamPerformanceAchieved = $examActualResult;
            $studentExamPerformance->studentExamPerformanceComments = $examComments;
            $date = new Date();
            $studentExamPerformance->studentExamPerformanceDate = $date->fromInputToDB($examDate);
            $studentExamPerformance->studentExamPerformancePredicted = $examPredictedResult;
            $studentExamPerformance->studentId = $student->studentId;
            $dao->add($studentExamPerformance);
            
            $_SESSION["studentId"] = $student->studentId;
            ob_start();
            header('Location: '.'student.php?sec=studentexams');
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
                        <input type="hidden" name="studentId" value="<?PHP echo($student->studentId)?>"/>
                        <input id="cknt" type="hidden" name="cknt" value="<?php echo($client->clientId)?>"/>
                        <div class="in-row in-settings">
                            <div class="col6">
                                <h3 style="margin-bottom: 15px;"><?php echo($dic->translate("Exam Results")) ?></h3>
                                 <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Qualification").":")?><span class="cred ml5">*</span></label>
                                    <select class="col6 p5" name="qualification">
                                     <?php
                                        $qualificationIds = $student->getQualificationArray();
                                        foreach($qualificationIds as $key => $value){
                                            #$qualification = new Qualification();
                                            $qualification = $dao->get("Qualification", $value, "qualificationId");
                                            echo '<option value="'.$qualification->qualificationId.'">'.$qualification->qualificationName.'</option>';
                                        }
                                    ?>
                                   </select>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Exam Date").":")?><span class="cred ml5">*</span></label>
                                    <div class="input-group input-append date col5 p5" id="datepicker1">
                                        <input type="text" class="form-control" name="examDate" value="<?PHP echo($examDate)?>"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Exam Predicted Result").":")?><span class="cred ml5">*</span></label>
                                    <input required class="col5 p5" type="text" name="examPredictedResult" value="<?php echo($examPredictedResult)?>"/>
                                    <p class="col8 in-error m0"><?php echo($examPredictedResultError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Exam Actual Result").":")?><span class="cred ml5">*</span></label>
                                    <input required class="col5 p5" type="text" name="examActualResult" value="<?php echo($examActualResult)?>"/>
                                    <p class="col8 in-error m0"><?php echo($examActualResultError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Exam Comments").":")?><span class="cred ml5">*</span></label>
                                    <textarea required style="resize: none;width: 75%;min-height: 100px;" class="col6 p5" type="text" name="examComments" value=""><?php echo($examComments)?></textarea>
                                    <p class="col8 in-error m0"><?php echo($examCommentsError)?></p>
                                </div>
                            </div>
                            
                        </div>
                        <button class="widget mt10" type="submit" name="add" value="add"><?php echo($dic->translate("Add"))?></button>
                        <button class="widget cwidget mt10" type="reset" value="reset" name="reset"><?php echo($dic->translate("Clear"))?></button>
                    </form>
                </div>
                <div style="clear: both; height: 120px;"></div> 
            </div>
        </div>
        <script src="javascript/date.js"></script>
        <?php include("content/footer.php") ?>
    </body>
</html>