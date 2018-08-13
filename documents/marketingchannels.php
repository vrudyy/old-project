<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    $dao = new DAO();
    if(isset($_POST["status"]) && $_POST["status"] != 2){
        $mcs = $dao->listAllWhere("MarketingChannel", " WHERE `marketingChannelStatus` = ".$_POST["status"]." AND `clientId` = '$client->clientId';");
    }else{
        $mcs = $dao->listAll("MarketingChannel", "clientId", $client->clientId, "ASC", "marketingChannelId");
    }
    
    if(isset($_POST["marketingChannel"]) && $_POST["marketingChannel"] != "all"){
        $size = sizeof($mcs);
        for($i = 0; $i<$size; $i++){
            if($mcs[$i]->marketingChannelId !== $_POST["marketingChannel"]){
                unset($mcs[$i]);
            }
        }
    }
    
    $mc = $dao->listAll("MarketingChannel", "clientId", $client->clientId);
    
    $statuses = ["In-Active", "Active"];
    
    
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
                <div class="home-title in-row">
                    <h3><?php echo $dic->translate("Marketing Channels") ?></h3>
                    <?php
                        if(isset($_POST["filter"]) && $_POST["filter"]=='open'){
                    ?>
                        <form id="filter-form" method="post">
                    <?php
                        if(isset($_POST["course"])){
                            echo '<input type="hidden" name="course" value="'.$_POST["course"].'"/>';
                        }
                        if(isset($_POST["status"])){
                            echo '<input type="hidden" name="status" value="'.$_POST["status"].'"/>';
                        }
                    ?>
                            <button id="filter-button" name="filter" value="close">
                                <i class="fas fa-caret-up"></i>
                            </button>
                        </form>
                    <?php  
                        }else{
                    ?>
                        <form id="filter-form" method="post">
                            <button id="filter-button" name="filter" value="open">
                    <?php
                        if(isset($_POST["course"])){
                            echo '<input type="hidden" name="course" value="'.$_POST["course"].'"/>';
                        }
                        if(isset($_POST["status"])){
                            echo '<input type="hidden" name="status" value="'.$_POST["status"].'"/>';
                        }
                    ?>
                                <i class="fas fa-sort-down"></i>
                            </button>
                        </form>
                    <?php
                        }
                    ?>
                    <a href="newmarketingchannel.php"><?php echo $dic->translate("Add New") ?></a>
                </div>
                <?php
                    if(isset($_POST["filter"]) && $_POST["filter"]=='open'){
                        include_once 'content/marketing-filter.php';
                    }
                ?>
                <div class="in-row in-courses-wrapper">
                    <?php
                        foreach($mcs as $m){
                            echo '<div class="in-course-wrapper col2">'
                            . '<div class="in-course">'
                                    . '<i class="fas fa-bullhorn"></i>'
                                    . '<form style="float:left;" action="" method="post">'
                                        . '<input type="hidden" name="marketingChannelId" value="'.$m->marketingChannelId.'"/>'
                                        . '<button style="line-height: 27px;" class="in-button" type="submit">'.$m->marketingChannelName.'</button>'
                                    . '</form>'
                                    . '<form style="float:right;margin-top:10px;" action="editmarketingchannel.php" method="post">'
                                        . '<input type="hidden" name="marketingChannelId" value="'.$m->marketingChannelId.'"/>'
                                        . '<button class="in-button" type="submit"><i style="margin:0;font-size:14px;" class="fas fa-pencil-alt"></i></button>'
                                    . '</form>'
                            . '</div>'
                            . '</div>';
                        }
                    $dao->close();
                    ?>
                </div>
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
</html>