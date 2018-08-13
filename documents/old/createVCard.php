<?php
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Address.php");
function createSingleVCardText($Name, $Surname, $Mobile, $Email, $Address, $Company, $Birthday)
{
	$vcard = "";

        $address = new Address();
        $address->convertToObject($Address);

	$mainPart = $address->firstline.'\,'.$address->secondline.'\,'.$address->thirdline;
	$town = $address->town;
	$region = $address->region;
	$postCode = $address->zip;

	$FullName = $Name.' '.$Surname;

	$vcard .= "BEGIN:VCARD\r";
	$vcard .= "VERSION:3.0\r";
	$vcard .= "N:".$Surname.";".$Name.";;;\r";
	$vcard .= "FN:".$FullName."\r";

	if( !empty($Company) )
	{
		$vcard .= "ORG:".$Company.";\r";
	}
	$vcard .= "EMAIL;TYPE=pref:".$Email."\r";
	$vcard .= "EMAIL:".$Email."\r";
	$vcard .= "TEL;TYPE=work,voice,pref:".$Mobile."\r";
	$vcard .= "TEL;TYPE=cell,voice:".$Mobile."\r";
	$vcard .= "ADR;TYPE=pref:;;".$mainPart.";".$town.";".$region.";".$postCode.";United Kingdom\n";

	if( !( $Birthday === "0000-00-00") )
	{
		$vcard .= "BDAY:".$Birthday."\r";
	}
	$vcard .= "END:VCARD";

	return $vcard;
}

function exportContacts($contacts)
{
        $vcartText = "";
	foreach($contacts as $c){
            $Name = $c->contactFirstName;
            $Surname = $c->contactLastName;
            $Mobile = $c->contactPhone;
            $Email = $c->contactEmail;
            $Address = $c->contactAddress;
            $Company = $c->contactOrganisation;
            $Birthday = $c->contactDOB;

            $vcardText .= createSingleVCardText($Name, $Surname, $Mobile, $Email, $Address, $Company, $Birthday);
            $vcardText .= "\r\r";
        }
        $clientId = $contacts[0]->clientId;
        
        
	$cardFile = fopen("./clients/$clientId/contacts/vcards/allcontacts.vcf", "w") or die("Unable to open file!");
	fwrite($cardFile, $vcardText);
	fclose($cardFile);
}

function exportStudent($contacts){
    $vcartText = "";
    foreach($contacts as $c){
        $Name = $c->contactFirstName;
        $Surname = $c->contactLastName;
        $Mobile = $c->contactPhone;
        $Email = $c->contactEmail;
        $Address = $c->contactAddress;
        $Company = $c->contactOrganisation;
        $Birthday = $c->contactDOB;

        $vcardText .= createSingleVCardText($Name, $Surname, $Mobile, $Email, $Address, $Company, $Birthday);
        $vcardText .= "\r\r";
    }
    $clientId = $contacts[0]->clientId;


    $cardFile = fopen("./clients/$clientId/contacts/vcards/student-parent.vcf", "w") or die("Unable to open file!");
    fwrite($cardFile, $vcardText);
    fclose($cardFile);
}

function createVCard($contact)
{
    
    
$Name = $contact->contactFirstName;
$Surname = $contact->contactLastName;
$Mobile = $contact->contactPhone;
$Email = $contact->contactEmail;
$Address = $contact->contactAddress;
$Company = $contact->contactOrganisation;
$Birthday = $contact->contactDOB;
$clientId = $contact->clientId;

$cardName = 13579 + $contact->contactId;
$filePath = "/main/clients/$clientId/contacts/vcards/$cardName.vcf";


$vcardText = createSingleVCardText($Name,$Surname, $Mobile, $Email, $Address, $Company, $Birthday);

$cardFile = fopen("./clients/$clientId/contacts/vcards/$cardName.vcf", "w") or die("Unable to open file!");


fwrite($cardFile, $vcardText);
fclose($cardFile);
}

