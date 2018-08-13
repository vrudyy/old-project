<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    
    $prospect = $dao->get("Prospect", $_POST['prospectId'], "prospectId");
    $parent = $dao->get("Contact", $prospect->parentId, "contactId");
    #$parent = new Contact();
    
    if(isset($_POST["submit"])){
        $description = $_POST["text"];
        $date = $_POST["date"];
        $reminder = new Reminder();
        $reminder->prospectId = $prospect->prospectId;
        $reminder->reminderDescription = $description;
        $d = new Date();
        $d->periodToDate($date);
        $reminder->reminderDate = $d->toDB();
        $reminder->reminderStatus = 0;
        $dao->add($reminder);
        ob_start();
        $_SESSION['prospectId'] = $prospect->prospectId;
        header('Location: '.'prospect.php?sec=reminders');
        ob_end_flush();
        die();
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
                <div class="settingswrapper">
                    <form id="detailsForm" action="" method="post">
                        <div class="in-row settingsSection vsec">
                            <div class="col5">
                                <h3><?php echo($dic->translate("New Reminder for prospect: ").$parent->contactFirstName." ".$parent->contactLastName) ?></h3>
                                <div class="in-setting-sec in-row" style="margin-bottom: 80px;">
                                    <label class="col4"><?php echo($dic->translate("Description"))?></label>
                                    <textarea style="padding: 5px;position: absolute;resize: none;" rows="6" cols="50" name="text"></textarea>
                                    <p class="col8 in-error m0"></p>
                                </div>
                                <div class="in-setting-sec in-row">
                                    <label class="col4 m0"><?php echo($dic->translate("Reminder date").":")?></label>
                                    <div class="input-group input-append date col6 p5" id="datepicker1">
                                        <input type="text" class="form-control" name="date" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <p class="col8 in-error m0"></p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="prospectId" value="<?php echo($prospect->prospectId)?>"/>
                        <button class="widget left" type="submit" name="submit" value="submit"><?php echo($dic->translate("Add")) ?></button>
                        <button id="resetButton"class="widget cwidget" type="reset" value="reset"><?php echo($dic->translate("Cancel")) ?></button>
                    </form>
                </div>
                <div style="clear: both; height: 100px;"></div>
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
    <script src="javascript/date.js"></script>
</html>