<?php 
include('global.php'); 
include "connect.php"; 
?>
<head>
<script type="text/javascript" src="./whizzywig/whizzywig.js"></script>
</head>

<body>
<h4>You can use the space below to communicate with the students for this class. Please use it responsibly adhering to the following guidelines 
to safeguard our students:
<ol>
<li>Please be polite and accurate in your message.</li>  
<li>Please avoid exchanging emails or phone numbers.</li>
<li>Always check spelling and punctuation.</li>
</ol>
</h4>
<hr>
<form name="emailToClass" method="post" action="do_sendEmailToClass.php" enctype="multipart/form-data">
<input type="hidden" name="classID" value="<?=$classID?>">
<input type="hidden" name="sender" value="<?= $fgmembersite->UserFullName();?>">
<b>To:</b>
<?
if ( $fgmembersite->UserRole() == "Tutor")
{
?>	
<label><input type="checkbox" name="sendToStudents" value="sendToStudents" checked disabled>All Students</label>
<?
} 
?>

<?
if ( $fgmembersite->UserRole() == "Manager" || $fgmembersite->UserRole() == "Secretary")
{
?>
<div class="checkbox">
<label><input type="checkbox" name="sendToStudents" value="sendToStudents" checked>All Students</label>
</div>
<div class="checkbox">
<label><input type="checkbox"name="sendToParents" value="sendToParents">All Parents</label>
</div>
<?
}
?>
<b>From:</b>
<br><? echo $fgmembersite->UserFullName();?>
<br>
<b>Cc:</b>
<br>secretariat@phi-tuition.eu, email@phi-tuition.eu
<br>
<b>Subject:</b>
<br> <input type="text" name="subject" style="width:80%;">
<br>
<b>Message:</b>
<textarea id="emailToClass" name="message" row="10" cols="80" style="width:80%; height:300px"> 
</textarea>
<script type="text/javascript">
whizzywig.makeWhizzyWig("emailToClass", "all");
</script>
<button type="submit" class="btn btn-primary">Send message</button>
</form>
</body>