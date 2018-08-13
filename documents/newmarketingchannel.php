<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Validator.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    $name = "";
    $nameError = "";
    
    if(isset($_POST['submit'])){
        $name = $_POST["name"];
        $nameError = Validator::isEmpty($name, "Marketing Channel Name");
        
        
        if(Validator::check($nameError)){
            $mc = new MarketingChannel();
            $mc->marketingChannelName = $name;
            $mc->marketingChannelStatus = $_POST["status"];
            $mc->clientId = $client->clientId;
            $dao->add($mc);
            ob_start();
            header('Location: '.'marketingchannels.php');
            ob_end_flush();
            die();
        }
    }
    
    $dao->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
    </head>
    <body>
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="settingswrapper">
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec">
                            <div class="col5">
                                <h3><?php echo($dic->translate("Marketing Channel Details")) ?></h3>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Marketing Channel Name")) ?> <span style="color:red;">*</span></label>
                                    <input value="<?php echo $name ?>" class="col6" type="text" name="name">
                                    <p class="error col10"><?php echo $nameError ?></p>
                                </div>
                                <div class="settingsSubSection">
                                    <label class="col4"><?php echo($dic->translate("Status")) ?></label>
                                    
                                    <select name="status">
                                        <option value="1"><?php echo($dic->translate("Active")) ?></option>
                                        <option value="0"><?php echo($dic->translate("Inactive")) ?></option>
                                    </select>
                                    <p class="error col10"></p>
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
    </body>
</html>