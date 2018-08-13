<?php

class Student{
    
    public $studentId;
    public $studentSchool;
    public $studentStatus;
    public $contactId;
    public $clientId;
    public $prospectId;
    public $educationLevelId;
    public $branchId;
    public $parentId;
    public $studentQualification;
    
    public function getQualificationArray(){
        if(strcmp($this->studentQualification, "")==0){
            return [];
        }else{
           return(explode(";",$this->studentQualification)); 
        }
    }
    
    public function addQualification($qualification){
        if(strcmp($this->studentQualification, "")==0){
            $this->studentQualification = $qualification;
        }else{
            $this->studentQualification .= ";".$qualification;
        }
    }
    
    public function removeQualification($qualification){
        if(strpos($this->studentQualification, $qualification) == 0){
            $this->studentQualification = substr($this->studentQualification, strlen($qualification)); 
        }else{
            $this->studentQualification = str_replace(";".$qualification, "", $this->studentQualification);
        }
    }
}

