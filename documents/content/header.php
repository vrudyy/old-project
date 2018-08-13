<div class="in-row in-header" style="height:115.6px;">
    <div class="col5" style="padding: 10px;">
        <?php 
            if(strlen($client->clientLogo)!=0){
        ?>        
                <img src="<?php echo $client->clientLogo; ?>" width="90px" height="90px">
        <?php        
            }
            $dao = new DAO;
            $numOfReminders = sizeof($dao->listAllWhere("Reminder", " WHERE `prospectId` IN (SELECT `Prospect`.`prospectId` FROM `Prospect` WHERE `clientId` = $client->clientId) AND `reminderStatus` = 0"));
            
            
                
         ?>
    </div>
    <div style="float: right; padding: 40px;">
        <p style="color: #fff;display:inline;font-size: 20.8px; ">
            <span style="margin: 0 10px 0 0;">(<?php echo($numOfReminders)?>)<i style="line-height: 35px; margin: 0 5px 0 0;" class="in-fl-l fas fa-bell"></i></span>
            <?php echo($dic->translate("Welcome back,")) ?> 
            <ul class="logdrop" style="display: inline;">
                <li>
                    <div style="font-size: 24px;padding: 5px;"><?php echo($contact->contactFirstName)?></div>
                    <ul class="dropdown">
                        <li><a href="settings.php"><?php echo($dic->translate("Settings")) ?></a></li>
                        <li><a href="logout.php"><?php echo($dic->translate("Log out")) ?></a></li>
                    </ul>
                </li>
             </ul>
        </p>
    </div>
</div>
