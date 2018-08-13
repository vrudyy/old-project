<?php
require_once('./PHPMailer/class.phpmailer.php');
include("./mpdf60/mpdf.php");
include "connect.php"; 

$dateCreated = date("Y-m-d");

$invoiceHours= $_POST["invoiceHours"];
$tutorID  = $_POST["tutorID"];
$totalFees = $_POST["totalFees"];

$itemDate = $_POST["itemDate"];
$itemDescription = $_POST["itemDescription"];
$itemHours = $_POST["itemHours"];
$itemCharge = $_POST["itemCharge"];
$extraItems = "";
$extraCharges = 0;

$extraItems .= "<br><br><table border='0'  class='table'>
<caption><h3>Extra items claimed</h3></caption>
 <tr>
  <th style='width: 50px;'>Date</th>
  <th style='width: 80px;'>Description</th>
  <th style='width: 200px;'>Hours worked</th>
  <th style='width: 200px;'>Charge</th>
 </tr>";

for($iItem=1; $iItem<=count($itemDate); $iItem++)
{
  $charge = str_replace('£', '', $itemCharge[$iItem]);
  $extraItems .="<tr>";
  $extraItems .="<td>".$itemDate[$iItem]."</td>";
  $extraItems .="<td>".$itemDescription[$iItem]."</td>";
  $extraItems .="<td>".$itemHours[$iItem]."</td>";
  $extraItems .="<td>£".number_format($charge,2)."</td>";
  $extraItems .="</tr>";

  $extraCharges += $charge;
}
$extraItems .= "</table>";

$totalAmount = $extraCharges + $totalFees;
$total = number_format($totalAmount, 2);
$extraItems .="<h4>Total amount claimed: £".$total."</h4>";

$formatBefore = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/social-buttons.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/datepicker.css" rel="stylesheet">
</head>
<body style="margin: 20px; padding: 20px">';

$formatBefore .= 
  "<div style='text-align: right;  padding: 10px;'><img src='./img/PhiTuitionLogo_png_NoBkg.png'></div>
  <div style='text-align: right; padding: 10px;'>
  Building 3 <br>
  Chiswick Park<br>
  566 Chiswick High Road<br>
  London, W4 5YA<br>
  T: +44 (0) 20 3286 3480<br>
  E: email@phi-tuition.eu<br>
  W: www.phi-tuition.eu<br>
  </div>
  <br><br>";

$tutorDetails = "";

$query = "SELECT * FROM Tutors WHERE id=$tutorID"; 
$result  = mysql_query($query) or die(mysql_error()); 
$row = mysql_fetch_assoc($result);

$tutorName = $row["tutorName"];
$tutorSurname = $row["tutorSurname"];
$tutorEmail = $row["tutorEmail"];
$tutorMobile = $row["tutorMobile"];
$bankName = $row["bankName"];
$bankSortCode = $row["bankSortCode"];
$bankAccountNumber = $row["bankAccountNumber"];

$tutorAddress = $row["tutorAddress"];

$tutorAddress = str_replace(";;;", ";", $tutorAddress);
$tutorAddress = str_replace(";;", ";", $tutorAddress);
$tutorAddress = str_replace(";", "\n", $tutorAddress);

$tutorDetais .="<p style='text-align:left'>";
$tutorDetais .="<b>Date: </b>".date('l jS F Y')."<br>";
$tutorDetais .="<br>"; 
$tutorDetais .="<b>Tutor's Details:</b><br>";
$tutorDetais .="<b>Tutor Name: </b>".$tutorName ." ".$tutorSurname."<br>";
$tutorDetais .="<b>Address: </b>".nl2br($tutorAddress)."<br>";
$tutorDetais .="<b>Email: </b>".$tutorEmail."<br>";
$tutorDetais .="<b>Mobile: </b>".$tutorMobile."<br>";
$tutorDetais .="</p>";

$tutorDetais .="<p style='text-align:right'>";
$tutorDetais .="<b>Bank Details:</b><br>";
$tutorDetais .="<b>Bank Name: </b>".$bankName."<br>";
$tutorDetais .="<b>Sort Code: </b>".$bankSortCode."<br>";
$tutorDetais .="<b>Account Number: </b>".$bankAccountNumber."<br>";
$tutorDetais .="</p>";

$formatBefore .= $tutorDetais."<br><br>"; 

$formatAfter = '</body>';

$invoiceHours = $formatBefore.$invoiceHours.$extraItems.$formatAfter;

//if the directory does not exist, create it
$dir = "./tutorFiles/tutorID_".$tutorID."/Invoices/";
if (!file_exists($dir)) 
{
        mkdir($dir,0777,true);         
}
//file Name
$timeStamp = date_timestamp_get(date_create());
$file = "SubmittedInvoice_".$tutorName."_".$tutorSurname."_".$dateCreated."_".$timeStamp.".pdf";
$filePath = $dir.$file;

$mpdf=new mPDF('c'); 
$mpdf->WriteHTML($invoiceHours);
$mpdf->Output($filePath,'F');
//$mpdf->Output();

//record it on the DB
include "connect.php"; 
$filePath = mysql_real_escape_string($filePath);

$query = "INSERT INTO SubmittedInvoices (tutorID, dateCreated, filePath, isPaid, amount)". 
               "VALUES ('$tutorID', '$dateCreated', '$filePath','0','$totalAmount')"; 
//echo $query;
$result  = mysql_query($query) or die(mysql_error()); 
mysql_close();


//*******Send a confirmation email
  $mail = new PHPMailer(); // defaults to using php "mail()"

  $mail->AddReplyTo("email@phi-tuition.eu", "Phi Tuition Ltd");

  $mail->SetFrom("email@phi-tuition.eu", "Phi Tuition Ltd");

  $mail->AddAddress($tutorEmail, $tutorName);
  $mail->AddAddress("accounts@phi-tuition.eu", "Phi Tuition Accounts");
  $mail->AddAddress("email@phi-tuition.eu", "Phi Tuition Ltd");

  $mail->Subject    =  "Confirmation of your submitted invoice";

  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

  $mainMessage =
"<html>
    <head>
    <title>Confirmation of your submitted invoice</title>
    </head>
    <body>
    Dear ".$tutorName.",
    <br><br>
    You have successfully submitted your invoice which is attached to this email.
    <br><br>
    Your invoice will be reviewed by our finance team and we will let you know in case there are mistakes. 
    <br><br>
    You can track the progress from your Nucleus profile.
    <br><br>
    Thank you once again for delivering excellent teaching to our students.
    <br><br>
    The Phi Tuition Team
    <br><br>
    www.phi-tuition.eu | www.facebook.com/phi.tuition | email@phi-tuition.eu
    <br><br>
    Building 3 (For attention of Phi Tuition)
    <br>Chiswick Park
    <br>566 Chiswick High Road
    <br>Chiswick 
    <br>London, W4 5YA 
    <br><br>
    DISCLAIMER
    <br>This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. If you have received this email in error please notify the system manager. This message contains confidential information and is intended only for the individual named. If you are not the named addressee you should not disseminate, distribute or copy this e-mail. Please notify the sender immediately by e-mail if you have received this e-mail by mistake and delete this e-mail from your system. If you are not the intended recipient you are notified that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited.
    <br>
    </body>
    </html>";   

    $mail->MsgHTML($mainMessage);

    $mail->AddAttachment($filePath);    

    if(!$mail->Send()) 
    {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
      
    }
//********
echo "<script>
             alert('The invoice has been submitted successfully');
             window.history.go(-1);
           </script>";            
?>