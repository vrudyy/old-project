<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Sort.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    //checking if the user logged in
    session_start();
    if(!isset($_SESSION["email"])){
        ob_start();
        header('Location: '.'login.php');
        ob_end_flush();
        die();
    }
    
    //initialising the DAO
    $dao = new DAO();
    
    //getting the client and contact of account owner
    $contact = $dao->get("Contact", $_SESSION["email"], "contactEmail");
    $user = $dao->get("User", $contact->contactId, "contactId");
    $client = $dao->get("Client", $user->clientId, "clientId");
    
    if(isset($_POST["schoolyearid"])){
        $schoolYear = $dao->get("SchoolYear", $_POST["schoolyearid"], "schoolYearId");
        $_SESSION["schoolyearid"] = $schoolYear->schoolYearId;
    }else{
        $schoolYear = $dao->get("SchoolYear", $_SESSION["schoolyearid"], "schoolYearId");
    }
    
    $p = $dao->listAll("Period", "schoolYearId", $schoolYear->schoolYearId);
    $sort = new Sort();
    $periods = $sort->sortPeriods($p);
    
    
    //closing connection;
    $dao->close();
    
    //creating a ditionary object
    $dic = new Dictionary($client->clientLanguage);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include("content/head.php") ?>
        <link href="css/gstyle.css" rel="stylesheet" type="text/css"/>
        <script src="./gantt/codebase/dhtmlxgantt.js"></script>
        <script src="./gantt/codebase/ext/dhtmlxgantt_marker.js"></script>  
        <script src="./gantt/codebase/ext/dhtmlxgantt_tooltip.js"></script>
        <link href="./gantt/codebase/dhtmlxgantt.css" rel="stylesheet">
        <link rel="stylesheet" href="./gantt/codebase/skins/dhtmlxgantt_broadway.css?v=20180227">
        <style type="text/css">
            body {
                    font-family: Helvetica, Arial, sans-serif;
                    font-size: 13px;
            }
            .contain {
                    width: 800px;
                    margin: 0 auto;
            }
            h1 {
                    margin: 40px 0 20px 0;
            }
            h2 {
                    font-size: 1.5em;
                    padding-bottom: 3px;
                    border-bottom: 1px solid #DDD;
                    margin-top: 50px;
                    margin-bottom: 25px;
            }
            table th:first-child {
                    width: 150px;
            }
            .gantt *{
                box-sizing: content-box;
            }
            .nav{
                height: 75.8px;
            }
            .nav li{
                height: 44.8px;
                line-height: 26.4px;
            }
            #gantt_here{
                height: 300px;
                float:left;
                border-radius: 0;
                width: 90%;
                margin: 20px 5%;
            }
    </style>
    </head>
    <body style="">
        <div class="wrapper">
            <?php include("content/header.php") ?>
            <?php include("content/nav.php") ?>
            <div class="content">
                <div class="home-title in-row">
                    <h3 style="font-size: 27.04px;"><?php echo $dic->translate("School Year: ").$schoolYear->schoolYearTitle ?></h3>
                    <form action="addperiod.php" method="post">
                        <input id="schoolYearId" type="hidden" name="schoolyearid" value="<?php echo $schoolYear->schoolYearId ?>"/>
                        <button type="submit"><?php echo $dic->translate("Add Period") ?></button>
                    </form>
                </div>
                <div class="in-row vsec" style="border-radius: 0;width: 90%;margin: 20px 5%;">
                    <h3 class="rooms" style="margin-bottom: 0;"><?php echo $dic->translate("School Year Periods") ?></h3>
                    <div class="room in-row roomHead">
                        <h3 class="col4 r b"><?php echo $dic->translate("Period Title") ?></h3>
                        <h3 class="col3 r b"><?php echo $dic->translate("Start Date") ?></h3>
                        <h3 class="col3 b"><?php echo $dic->translate("End Date") ?></h3>
                        <h3 class="col2 b"><?php echo $dic->translate("Number Of Weeks") ?></h3>
                    </div>
                    <?php
                        foreach($periods as $key => $p){
                            if($key % 2 ==0) {
                                echo "<div class=\"per room in-row mg\">";
                            }else{
                                echo "<div class=\"per room in-row ug\">"; 
                            }
                            echo "<form action=\"updateperiod.php\" method=\"post\" class=\"col4 b r\">";
                            echo "<input type=\"hidden\" name=\"schoolyearid\" value=\"$schoolYear->schoolYearId\">";
                            echo "<input type=\"hidden\" name=\"periodid\" value=\"$p->periodId\">";
                            echo "<button type=\"submit\">";    
                            echo $p->periodTitle;    
                            echo "</button>";        
                            echo "</form>";
                            $date = new Date();
                           
                            
                            $date->fromInput($p->periodStart);
                            $p->periodStart = $date->longDate();
                            $datetime1 = new DateTime($date->toDB());
                            $date->fromInput($p->periodEnd);
                            $datetime2 = new DateTime($date->toDB());
                            $p->periodEnd = $date->longDate();
                            
                            echo "<h3 class=\"col3 r b\">".$p->periodStart."</h3>";
                            echo "<h3 class=\"col3 r b\">".$p->periodEnd."</h3>";
                            
                            
                            $interval = $datetime1->diff($datetime2);
                            $weeks = (int)round($interval->format('%a') / 7);
                            echo "<h3 class=\"col2 b\">".$weeks."</h3>";
                            echo "</div>";
                        }
                    ?> 
                </div>
            </div>
            <div class="in-row" style="width: 90%; margin: 0 5%;">
                <input type="radio" id="scale1" name="scale" value="1"/><label for="scale1">Day scale</label>
                <input type="radio" id="scale2" name="scale" value="2"/><label for="scale2">Week scale</label>
                <input type="radio" id="scale3" name="scale" value="3"/><label for="scale3">Month scale</label>
                <input type="radio" id="scale4" name="scale" value="4" checked/><label for="scale4">Year scale</label>
            </div>
            <div id="gantt_here" ></div>
        </div>
        
        <?php include("content/footer.php") ?>
        <script src="javascript/periodGantt.js"></script>
    </body>
</html>