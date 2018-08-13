<?php
    require_once('./PHPMailer/class.phpmailer.php');

    date_default_timezone_set("Europe/London");

    // connect to the database 
    include "connect.php";

    $formID = $_POST['formID'];
    
    $query = "SELECT * FROM TutorContracts WHERE id='$formID' "; 
    $result  = mysql_query($query) or die(mysql_error()); 
    $row = mysql_fetch_assoc($result);

    $tutorID = $row['tutorID'];
    $filePath = $row['filePath'];

    $query = "SELECT * FROM Tutors WHERE id='$tutorID' "; 
    $result  = mysql_query($query) or die(mysql_error()); 

    $row = mysql_fetch_assoc($result);
    
    $tutorEmail = $row['tutorEmail'];

    $tutorName = $row['tutorName'];
    $tutorSurname = $row['tutorSurname'];

    $tutorFullName = $tutorName.' '.$tutorSurname; 
    
    $part1=substr($filePath, 0, strlen($filePath) - 4);
    $documentID = substr($part1,-10)."_".$formID."_".$tutorID;
    
    /**********************/

    $mail = new PHPMailer(); // defaults to using php "mail()"

    $mail->AddReplyTo("email@phi-tuition.eu", "Phi Tuition Ltd");

    $mail->SetFrom("email@phi-tuition.eu", "Phi Tuition Ltd");

    $mail->AddAddress($tutorEmail, $tutorFullName);
    $mail->AddAddress("secretariat@phi-tuition.eu", "Phi Tuition Secretariat");
    $mail->AddAddress("email@phi-tuition.eu", "Phi Tuition Ltd");

    $mail->Subject = "Tutoring agreement for ".$tutorFullName." - Please review";

    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

    $mainMessage =
    "<html>
    <head>
    <title>Tutoring Agreement</title>
    </head>
    <body>
    Dear ".$tutorName.",
    <br><br>
    A very warm welcome to Phi Tuition.
    <br><br>
    I look forward to having you onboard our team of tutors.
    <br><br>
    Please find attached the Tutoring Agreement that summarises the terms of delivering your services to Phi Tuition, to our students and their parents. 
    It also includes the agreed payment fees. 
    <br><br>
    I would appreciate it if you can spare few minutes to review the contract.
    <br><br>
    Once you are happy, please sign it electronically at:
    <br><br>
    <a 
    style='background-color: #f44336;
    color: black;
    padding: 14px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block; 
    background-color: orange' 
    href='http://www.phi-tuition.eu/nucleus/tutorSign.php?id=".$documentID."'>Review and Sign Document</a>
    <br><br>
    If there is a mistake, please do give us a call on 020 3286 3480 or email us at: email@phi-tuition.eu at your convenience.
    <br><br>
    Thank you once again and I shall look forward to hearing back from you. 
    <br><br>
    Dr Stathis Stefanidis
    <br>    
    Founder & Director
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
    </html>
    ";    

    $mail->MsgHTML($mainMessage);

    $mail->AddAttachment($filePath);    

    if(!$mail->Send()) 
    {
      echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
        $query = "UPDATE TutorContracts SET isSent = '1' WHERE id='$formID'";
        //echo $query;
        $result  = mysql_query($query) or die(mysql_error()); 
        echo "<script>
             alert('The email has been sent successfully');
             window.history.go(-1);
        </script>";
    }
    
mysql_close();     
?>