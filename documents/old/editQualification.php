<?php include('menu.php');?>

<script type="text/javascript">

function confirmDeleteCourse()
{
  if (confirm('Are you sure you want to delete the course from the database?')) {
     return true;
  } else {
     return false;
  }
}
</script>
   
<body style="margin: 20px; padding: 20px"> 

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
    
    $qualificationDirectory = "./qualificationFiles/qualificationID_".$qualificationID."/";

    echo "<h2>Qualification: ".$level."-".$subject."-".$examinationBoard."</h2>";
  }
?>

<table class="table table-striped" style="width:20%">
  <tr>
    <th>Qualification ID:</th>
    <td> <? echo $qualificationID ?> </td>
  </tr>
</table>

      
<div>
  <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#specification" data-toggle="tab">Specification</a></li>        
        <li><a href="#examsdates" data-toggle="tab">Examination Dates</a></li>
        <li><a href="#pastpapers" data-toggle="tab">Past Papers</a></li>        
  </ul>
  
<div id="my-tab-content" class="tab-content">

<div class="tab-pane fade in active" id="specification">
<table border="0"  class="table table-striped" >
<caption><h3>Specification</h3></caption>

 <tr>
    <th>Specification File</th>
    <th>Comments</th>
  </tr>
<tr>
<?

 $query = "SELECT * FROM CourseSpecifications WHERE courseID=$qualificationID "; 
 $result  = mysql_query($query) or die(mysql_error()); 
 
  while($row = mysql_fetch_assoc($result))
  {
   $spec = $row['specificationFile'];
   echo "<tr>"; 
   echo "<td><a href=".$spec.">".basename($spec)."</a></td>";
   echo "<td>".$row['comment']."</td>";
   echo "</tr>"; 
  }
?>
</table>

<button id="addSpecification" type="submit" class="btn btn-primary" onclick="window.open('addSpecification.php?qualificationID=<?=$qualificationID?>','','height=400,width=600'); return false;">Add Specification</button> 
</div>

<div class="tab-pane fade" id="examsdates">
<table border="0"  class="table table-striped" >
<caption><h3>Examination Dates</h3></caption>
 <tr>
    <th>Exams Date</th>
    <th>Paper Code</th>
    <th>Description</th>
  </tr>
<tr>
<?

 $query = "SELECT * FROM CourseExamsDates WHERE qualificationID=$qualificationID "; 
 $result  = mysql_query($query) or die(mysql_error()); 
 
  while($row = mysql_fetch_assoc($result))
  {
   $examsDate =date_create($row['examsDate']);
   $examsDate = date_format($examsDate, 'l jS F Y');
 
   echo "<tr>"; 
   echo "<td>".$examsDate."</td>";
   echo "<td>".$row['paperCode']."</td>";
   echo "<td>".$row['description']."</td>";
   echo "</tr>"; 
  }
?>
</table>

<button id="addExamsDate" type="submit" class="btn btn-primary" onclick="window.open('addExamsDate.php?qualificationID=<?=$qualificationID?>','','height=400,width=600'); return false;">Add Examination Date</button> 
<br><br>
<form action="createPastPapersRevisionPlan.php" method="post" enctype="multipart/form-data">  
<input type="hidden" name="qualificationID" value="<?=$qualificationID?>">
<button type="submit" class="btn btn-info">Create Course Past Papers Revision Plan</button>
</form>
</div>

<div class="tab-pane fade" id="pastpapers">
<table border="0"  class="table table-striped" >
<caption><h3>Past Papers </h3></caption>
  <?
  $pastpaperDirectory = $qualificationDirectory."PastPapers";
  if( !file_exists($pastpaperDirectory) )
  {
    mkdir($pastpaperDirectory,0777, true);
  }
  $pastpapers = scandir($pastpaperDirectory);
  for( $i=2 ; $i<count($pastpapers); $i++) {
    echo "<tr><td><left>";
    echo " <a href=".$pastpaperDirectory."/".rawurlencode($pastpapers[$i]).">".$pastpapers[$i]."</a>";
    echo "</left></td></tr>";
  }
  ?>
</table>

<form name="addPastPaperFile_form" method="post" action="do_addPastPaperFile.php" enctype="multipart/form-data">   
<input type="hidden" name="qualificationID" value="<?=$qualificationID?>">
<br>
<input type="file" name="pastPaperFile"></td>
<br>
<button type="submit" class="btn btn-primary">Add Past Paper file</button> 
</form>
</div>

</div>
</div>      
 <?
    mysql_close(); 
?>
<br>
<?php include('footer.php');?>