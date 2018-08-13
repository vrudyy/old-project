<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/FileUpload.php");
    
    //checking if the user logged in
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    $dao = new DAO();
    
    $accEmail = $_SESSION["email"]; 
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    $imgURL = $client->clientLogo;
    
    
    if(isset($_POST["submit"])){
        $path = FileUpload::image($_FILES["logo"], $client->clientId, "logo", 1000000);
        $client->clientLogo = $path;
        $dao->update($client);
        ob_start();
        header('Location: '.'logo.php');
        ob_end_flush();
        die();
    }
    
    
    //intialising the dictionary
    $dic = new Dictionary($client->clientLanguage);
    
    //closing database connection
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
                <div class="settingswrapper in-row vsec ">
                    <div class="col5">
                        <h3 style="" class="in-row"><?php echo($dic->translate("Company Logo")) ?></h3>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="file" name="logo"/>
                            <button type="submit" name="submit" class="widget"><?php echo($dic->translate("Upload")) ?></button>
                            <p style="margin: 80px 0 0 0;"><?php echo($dic->translate("Make it large (e.g. 1000px wide) and either PNG, GIF or JPG format.")) ?></p>
                        </form>
                        <h4 style="margin: 20px 0 0 0;"><?php echo($dic->translate("Current Logo")) ?></h4>
                        <?php 
                            if(strlen($imgURL)!=0){
                        ?>  
                        <img style="margin: 30px 0;min-height: 150px;min-width: 150px;max-height: 150px;max-width: 150px; " src="<?php echo $imgURL ?>" alt="companyLogo"/>
                        <?php        
                            }else{
                                echo "<div style=\"height:150px; width: 150px;\"></div>";
                            }
                         ?>
                    </div>
                </div>
                <div style="clear: both; height: 100px;"></div>
            </div>
            
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>