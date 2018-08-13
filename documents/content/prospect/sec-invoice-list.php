<?php
$invoices = $dao->listAll("Invoice", "prospectId", $prospect->prospectId);
for($j = 0; $j<sizeof($invoices); $j++){
    $student = $dao->get("Contact", $prospect->studentId, "contactId");
    $name = $student->contactFirstName." ".$student->contactLastName." 00".($j+1);
    $file = $dao->get("File", $invoices[$j]->fileId, "fileId");
?>
    <div class="in-row in-card-wrapper col3">
        <div class="in-card in-row">
            <div class="in-card-title col10">
                <?php echo($name)?>
            </div>
            <i class="in-card-icon fas fa-file-alt col2"></i>
            <div class="col10 in-card-details">
                <ul class="in-row">
                    <li class="in-row"><a download href="<?php echo(substr($file->fileLocation.$file->fileName, 28))?>"><i style="padding-right: 10px;" class="fas fa-download"></i><?php echo($dic->translate("Download"))?></a></li>
                </ul>
            </div>
        </div>
    </div>
<?php
}

?>