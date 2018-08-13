<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
   
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
    
    //getting the client and contact of account owner
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    if(isset($_POST["submit"])){
        $schoolYearTitle = $_POST["schoolYearTitle"];
        $schoolYearStart = $_POST["schoolYearStart"];
        $schoolYearEnd = $_POST["schoolYearEnd"];
        
        $schoolYearTitleError = Validator::isEmpty($schoolYearTitle, "School Year Title");
        if(strlen($schoolYearTitleError) == 0){
            
            $year = new SchoolYear();
            $year->schoolYearId = $dao->next("SchoolYear", "schoolYearId");
            $year->clientId = $client->clientId;
            $year->schoolYearTitle = $schoolYearTitle;
            $year->schoolYearStart = $schoolYearStart;
            $year->schoolYearEnd = $schoolYearEnd;
            $year->schoolYearStatus = "y";
            
            $dao->add($year);
            ob_start();
            header('Location: '.'schoolyears.php');
            ob_end_flush();
            die();
           
        }
    }
    
    
    //closing connection;
    $dao->close();
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
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
               <div class="settingswrapper">
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec" style="border: none;">
                            <div class="col5">
                                <h3><?php echo($dic->translate("School Year Details")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("School Year Title")) ?> <span style="color:red;">*</span></label>
                                    <input required value="<?php echo $schoolYearTitle ?>" class="col6" type="text" name="schoolYearTitle">
                                    <p class="error"><?php echo $schoolYearTitleError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("Start Date")) ?> <span style="color:red;">*</span></label>
                                    <div class="input-group input-append date" id="datepicker1">
                                        <input value="<?php echo $schoolYearStart ?>" required type="text" class="form-control col6" name="schoolYearStart" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <p class="error"><?php echo $schoolYearStartError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col6"><?php echo($dic->translate("End Date")) ?> <span style="color:red;">*</span></label>
                                    <div class="input-group input-append date" id="datepicker2">
                                        <input value="<?php echo $schoolYearEnd ?>" required type="text" class="form-control col6" name="schoolYearEnd" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <p class="error"><?php echo $schoolYearEndError ?></p>
                                </div>
                            </div>
                        </div>
                        <button name="submit" value="submit" type="submit" class="widget left"><?php echo($dic->translate("Add School Year")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                
                <div style="clear: both; height: 120px;"></div> 
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
    $(document).ready(function(){
       $('#datepicker2') 
               .datepicker({
                   format: 'D dd, MM yyyy',
                   startDate: '01 01 2010',
                   endDate:  '12 30 2050'
                   
       });
    });
</script>
    

    </body>
</html>