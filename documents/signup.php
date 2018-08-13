<?PHP
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileStructure.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Mailer.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Mailer2.php");
    
    //Initialising values for the input fields
    $firstnamePlaceholder = "";
    $lastnamePlaceholder = "";
    $emailPlaceholder = "";
    $businessNamePlaceholder = "";
    
    //initialising the DAO
    $dao = new DAO();
    
    //getting all the languages
    $languages = $dao->listAll("Language");
    
    //when the user submits the form
    if($_POST){
        
        //getting the values from the input fields
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $email = $_POST["email"];
        $businessName = $_POST["companyName"];
        $language = $_POST["language"];
        $password = $_POST["password"];
        
        /*
        //checking for invalid inputs
        $firstnameError = Validator::isName($firstname, "Firstname");
        $lastnameError = Validator::isName($lastname, "Lastname");
        $emailError = Validator::isNotUserEmail($email);
        $businessNameError = Validator::isCompanyName($businessName);
        $passwordError = Validator::isPassword($password);
        */
        
        $firstnamePlaceholder = $firstname;
        $lastnamePlaceholder = $lastname;
        $emailPlaceholder = $email;
        $businessNamePlaceholder = $businessName;

        
        
        //if there are no input errors
        if(Validator::check($firstnameError, $lastnameError, $emailError, $businessNameError, $passwordError)){
            
            //setting up the id for the email verification
            $id = md5($businessName);
            
            //sending the email for verification
            #$mailer = new Mailer($email, "i-Nucleaus Sign Up", $firstname." ".$lastname, "www.i-nucleus.com/main/confirm.php?email=$email&id=".$id);
            #$mailer->send();
            
            
           
            
            //creating a new Client and Contact
            $client = new Client();
            $contact = new Contact();
            $user = new User();
            
            $user->userPassword = md5($password);
            $user->roleId = 1;
            $contact->contactFirstName = $firstname;
            $contact->contactLastName = $lastname;
            $client->clientLanguage = $language;
            $contact->contactEmail = $email;
            $contactId = $dao->add($contact);
            
           
            $client->clientCompanyName = $businessName;
            $client->clientSignUpDate = "2018-01-04";
            $client->contactId = $contactId;
            
            //adding the new client and contact to the database
            $clientId = $dao->add($client);
            $user->clientId = $clientId;
            $user->contactId = $contactId;
            $user->userActivate = 'n';
            $dao->add($user);
            
            $mailer2 = new Mailer2();
            $mailer2->newClient($contact, "www.i-nucleus.com/main/confirm.php?cisid=$clientId&id=".$id);
            
            //creating the folder structure for the client
            FileStructure::newClient($clientId);
            
            ob_start();
            header('Location: '.'emailconfirmation.php');
            ob_end_flush();
            die();
        }else{
            //setting up the place holders if there are errors
            $firstnamePlaceholder = $firstname;
            $lastnamePlaceholder = $lastname;
            $emailPlaceholder = $email;
            $businessNamePlaceholder = $businessName;
        }
         
    }
    
    $dao->close();
     
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>i-Nucleus Sign up</title>
        
        <link href="css/defaults.css" rel="stylesheet" >
        <link href="css/grid.css" rel="stylesheet" >
        <link href="css/signup.css" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <div class="in-row signuplogo">
                <div class="col12">
                    <img class="slogo" src="img/signuplogo.png" alt="i-Nucleus Logo">
                </div>
            </div>
            <div class="in-row">
                <div class="col5 leftCol mh500">
                    <h2>Start your free trial</h2>
                    <h3>30 days completely free</h3>
                    <h3>No Credit card required</h3>
                    <div class="extraInfo">
                        <ul class="">
                            <li>
                                <h4 class="">Safe & Secure</h4>
                                <p class="">Your data is securely backed up every 5 minutes</p>
                            </li>
                            <li>
                                <h4 class="">Cancel Anytime</h4>
                                <p class="">Export all of your data if you decide to cancel</p>
                            </li>
                            <li>
                                <h4 class="">Help Always on hand</h4>
                                <p class="">Any questions you have answered quickly</p>
                            </li>
                        </ul>
                    </div> 
                </div>
                <div class="col7 mh500">
                    <form action="" method="post">
                        <div class="inputWrapper">
                            <input autocomplete="new-password" type="text" placeholder="First Name" value="<?php echo  $firstnamePlaceholder ?>" name="firstname">
                            <span><?php echo  $firstnameError ?></span>
                        </div>
                        <div class="inputWrapper">
                            <input autocomplete="new-password" type="text" placeholder="Last Name" value="<?php echo  $lastnamePlaceholder ?>" name="lastname">
                            <span><?php echo  $lastnameError ?></span>
                        </div>
                        <div class="inputWrapper">
                            <input autocomplete="new-password" type="email" placeholder="Email" value="<?php echo  $emailPlaceholder ?>" name="email">
                            <span><?php echo  $emailError ?></span>
                        </div>
                        <div class="inputWrapper">
                            <input autocomplete="new-password" type="text" placeholder="Business Name" value="<?php echo  $businessNamePlaceholder ?>" name="companyName">
                            <span><?php echo  $businessNameError ?></span>
                        </div>
                        <div class="inputWrapper">
                            <select name="language">
                              <?php 
                                foreach($languages as $lang){ 
                                    echo "<option value=\"".$lang->languageName."\">".$lang->languageName."</option>";
                                } 
                             ?>
                            </select>
                            <span></span>
                        </div>
                        <div class="inputWrapper">
                            <input autocomplete="new-password" type="password" placeholder="Password" name="password">
                            <span><?php echo  $passwordError ?></span>
                        </div>
                        <button class="widget" style="width : 100%; min-height: 70px;">
                            Get Started With i-Nucleus
                        </button>
                    </form>    
                </div>
            </div>
            <div style="clear: both;"></div>
        </div> 
        
    </body>
</html>