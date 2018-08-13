<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/social-buttons.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">

<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>
</head>

<body>

<?
	include "connect.php"; 
  	$query = "SELECT * FROM Qualifications WHERE id=".$_GET["qualificationID"]." "; 
  	$result  = mysql_query($query) or die(mysql_error()); 

  	if($result) 
  	{
    	$row = mysql_fetch_assoc($result);
      $qualificationID = $row["id"];
    	$level = $row["Level"];
    	$subject = $row["Subject"];
    	$examinationBoard = $row["ExaminationBoard"];
	}
?>	
<h3>Add new specification for: <b> <?=$level?> <?=$subject?>  <?=$examinationBoard?></b> </h3>


<form name="addNewCourseSpecification_form" method="post" action="do_addNewCourseSpecification.php" enctype="multipart/form-data">   
<input type="hidden" name="courseID" value="<?=$qualificationID?>">
<br>
<h4>Specification File</h4>
<input type="file" name="courseSpecificationFile"></td>

<br>
<br>
<h4>Comment</h4>
<textarea class="form-control" rows="10" style="width:300px;" name="comment" placeholder="Comment"></textarea>

<br>
<button type="submit" class="btn btn-primary">Add specification</button>
<button type="reset" class="btn btn-mini">Clear fields</button>

</form>

<?php 
include('scripts.php');
?>  
</body>
</html>
