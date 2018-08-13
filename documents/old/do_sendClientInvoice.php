<?php
require_once('./PHPMailer/class.phpmailer.php');

include "connect.php";
$invoiceID = $_GET["invoiceID"];

$query = "SELECT * FROM ClientInvoices WHERE id='$invoiceID'";

$result  = mysql_query($query) or die(mysql_error()); 

$row = mysql_fetch_assoc($result);

$invoiceReference = $row['invoiceReference'];
$invoiceAmount = $row['invoiceAmount'];
$invoiceFile = $row['filePath'];
$studentID = $row['studentID'];
$prospectID = $row['prospectID'];

$invoiceUniqueReference = $invoiceFile;
$invoiceUniqueReference = str_replace("./clientInvoices/Phi-Tuition-Ltd_Invoice_","",$invoiceUniqueReference);
$invoiceUniqueReference = str_replace(".pdf","",$invoiceUniqueReference);


if( $studentID > 0 ) //This invoice has been created via the student
{	
	$query2 = "SELECT parentName, parentSurname, parentEmail FROM Students WHERE id = $studentID ";
}
else //This invoice has been created via the prospects
{
	$query2 = "SELECT parentName, parentSurname, parentEmail FROM Prospects WHERE id='$prospectID'";
}

$result2  = mysql_query($query2) or die(mysql_error()); 
$row2 = mysql_fetch_assoc($result2);
$parentName = $row2['parentName'];
$parentSurname = $row2['parentSurname'];
$parentFullName = $parentName." ".$parentSurname;
$parentEmail = $row2['parentEmail'];

//*************
$url = "https://www.paypal.com/cgi-bin/webscr?business=email@phi-tuition.eu&cmd=_xclick&currency_code=GBP&amount=".$invoiceAmount."&item_name=".$invoiceReference;
$icon = "<img src='./img/paypalPayments.png' height='20'>";
$paypalLink= '<a href="'.$url.'">'.$icon.'</a>';

//*************
$mail = new PHPMailer(); // defaults to using php "mail()"

$mail->AddReplyTo("email@phi-tuition.eu", "Phi Tuition Ltd");

$mail->SetFrom("email@phi-tuition.eu", "Phi Tuition Ltd");

$mail->AddAddress($parentEmail, $parentFullName);
$mail->AddAddress("accounts@phi-tuition.eu", "Phi Tuition Accounts");
$mail->AddAddress("email@phi-tuition.eu", "Phi Tuition Ltd");

$mail->Subject    = "Invoice ".$invoiceReference;

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mainMessage ="
    <html>
    <head>
<title>Invoice ".$invoiceReference."</title>
</head>
<body>
Dear ".$parentName.",
<br><br>
Please see below your latest invoice.
    <br><br>
    <a 
    style='background-color: #f44336;
    color: black;
    padding: 14px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block; 
    background-color: orange' 
    href='http://www.phi-tuition.eu/nucleus/viewInvoice.php?id=".$invoiceUniqueReference."'>View Invoice</a>
<br><br>
We would like to thank you in advance for your valued business.
<br><br>
The quickest, easiest and safest way to pay your invoice is with your Debit or Credit card via Paypal:
<br><br>".$paypalLink."
<br><br>
Alternatively, you can pay your invoice via a bank transfer using the bank account number written on the invoice.
<br><br>
For regular monthly instalments, you may consider setting up a Standing Order or a Direct Debit with your bank.
<br><br>
We also accept all the major Credit or Debit Cards, so please give us a call if you wish to pay using a card over the phone.
<br><br>
If you have any further questions, please get in touch with us.
<br><br>
Kind regards,
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

//$mail->AddAttachment($invoiceFile);    

if(!$mail->Send()) 
{
  echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
 $query = "UPDATE ClientInvoices SET isSent = '1' WHERE id='$invoiceID'";
 //echo $query;
 $result  = mysql_query($query) or die(mysql_error()); 	
 echo "<script>
             alert('The email has been sent successfully');
             window.history.go(-1);
        </script>";

}
mysql_close();

?>