<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
   
    $dao = new DAO();
    include("content/session.php");
    
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
                <div class="in-drive-header col12">
                    <div class="in-fl-l"><?php echo ($dic->translate("Email Templates")) ?></div>
                </div>
                <div class="in-drive-menu col2">
                    <ul>
                        <li class="in-row <?php echo((strcmp("invoice", $_GET["sec"]) == 0)? "in-drive-selected" : "")?>">
                            <i class="in-fl-l fas fa-dollar-sign"></i>
                            <form action="emailtemplates.php?sec=invoice" method="post">
                                <button type="submit"><?php echo $dic->translate("Invoice Text")?></button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="in-drive-files col10" id="drop-area">
                    <?php
                        if(strcmp($_GET["sec"], "invoice")==0){
                            include_once 'content/emailtemplates/invoiceText.php';
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php include("content/footer.php") ?>
    </body>
    <?php     
        $dao->close();
    ?>
</html>
