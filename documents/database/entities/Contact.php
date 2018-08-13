<?php

class Contact{
    
    public $contactId;
    public $contactFirstName;
    public $contactLastName;
    public $contactOrganisation;
    public $contactDOB;
    public $contactEmail;
    public $contactAddress;
    public $contactPhone;
    public $contactLandline;
    public $clientId;
    
    public function fullName(){
        return $this->contactFirstName." ".$this->contactLastName;
    }
}





