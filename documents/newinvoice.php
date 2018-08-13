<?php
    
    require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Dictionary.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/main/php/Date.php");
   
    $dao = new DAO();
    include("content/session.php");
    
    $prospect = $dao->get("Prospect", $_POST["prospectId"], "prospectId");
    $parent = $dao->get("Contact", $prospect->parentId, "contactId");
    $student = $dao->get("Contact", $prospect->studentId, "contactId");
    

    
    
   
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            body > div.wrapper > div.content > form > div.in-row.in-invoice > div:nth-child(7) > div{
                float: left;
                width: 100%;
                margin: 20px 0 0 0;
            }
            body > div.wrapper > div.content > form > div.in-row.in-invoice > div:nth-child(7) > div > div.ck.ck-editor__main > div{
                min-height: 150px;
            }
            body > div.wrapper > div.content > form > div.in-row.in-invoice-editText-wrapper > div > div > div.ck.ck-editor__main > div{
                height: 140px;
            }
        </style>
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
                <?php include_once 'content/prospect/sec-invoices.php';?>
            </div>
        </div>
       
        <?php include("content/footer.php") ?>
    </body>
    <script type="text/javascript">
        $(document).ready(function(){
           $('#datepicker1') 
                   .datepicker({
                       format: 'D dd, MM yyyy',
                       startDate: '01 01 2010',
                       endDate:  '12 30 2050'

           });
        });
        $(document).ready(function(){
           $('#datepicker2') 
                   .datepicker({
                       format: 'D dd, MM yyyy',
                       startDate: '01 01 2010',
                       endDate:  '12 30 2050'

           });
        });
        
        
    </script>
    <?php
        #if(isset($_POST["submit"]) && strcmp($_POST["submit"], "save") == 0){
    
        #}
        $dao->close();
    ?>
</html>