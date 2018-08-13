<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include_once("content/session.php");
    include_once 'content/new-prospect-error-placeholders.php';
    include_once 'content/new-prospect-placeholders.php';
    
    $student = new Student();
    $student = $dao->get("Student", $_POST["studentId"], "studentId");
    $studentContact = new Contact();
    $studentContact = $dao->get("Contact", $student->contactId, "contactId");
    $parentContact = new Contact();
    $parentContact = $dao->get("Contact", $student->parentId, "contactId");
    
    
    $educationalLevels = $dao->listAll("EducationLevel", "clientId", $client->clientId);
    $branches = $dao->listAll("Branch", "clientId", $client->clientId);
    $checked = strcmp($studentContact->contactAddress, $parentContact->contactAddress) == 0 ? "checked": "";
    
    if(strcmp($studentContact, "")==0 || strcmp($studentContact, ";;;;;;;")==0){
        $checked = "checked";
    }
    
    
    //initialising errors
    $studentFirstNameError = "";
    $studentLastNameError = "";
    $studentMobileError = "";
    $studentEmailError = "";
    $studentFirstlineError = "";
    $studentSecondlineError = "";
    $studentThirdlineError = "";
    $studentTownError = "";
    $studentRegionError = "";
    $studentZipError = "";
    $studentSchoolError = "";
    $parentFirstNameError = "";
    $parentLastNameError = "";
    $parentMobileError = "";
    $parentEmailError = "";
    $parentFirstLineError = "";
    $parentSecondLineError = "";
    $parentThirdLineError = "";
    $parentTownError = "";
    $parentRegionError = "";
    $parentZipError = "";
    
    //initialising placeholders
    $studentFirstName = $studentContact->contactFirstName;
    $studentLastName = $studentContact->contactLastName;
    $studentMobile = $studentContact->contactPhone;
    $studentEmail = $studentContact->contactEmail;
    $address = new Address();
    $address->convertToObject($studentContact->contactAddress);
    $studentFirstLine = $address->firstline;
    $studentSecondLine = $address->secondline;
    $studentThirdLine = $address->thirdline;
    $studentTown = $address->town;
    $studentRegion = $address->region;
    $studentZip = $address->zip;
    $studentSchool = $student->studentSchool;
    $date = new Date();
    $dob = $date->fromDBToInput($studentContact->contactDOB);
    $parentFirstName = $parentContact->contactFirstName;
    $parentLastName = $parentContact->contactLastName;
    $parentMobile = $parentContact->contactPhone;
    $parentEmail = $parentContact->contactEmail;
    $address->convertToObject($parentContact->contactAddress);
    $parentFirstLine= $address->firstline;
    $parentSecondLine = $address->secondline;
    $parentThirdLine = $address->thirdline;
    $parentTown = $address->town;
    $parentRegion = $address->region;
    $parentZip = $address->zip;
    $branchId = $student->branchId;
    
    
    if(isset($_POST["add"])){
        //parent POST information and ERROR checking
        $parentFirstName = $_POST["pname"];
        $parentLastName = $_POST["plastname"];
        $parentMobile = $_POST["pmobile"];
        $parentEmail = $_POST['pemail'];
        $parentFirstLine= $_POST["pfirstline"];
        $parentSecondLine = $_POST["psecondline"];
        $parentThirdLine = $_POST['pthirdline'];
        $parentTown = $_POST["ptown"];
        $parentRegion = $_POST["pregion"];
        $parentZip = $_POST['pzip'];
        $branchId = $_POST["branch"];
        
        
        $parentFirstNameError = Validator::isEmpty($parentFirstName, "First Name");
        $parentLastNameError = Validator::isEmpty($parentLastName, "Last Name");
        $parentMobileError = Validator::isNumber($parentMobile, "Mobile Number");
        $parentEmailError = Validator::isEmpty($parentEmail, "Email");
        $parentFirstLineError = "";
        $parentSecondLineError = "";
        $parentThirdLineError = "";
        $parentTownError = "";
        $parentRegionError = "";
        $parentZipError = "";
        
        //student POST information and ERROR checking
        $studentFirstName = $_POST["name"];
        $studentLastName = $_POST["lastname"];
        $studentMobile = $_POST["mobile"];
        $studentEmail = $_POST["email"];
        $studentFirstLine = $_POST["firstline"];
        $studentSecondLine = $_POST["secondline"];
        $studentThirdLine = $_POST["thirdline"];
        $studentTown = $_POST["town"];
        $studentRegion = $_POST["region"];
        $studentZip = $_POST["zip"];
        $studentSchool = $_POST["school"];
        $studentEducationLevel = $_POST["educationalLevel"];
        $studentDOB = $_POST["dob"];
        $studentStatus = $_POST["studentStatus"];
        
        $studentFirstNameError = Validator::isEmpty($studentFirstName, "Student First Name");
        $studentLastNameError = Validator::isEmpty($studentLastName, "Student Last Name");
        $studentMobileError = "";
        $studentEmailError = "";
        $studentFirstlineError = "";
        $studentSecondlineError = "";
        $studentThirdlineError = "";
        $studentTownError = "";
        $studentRegionError = "";
        $studentZipError = "";
        $studentSchoolError = "";
        
        if(Validator::check($parentZipError, $parentRegionError, $parentTownError, $parentThirdLineError, $parentSecondLineError, $parentFirstLineError, $parentEmailError,$parentMobileError, $parentLastNameError, $parentFirstNameError,$studentSchoolError, $studentZipError, $studentRegionError, $studentTownError, $studentThirdlineError, $studentSecondlineError, $studentFirstNameError, $studentLastNameError, $studentMobileError, $studentEmailError, $studentFirstlineError)){
            if($student->parentId == 0){
                $parentContact = new Contact();
            }
            $parentContact->contactFirstName = $parentFirstName;
            $parentContact->contactLastName = $parentLastName;
            $parentContact->contactEmail = $parentEmail;
            $parentContact->contactPhone = $parentMobile;
            $pAddress = new Address();
            $pAddress->firstline = $parentFirstLine;
            $pAddress->secondline = $parentSecondLine;
            $pAddress->thirdline - $parentThirdLine;
            $pAddress->town = $parentTown;
            $pAddress->region = $parentRegion;
            $pAddress->zip = $parentZip;
            $parentContact->contactAddress = $pAddress->convertToDB();
            if($student->parentId == 0){
                $parentContactId = $dao->add($parentContact);
                $student->parentId = $parentContactId;
            }else{
                $dao->update($parentContact);
            }
            
            
            $studentContact->contactFirstName = $studentFirstName;
            $studentContact->contactLastName = $studentLastName;
            $studentContact->contactEmail = $studentEmail;
            $studentContact->contactPhone = $studentMobile;
            
            if(isset($_POST["usePAddress"])){
                $studentContact->contactAddress = $pAddress->convertToDB();
            }else{
                $studentAddress = new Address();
                $studentAddress->firstline = $studentFirstLine;
                $studentAddress->secondline = $studentSecondLine;
                $studentAddress->thirdline = $studentThirdLine;
                $studentAddress->town = $studentTown;
                $studentAddress->region = $studentRegion;
                $studentAddress->zip = $studentZip;
                $studentContact->contactAddress = $studentAddress->convertToDB();
            }
            $studentContact->contactDOB = $date->fromInputToDB($studentDOB);
            $dao->update($studentContact);
            
            
            $student->educationLevelId = $studentEducationLevel;
            $student->studentSchool = $studentSchool;
            $student->studentStatus = $studentStatus;
            $student->branchId = $branchId;
            
            
            $dao->update($student);
            
            ob_start();
            header('Location: '.'students.php');
            ob_end_flush();
            die();
            
            #var_dump($_POST);
            #echo '<br><br><br><br>';
            #var_dump($student);
        }
    }
    
   
    
    $dao->close();
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
                        <div class="in-row in-settings">
                            <div class="col6">
                                <h3><?php echo($dic->translate("Parent's/Guardian's details")) ?></h3>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Name").":")?><span class="cred ml5">*</span></label>
                                    <input class="col5 p5" type="text" name="pname" value="<?php echo($parentFirstName)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentFirstNameError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Surname").":")?><span class="cred ml5">*</span></label>
                                    <input class="col5 p5" type="text" name="plastname" value="<?php echo($parentLastName)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentLastNameError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Mobile").":")?><span class="cred ml5">*</span></label>
                                    <input class="col5 p5" type="text" name="pmobile"  value="<?php echo($parentMobile)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentMobileError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Email").":")?><span class="cred ml5">*</span></label>
                                    <input class="col5 p5" type="text" name="pemail" value="<?php echo($parentEmail)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentEmailError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Address").":")?></label>
                                    <input class="col5 p5" type="text" name="pfirstline" value="<?php echo($parentFirstLine)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentFirstLineError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"></label>
                                    <input class="col5 p5" type="text" name="psecondline" value="<?php echo($parentSecondLine)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentSecondLineError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"></label>
                                    <input class="col5 p5" type="text" name="pfthirdline" value="<?php echo($parentThirdLine)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentThirdLineError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Town").":")?></label>
                                    <input class="col5 p5" type="text" name="ptown" value="<?php echo($parentTown)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentTownError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Region/State").":")?></label>
                                    <input class="col5 p5" type="text" name="pregion" value="<?php echo($parentRegion)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentRegionError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Post/Zip Code").":")?></label>
                                    <input class="col5 p5" type="text" name="pzip" value="<?php echo($parentZip)?>"/>
                                    <p class="col8 in-error m0"><?php echo($parentZipError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Branch").":")?></label>
                                    <select class="col5 p5" name="branch">
                                        <?php 
                                            echo '<option value="0">'.$dic->translate("Select a Branch").'</option>';
                                            foreach($branches as $b){
                                                echo '<option '.($branchId == $b->branchId ? "selected" : "").' value="'.$b->branchId.'">'.$b->branchName.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col6">
                                <h3><?php echo($dic->translate("Student's details")) ?></h3>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Name").":")?><span class="cred ml5">*</span></label>
                                    <input class="col5 p5" type="text" name="name" value="<?php echo($studentFirstName)?>"/>
                                    <p class="col8 in-error m0"><?php echo($studentFirstNameError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Surname").":")?><span class="cred ml5">*</span></label>
                                    <input class="col5 p5" type="text" name="lastname" value="<?php echo($studentLastName)?>"/>
                                    <p class="col8 in-error m0"><?php echo($studentLastNameError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Mobile").":")?></label>
                                    <input class="col5 p5" type="text" name="mobile" value="<?php echo($studentMobile)?>"/>
                                    <p class="col8 in-error m0"><?php echo($studentMobileError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Email").":")?></label>
                                    <input class="col5 p5" type="text" name="email" value="<?php echo($studentEmail)?>"/>
                                    <p class="col8 in-error m0"><?php echo($studentEmailError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col4 m0"><?php echo($dic->translate("Use Parent/Guardian address"))?></label>
                                    <input id="useParentButton" class="col5 p5" type="checkbox" <?PHP echo($checked)?> name="usePAddress" value="yes"/>
                                    <p class="col8 in-error m0"></p>
                                </div>
                                <div class="in-row <?PHP echo(strcmp($checked, "checked")==0?"in-form-unselect":"")?>" id="useParentCover">
                                    <div class="in-setting-sec in-row">
                                        <label class="col3 m0"><?php echo($dic->translate("Address").":")?></label>
                                        <input class="col5 p5" type="text" name="firstline" value="<?php echo($studentFirstLine)?>"/>
                                        <p class="col8 in-error m0"><?php echo($studentFirstlineError)?></p>
                                    </div>
                                    <div class="in-setting-sec in-row">
                                        <label class="col3 m0"></label>
                                        <input class="col5 p5" type="text" name="secondline" value="<?php echo($studentSecondLine)?>"/>
                                        <p class="col8 in-error m0"><?php echo($studentSecondlineError)?></p>
                                    </div>
                                    <div class="in-setting-sec in-row">
                                        <label class="col3 m0"></label>
                                        <input class="col5 p5" type="text" name="thirdline" value="<?php echo($studentThirdLine)?>"/>
                                        <p class="col8 in-error m0"><?php echo($studentThirdlineError)?></p>
                                    </div>
                                    <div class="in-setting-sec in-row">
                                        <label class="col3 m0"><?php echo($dic->translate("Town").":")?></label>
                                        <input class="col5 p5" type="text" name="town" value="<?php echo($studentTown)?>"/>
                                        <p class="col8 in-error m0"><?php echo($studentTownError)?></p>
                                    </div>
                                    <div class="in-setting-sec in-row">
                                        <label class="col3 m0"><?php echo($dic->translate("Region/State").":")?></label>
                                        <input class="col5 p5" type="text" name="region" value="<?php echo($studentRegion)?>"/>
                                        <p class="col8 in-error m0"><?php echo($studentRegionError)?></p>
                                    </div>
                                    <div class="in-setting-sec in-row">
                                        <label class="col3 m0"><?php echo($dic->translate("Post/Zip Code").":")?></label>
                                        <input class="col5 p5" type="text" name="zip" value="<?php echo($studentZip)?>"/>
                                        <p class="col8 in-error m0"><?php echo($studentZipError)?></p>
                                    </div>
                                </div>   
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("School").":")?></label>
                                    <input class="col5 p5" type="text" name="school" value="<?php echo($studentSchool)?>"/>
                                     <p class="col8 in-error m0"><?php echo($studentSchoolError)?></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Student Status").":")?></label>
                                    <select name="studentStatus">
                                        <option <?PHP echo($student->studentStatus == 1 ? "selected" : "")?> value="1"><?PHP echo($dic->translate("Current"))?></option>
                                        <option <?PHP echo($student->studentStatus == 0 ? "selected" : "")?> value="0"><?PHP echo($dic->translate("Past"))?></option>
                                    </select>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Education Level").":")?></label>
                                    <select name="educationalLevel">
                                        <?PHP 
                                            foreach($educationalLevels as $key => $value){
                                                echo '<option '.($student->educationLevelId == $value->educationLevelId ? "selected":"").' value="'.$value->educationLevelId.'">'.$value->educationLevelName.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col3 m0"><?php echo($dic->translate("Date of Birth").":")?></label>
                                    <div class="input-group input-append date col5 p5" id="datepicker1">
                                        <input type="text" class="form-control" name="dob" value="<?PHP echo($dob)?>"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="studentId" value="<?PHP echo($student->studentId)?>"/>
                        <button class="widget mt10" type="submit" name="add" value="add"><?php echo($dic->translate("Update Student"))?></button>
                        <button class="widget cwidget mt10" type="reset" value="reset" name="reset"><?php echo($dic->translate("Clear"))?></button>
                    </form>
                </div>
                <div style="clear: both; height: 120px;"></div> 
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
    <script src="javascript/date.js"></script>
    <script>
        $button = document.getElementById("useParentButton");
        $cover = document.getElementById("useParentCover");
        
        $button.addEventListener("click", function(e){
            if($button.checked === false){
                $button.setAttribute("value", "no");
                $button.checked = false;
                $cover.style.opacity = 1;
                $cover.style.pointerEvents = "auto";
            }else if($button.checked === true){
                $button.setAttribute("value", "yes");
                $button.checked = true;
                $cover.style.opacity = 0.3;
                $cover.style.pointerEvents = "none";
            }
        });
    </script>
</html>