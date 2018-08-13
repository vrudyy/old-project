 <?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Address.php");
    
    //starts the session and checks if it is set
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //initialises the DAO
    $dao = new DAO();
    
    //gets the session email, contact, client and company name
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    
    
    $dic = new Dictionary($client->clientLanguage);
    //when the user submits the form
    
    if(isset($_POST["submit"])){
        
        $firstName = htmlspecialchars($_POST["firstName"]);
        $lastName = htmlspecialchars($_POST["lastName"]);
        $organisation = htmlspecialchars($_POST["organisation"]);
        $dob = htmlspecialchars($_POST["dateOfBirth"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["phone"]);
        
        //gets the form data
        $firstline = htmlspecialchars($_POST["firstline"]);
        $secondline = htmlspecialchars($_POST["secondline"]);
        $thirdline = htmlspecialchars($_POST["thirdline"]);
        $town = htmlspecialchars($_POST["town"]);
        $region = htmlspecialchars($_POST["region"]);
        $zip = htmlspecialchars($_POST["zip"]);
        
        
        $firstNameError = Validator::isEmpty($firstName, "First Name");
        $lastNameError = Validator::isEmpty($lastName, "Last Name");
        $organisationError = Validator::isEmpty($organisation, "Organisation Name");
        $emailError = "";
        $phoneError = "";
        $dobError = "";
        
        
        $firstlineError = "";
        $secondlineError = "";
        $thirdlineError = "";
        $townError = "";
        $regionError = "";
        $zipError = "";
        
        //if there are no input errors
        if((Validator::check($firstNameError, $lastNameError) || Validator::check($organisationError)) && Validator::check($emailError, $phoneError, $dobError, $firstlineError, $secondlineError, $thirdlineError, $townError, $regionError, $zipError)){
            
            $cc= new Contact();
            $cc->contactFirstName = $firstName;
            $cc->contactLastName = $lastName;
            $cc->contactOrganisation = $organisation;
            $cc->contactPhone = $phone;
            $cc->contactEmail = $email;
            
            
            $date = new Date();
            $date->periodToDate($dob);
            $cc->contactDOB = $date->toDB();
            
            $address = new Address();
            $address->firstline = $firstline;
            $address->secondline = $secondline;
            $address->thirdline = $thirdline;
            $address->town = $town;
            $address->country = $country;
            $address->zip = $zip;
            
            $cc->contactAddress = $address->convertToDB();
            $cc->clientId = $client->clientId;
           
            $dao->add($cc);
            
            ob_start();
            header('Location: '.'contacts.php');
            ob_end_flush();
            die();
        }
        
    }
    
    //closes the connection to the database
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
                                <h3><?php echo($dic->translate("Contact Details")) ?></h3>
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
                                    <label class="col4"><?php echo($dic->translate("Organisation")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $organisation ?>" class="col6" type="text" name="organisation">
                                    <p class="error"><?php echo $organisationError ?></p>
                                    <p><?php echo($dic->translate("Enter a first and last name, and/or an organisation name. Both are not required")) ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Date of Birth")) ?> </label>
                                    <div class="input-group input-append date" id="datepicker1">
                                        <input type="text" class="form-control col6" name="dateOfBirth" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <p class="error"><?php echo $schoolYearStartError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Email")) ?> </label>
                                    <input value="<?php echo $email ?>" class="col6" type="text" name="email">
                                    <p class="error"><?php echo $emailError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Phone")) ?></label>
                                    <input value="<?php echo $phone ?>" class="col6" type="text" name="phone">
                                    <p class="error"><?php echo $phoneError ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="in-row vsec settingsSection">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Contact Address")) ?></h3>
                                
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
                        <button class="widget left" type="submit" name="submit" value="submit"><?php echo($dic->translate("Add")) ?></button>
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