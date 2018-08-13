<?php 

// define the posted file into variables 
$courseID = $_POST["courseID"];
$comment = $_POST["comment"];
$specificationFile = $_FILES['courseSpecificationFile']['name'];
$specificationFile_tmp = $_FILES['courseSpecificationFile']['tmp_name']; 
$specificationFileExt = pathinfo($specificationFile, PATHINFO_EXTENSION); 

$courseSpecPath ="./qualificationFiles/qualificationID_$courseID/Specifications";

$spec = "$courseSpecPath/".$specificationFile;

  if (!file_exists ($courseSpecPath) )
  {
    mkdir("$courseSpecPath");
  }
  
  if( !empty($specificationFile_tmp) ) 
  {
       move_uploaded_file($specificationFile_tmp, $spec);
  }

// connect to the database 
include "connect.php"; 

$query = "INSERT INTO CourseSpecifications (courseID, specificationFile, comment)"."VALUES ('$courseID', '$spec', '$comment')";
                   
//echo $query;
$result  = mysql_query($query) or die(mysql_error()); 
//$result = true;
if($result) { 
echo "<script>
      alert('The specification file was recorded successfully');
      window.close();
     </script>";
} else 
{ 
    echo "alert('The specification file has not been recorded')"; 
}
mysql_close(); 
?>  