 <?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Address.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Mailer2.php");

    
    include("content/database.php");
    include("content/session.php");
     
    $tutorId = $_POST["tutorId"];
    $tutor = $dao->get("Tutor", $tutorId, "tutorId");
    $tutorContact = $dao->get("Contact", $tutor->contactId, "contactId");
    $date = new Date();
    $address = new Address();
    
    //setting up placeholder values
    $firstName = $tutorContact->contactFirstName;
    $lastName = $tutorContact->contactLastName;
    $dob = "";
    if(!strcmp($tutorContact->contactDOB, "0000-00-00")==0){
        $date->fromInput($tutorContact->contactDOB);
        $dob = $date->longDate();
    }
    $email = $tutorContact->contactEmail;
    $phone = $tutorContact->contactPhone;
    $address->convertToObject($tutorContact->contactAddress);
    $firstline = $address->firstline;
    $secondline = $address->secondline;
    $thirdline = $address->thirdline;
    $town = $address->town;
    $region = $address->region;
    $zip = $address->zip;
    
    //setting up error messages
    $firstNameError="";$lastNameError="";$emailError="";$phoneError="";$dobError="";$firstlineError="";$secondlineError="";$thirdlineError="";$townError="";$regionError="";$zipError="";      
    
    if(isset($_POST["submit"])){
        //getting form data
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $dob = htmlspecialchars($_POST["dob"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $status = htmlspecialchars($_POST["status"]);
        $firstline = htmlspecialchars($_POST["firstline"]);
        $secondline = htmlspecialchars($_POST["secondline"]);
        $thirdline = htmlspecialchars($_POST["thirdline"]);
        $town = htmlspecialchars($_POST["town"]);
        $region = htmlspecialchars($_POST["region"]);
        $zip = htmlspecialchars($_POST["zip"]);
        //validating form data
        $firstNameError = Validator::isEmpty($firstName, "First Name");
        $lastNameError = Validator::isEmpty($lastName, "Last Name");
        $emailError = Validator::isEmail($email, "Email");
        $phoneError = Validator::isNumber($phone, "Phone number");
        $dobError = "";
        $firstlineError = "";
        $secondlineError = "";
        $thirdlineError = "";
        $townError = "";
        $regionError = "";
        $zipError = "";
        //if there are no input errors
        if(Validator::check($firstNameError, $lastNameError, $emailError, $phoneError, $dobError, $firstlineError, $secondlineError, $thirdlineError, $townError, $regionError, $zipError)){
            $address->firstline = $firstline;
            $address->secondline = $secondline;
            $address->thirdline = $thirdline;
            $address->town = $town;
            $address->region = $region;
            $address->zip = $zip;
            
            $tutorContact->contactFirstName = $firstName;
            $tutorContact->contactLastName = $lastName;
            $date->periodToDate($dob);
            $tutorContact->contactDOB = $date->toDB();
            $tutorContact->contactAddress = $address->convertToDB();
            $tutorContact->contactEmail = $email;
            $tutorContact->contactPhone = $phone;
            $tutor->tutorStatusId = $status;
            
            if($status == 5){
                $mailer = new Mailer2();
                $mailer->newUser($tutorContact, "https://www.i-nucleus.com/main/resetpass.php?email=".$email);
            }
            
            
            $dao->update($tutor);
            $dao->update($tutorContact);
            
            
            
            ob_start();
            header('Location: '.'tutors.php');
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

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>
        <style>
            .error{
                width: 83.33%;
            }
            #datepicker1 {
                width: 50%;
            }

        </style>
    </head>
    <body style="background: #f7f7f7;">
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="settingswrapper">
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Tutor Details")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("First Name")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $firstName ?>" class="col6" type="text" name="firstName">
                                    <p class="error"><?php echo $firstNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Last Name")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $lastName ?>" class="col6" type="text" name="lastName">
                                    <p class="error"><?php echo $lastNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Date of Birth")) ?> </label>
                                    <div class="input-group input-append date" id="datepicker1">
                                        <input value="<?php echo($dob)?>" type="text" class="form-control col6" name="dob" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <p class="error"><?php echo $schoolYearStartError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Email")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $email ?>" class="col6" type="text" name="email">
                                    <p class="error"><?php echo $emailError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Phone")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $phone ?>" class="col6" type="text" name="phone">
                                    <p class="error"><?php echo $phoneError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Status")) ?></label>
                                    
                                    <select name="status">
                                        <?php 
                                            $statuses = $dao->listAll("TutorStatus");
                                            $status = $tutor->tutorStatusId;
                                            foreach($statuses as $st){
                                                if(strcmp($st->tutorStatusId, $status)==0){
                                                    echo '<option value="'.$st->tutorStatusId.'" selected>'.$dic->translate($st->tutorStatusStatus).'</option>'; 
                                                }else{
                                                    echo '<option value="'.$st->tutorStatusId.'">'.$dic->translate($st->tutorStatusStatus).'</option>';
                                                }
                                            }
                                            
                                            $dao->close();
                                        ?>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <div class="in-row vsec settingsSection">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Address")) ?></h3>
                                
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Address")) ?> </label>
                                    <input value="<?php echo $firstline ?>" class="col6" type="text" name="firstline">
                                    <p class="error"><?php echo $firstlineError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"></label>
                                    <input value="<?php echo $secondline ?>" class="col6" type="text" name="secondline">
                                    <p class="error"><?php echo $secondlineError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"></label>
                                    <input value="<?php echo $thirdline ?>" class="col6" type="text" name="thirdline">
                                    <p class="error"><?php echo $thirdlineError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Town")) ?> </label>
                                    <input value="<?php echo $town ?>" class="col6" type="text" name="town">
                                    <p class="error"><?php echo $townError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Region or State")) ?></label>
                                    <input value="<?php echo $region ?>" class="col6" type="text" name="region">
                                    <p class="error"><?php echo $regionError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Post/Zip Code")) ?> </label>
                                    <input value="<?php echo $zip ?>" class="col6" type="text" name="zip">
                                    <p class="error"><?php echo $zipError ?></p>
                                </div>                             
                                
                            </div>
                        </div>
                        <input type="hidden" name="tutorId" value="<?php echo($tutor->tutorId)?>"/>
                        <button class="widget left" type="submit" name="submit" value="submit"><?php echo($dic->translate("Update")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 100px;"></div>
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