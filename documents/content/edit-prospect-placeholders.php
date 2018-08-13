<?php

$prospect = $dao->get("Prospect", $_POST['prospectId'], 'prospectId');
#$prospect = new Prospect();
$parentContact = $dao->get('Contact', $prospect->parentId, "contactId");
#$parent = new Contact();
$studentContact = $dao->get("Contact", $prospect->studentId, "contactId");
#$student = new Contact();
$address = new Address();
$date = new Date();

$parentFirstName = $parentContact->contactFirstName;
$parentLastName = $parentContact->contactLastName;
$parentEmail = $parentContact->contactEmail;
$parentMobile = $parentContact->contactPhone;
$address->convertToObject($parentContact->contactAddress);
$parentFirstLine = $address->firstline;
$parentSecondLine = $address->secondline;
$parentThirdLine = $address->thirdline;
$parentTown = $address->town;
$parentRegion = $address->region;
$parentZip = $address->zip;

$status = $prospect->prospectStatus;
$branchId = $prospect->branchId;
$marketingChannelId = $prospect->marketingChannelId;

$address->convertToObject($studentContact->contactAddress);
$studentFirstName = $studentContact->contactFirstName;
$studentLastName = $studentContact->contactLastName;
$studentEmail = $studentContact->contactEmail;
$studentMobile = $studentContact->contactPhone;
$studentFirstLine = $address->firstline;
$studentSecondLine = $address->secondline;
$studentThirdLine = $address->thirdline;
$studentTown = $address->town;
$studentRegion = $address->region;
$studentZip = $address->zip;
$studentSchool = "";
$studentDOB = $date->getLongFromDB($studentContact->contactDOB);
