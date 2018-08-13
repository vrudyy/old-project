 <?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
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
    #$client = new Client();
    $companyName = $client->clientCompanyName;
    
    //gets the address object
    $address = new Address();
    $address->convertToObject($contact->contactAddress);
    
    $currencies = $dao->listAll("Currency");
    
    //gets address information of the user
    $firstline = $address->firstline;
    $secondline = $address->secondline;
    $thirdline = $address->thirdline;
    $town = $address->town;
    $region = $address->region;
    $zip = $address->zip;
    $country = $address->country;
    //gets the languange 
    $language = $client->clientLanguage;
    $phone = $contact->contactPhone;
    $regnum = $client->clientRegistrationNumber;
    $vat = $client->clientVAT;
    $email = $client->clientEmail;
    $vatVal = $client->clientVatVal;
    $currency = $client->currencyId;
    $bankName = $client->clientBankName;
    $sortCode = $client->clientSortCode;
    $accountNumber = $client->clientBankNumber;
    
    //getting all available languages
    $languages = $dao->listAll("Language");
    
    //create a dictionary
    $dic = new Dictionary($language);
    //when the user submits the form
    
    if(isset($_POST["submit"])){
        
        
        //gets the form data
        $firstline = htmlspecialchars($_POST["firstline"]);
        $secondline = htmlspecialchars($_POST["secondline"]);
        $thirdline = htmlspecialchars($_POST["thirdline"]);
        $town = htmlspecialchars($_POST["town"]);
        $region = htmlspecialchars($_POST["region"]);
        $zip = htmlspecialchars($_POST["zip"]);
        $country = htmlspecialchars($_POST["country"]);
        $language = htmlspecialchars($_POST["language"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $regnum = htmlspecialchars($_POST["regnum"]);
        $vat = htmlspecialchars($_POST["vat"]);
        $currency = htmlspecialchars($_POST["currency"]);
        $vatVal = htmlspecialchars($_POST["vatVal"]);
        $bankName = htmlspecialchars($_POST["bankName"]);
        $sortCode = htmlspecialchars($_POST["sortCode"]);
        $accountNumber = htmlspecialchars($_POST["accountNumber"]);
        
        //checks if the input data is correct
        $compNameError = ((strcmp($companyName, $_POST["companyName"]) != 0)? Validator::isCompanyName($_POST["companyName"]) : "");
        $firstlineError = Validator::isEmpty($firstline, "Address");
        $secondlineError = "";
        $thirdlineError = "";
        $townError = Validator::isEmpty($town);
        $regionError = "";
        $zipError = Validator::isEmpty($zip);
        $countryError = "";
        $emailError = Validator::isEmail($email);
        $phoneError = "";
        $vatValError = Validator::isNumber($vatVal, "VAT value");
        $bankNameError = "";
        $sortCodeError = "";
        $accountNumberError = "";
        
        //if there are no input errors
        if(Validator::check($compNameError, $firstlineError, $secondlineError, $thirdlineError
                , $townError, $regionError, $zipError, $countryError, $emailError, $phoneError, $vatValError,
                $bankNameError, $sortCodeError, $accountNumberError)){
            //updates the client information    
            $client->clientRegistrationNumber = $regnum;
            $client->clientVAT = $vat;
            $client->clientCompanyName = $_POST["companyName"];
            $client->clientLanguage = $language;
            $client->clientEmail = $email;
            $client->clientVatVal = $vatVal;
            $client->currencyId = $currency;
            $client->clientBankName = $bankName;
            $client->clientBankNumber = $accountNumber;
            $client->clientSortCode = $sortCode;
            
           
            $dao->update($client);
            
            //updates the address information
            $address->firstline = $firstline;
            $address->secondline = $secondline;
            $address->thirdline = $thirdline;
            $address->town = $town;
            $address->region = $region;
            $address->country = $county;
            $address->zip = $zip;
            
            //updates the contact information
            $contact->contactAddress = $address->convertToDB();
            $contact->contactPhone = $phone;
            
            $dao->update($contact);
            
            //redirects to the settings page
            
            ob_start();
            header('Location: '.'settings.php');
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
        <?php include("content/head.php") ?>
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
                                <h3><?php echo($dic->translate("Company Details")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Company Name")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $companyName ?>" class="col6" type="text" name="companyName">
                                    <p class="error"><?php echo $compNameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Company Address")) ?> <span style="color:red;">*</span></label>
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
                                    <label class="col4"><?php echo($dic->translate("Town")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $town ?>" class="col6" type="text" name="town">
                                    <p class="error"><?php echo $townError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Region or State")) ?></label>
                                    <input value="<?php echo $region ?>" class="col6" type="text" name="region">
                                    <p class="error"><?php echo $regionError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Post/Zip Code")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $zip ?>" class="col6" type="text" name="zip">
                                    <p class="error"><?php echo $zipError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Language")) ?></label>
                                    <select name="language" class="col6">
                                        <?php 
                                            foreach($languages as $lang){ 
                                                
                                                if(strcmp($lang->languageName, $language)==0){
                                                    echo "<option selected value=\"".$lang->languageName."\">".$lang->languageName."</option>";
                                                }else{
                                                    echo "<option value=\"".$lang->languageName."\">".$lang->languageName."</option>";
                                                } 
                                                
                                            } 
                                         ?>
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Contact Email Address")) ?></label>
                                    <input value="<?php echo $email ?>" class="col6" type="text" name="email">
                                    <p class="error"><?php echo $emailError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Contact Phone Number")) ?></label>
                                    <input value="<?php echo $phone ?>" class="col6" type="text" name="phone">
                                    <p class="error"><?php echo $phoneError ?></p>
                                </div>  
                            </div>
                        </div>
                        <div class="in-row vsec">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Other Details")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Company Registration Number")) ?></label>
                                    <input value="<?php echo $vat ?>" class="col6" type="text" name="regnum">
                                    <p class="error"></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("VAT Number")) ?></label>
                                    <input value="<?php echo $regnum ?>" class="col6" type="text" name="vat">
                                    <p class="error"></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("VAT")."(%)") ?></label>
                                    <input value="<?php echo $vatVal ?>" class="col6" type="text" name="vatVal">
                                    <p class="error"><?php echo($vatValError)?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Currency")) ?></label>
                                    
                                    <select name="currency" class="col6">
                                        <?php 
                                            foreach($currencies as $c){ 
                                                #$c = new Currency;
                                                if($currency == $c->currencyId){
                                                    echo "<option selected value=\"".$c->currencyId."\">".$c->currencyShort."</option>";
                                                }else{
                                                    echo "<option value=\"".$c->currencyId."\">".$c->currencyShort."</option>";
                                                } 
                                                
                                            } 
                                         ?>
                                    </select>
                                    
                                    <p class="error"></p>
                                </div>
                                
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Bank Name")) ?></label>
                                    <input value="<?php echo $bankName ?>" class="col6" type="text" name="bankName">
                                    <p class="error"><?php echo($bankNameError)?></p>
                                </div>
                                
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Bank Sort Code")) ?></label>
                                    <input value="<?php echo $sortCode ?>" class="col6" type="text" name="sortCode">
                                    <p class="error"><?php echo($sortCodeError)?></p>
                                </div>
                                
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Bank Account Number")) ?></label>
                                    <input value="<?php echo $accountNumber ?>" class="col6" type="text" name="accountNumber">
                                    <p class="error"><?php echo($accountNumberError)?></p>
                                </div>
                                
                            </div>
                        </div>
                        <button class="widget left" type="submit" name="submit" value="submit"><?php echo($dic->translate("Save")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 100px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>