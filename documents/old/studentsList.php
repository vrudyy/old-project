<script>
function refresh(id){
location.href="students.php?studentStatusFilter=" + id ;
}

function setSelectedValue(valueToSelect)
{    
    var element = document.getElementById('studentStatusFilter');
    element.value = valueToSelect;
}
</script>
<?
// connect to the database 
include "connect.php"; 
?>

<?
  if(isset($_GET["studentStatusFilter"]))
  {
    $studentStatusFilter = $_GET["studentStatusFilter"];
  }
  else
  {
    $studentStatusFilter = "Current";
  }
?>

<label for="studentStatus"><b>Student status:</b></label>
  <select name="studentStatusFilter" id="studentStatusFilter" onchange="refresh(this.value)">
  <?  
  
      // query the server
        $query = "SELECT * FROM StudentStatus"; 
        $result  = mysql_query($query) or die(mysql_error()); 

        if($result) 
        {   
          while ($row = mysql_fetch_assoc($result)) 
          {
            $status = $row['studentStatus'];                  
            echo '<option value="'.$status.'">'.$status.'</option>';
          }
          echo '<option value="All">All</option>';
        }

        echo '<option value="" disabled >----School Years---</option>';
        $query = "SELECT * FROM SchoolYears ORDER BY SchoolYear"; 
        $result  = mysql_query($query) or die(mysql_error()); 

        if($result) 
        {   
          while ($row = mysql_fetch_assoc($result)) 
          {
            $schoolYear = $row['SchoolYear'];                  
            echo '<option value="'.$schoolYear.'">'.$schoolYear.'</option>';
          }
        }

        echo '<script> setSelectedValue("'.$studentStatusFilter.'"); </script>';
?> 
</select>
<br>
<table id="studentsTable" border="0" class="table table-striped" data-height="690" data-sort-order="desc">
<thead>
 <tr>
    <th data-field="studentsID" data-align="center" data-sortable="true" data-sorter="hyperlinkSorter">Student's ID</th>
    <th data-field="studentsYear" data-align="center" data-sortable="true" >Student's Year</th>
    <th data-field="studentsName" data-align="center" data-sortable="true" data-sorter="hyperlinkSorter">Student's Name</th>
    <th data-field="studentsSurname" data-align="center" data-sortable="true" data-sorter="hyperlinkSorter">Student's Surname</th>
    <th data-field="studentsMobile" data-align="center" data-sortable="true">Student's Mobile</th>
    <th data-field="studentsEmail" data-align="center" data-sortable="true" data-sorter="hyperlinkSorter">Student's Email</th>
    <th data-field="studentsBranch" data-align="center" data-sortable="true" data-sorter="hyperlinkSorter">Student's Branch</th>
    <th data-field="parentsName" data-align="center" data-sortable="true" data-sorter="hyperlinkSorter">Parent's Name</th>
    <th data-field="parentsMobile" data-align="center" data-sortable="true" >Parent's Mobile</th>
    <th data-field="parentsEmail" data-align="center" data-sortable="true" data-sorter="hyperlinkSorter">Parent's Email</th>
  </tr>
</thead>
<tbody>
<?php

  if ($studentStatusFilter=="" || $studentStatusFilter == "Current")
  {
    $query = "SELECT * FROM Students WHERE StudentStatus='Current' ORDER BY ID "; 
  }
  elseif ($studentStatusFilter=="All")
  {
    $query = "SELECT * FROM Students ORDER BY ID"; 
  }
  elseif ($studentStatusFilter=="Past")
  {
    $query = "SELECT * FROM Students WHERE StudentStatus='Past' ORDER BY ID "; 
  }
  else
  {
    $query = "SELECT * FROM Students WHERE id IN (SELECT studentID FROM StudentsAttendingCourses WHERE schoolYear='$studentStatusFilter') ORDER BY ID";  
  }

  $result  = mysql_query($query) or die(mysql_error()); 

  echo "There are ".mysql_num_rows ($result)." students in the list.";
  echo "<br>";

  $allEmails_Students = "email@phi-tuition.eu";
  $allEmails_Parents = "email@phi-tuition.eu";
  $allEmails = "email@phi-tuition.eu";

  while($row = mysql_fetch_assoc($result))
  {
    if( $row['studentEmail'] != ""  )   
    {
        $allEmails_Students = $allEmails_Students.",".$row['studentEmail'];
        $allEmails = $allEmails.",".$row['studentEmail'];   
    }

    if($row['parentEmail'] != "" || $row['parentEmail'] != "TBC")
    {
      $allEmails_Parents = $allEmails_Parents.",".$row['parentEmail'];
      $allEmails = $allEmails.",".$row['parentEmail'];
    }

   $branchID = $row['branchID'];
   $query = "SELECT branchName FROM Branches WHERE id='$branchID'";
   $result2  = mysql_query($query) or die(mysql_error()); 

   $branchName = "";
   if($result2)
   {
     $row2 = mysql_fetch_assoc($result2);
     $branchName = $row2["branchName"];
   }

   $studentYear = $row['studentYear'];
   if( $studentYear == 0 ) $studentYear = "Other";
   else  $studentYear = "Year-".$row['studentYear'];

   echo "<tr>"; 
   echo "<td>".$row['id']."</td>";  
   echo "<td>".$studentYear."</td>";
   echo "<td>".'<a href="editStudent.php?studentID='.$row['id'].'"> '.$row['studentName'].'</a>'."</td>";
   echo "<td>".'<a href="editStudent.php?studentID='.$row['id'].'"> '.$row['studentSurname'].'</a>'."</td>";
   echo "<td>".'<a href="skype:'.$row['studentMobile'].'?call">'.$row['studentMobile'].'</a>'."</td>";
   echo "<td><a href='mailto:".$row['studentEmail']."'>".$row['studentEmail']."</a></td>";
   echo "<td>".'<a href="editBranch.php?branchID='.$branchID .'"> '.$branchName.'</a>'."</td>";
   echo "<td>".'<a href="editStudent.php?studentID='.$row['id'].'"> '.$row['parentName'].' '.$row['parentSurname'].'</a>'."</td>";
   echo "<td>".'<a href="skype:'.$row['parentMobile'].'?call">'.$row['parentMobile'].'</a>'."</td>";
   echo "<td><a href='mailto:".$row['parentEmail']."'>".$row['parentEmail']."</a></td>";
   echo "</tr>"; 
  }

  $allEmails_Students = str_replace("TBC", '', $allEmails_Students);
  $allEmails_Parents = str_replace("TBC", '', $allEmails_Parents);
  $allEmails = str_replace("TBC", '', $allEmails);

  echo "You can email <a href='mailto:email@phi-tuition.eu?bcc=".$allEmails_Students."'>all the students </a> or <a href='mailto:email@phi-tuition.eu?bcc=".$allEmails_Parents."'>all the parents</a> or even <a href='mailto:email@phi-tuition.eu?bcc=".$allEmails."'>everybody</a> in the list.";
?>
</tbody>
</table>
<p>
<?
if ($studentStatusFilter == "Current")
{
?>
<form name='sendFeedbackForm' method='post' onsubmit='return confirmSendFeedbackForm()' action='do_sendFeedbackForm.php' enctype='multipart/form-data'>
<button type='submit' value='submit' class='btn btn-info pull-right'>Send feedback request to all parents</button>
</form>
<br><br>
<button id="addStudentAnnouncement" type="submit" class="btn btn-primary" onclick="window.open('addStudentAnnouncement.php','','height=500,width=550'); return false;">Add new announcement for students</button> 
<br><br>
<?
}
?>
