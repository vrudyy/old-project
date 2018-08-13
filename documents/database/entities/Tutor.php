<?php

class Tutor{
    
    public $tutorId;
    public $contactId;
    public $clientId;
    public $tutorStatusId;
    public $tutorApprovedCourses;
    public $tutorRate;
    
    public function getCourseArray(){
        if(strcmp($this->tutorApprovedCourses, "")==0){
            return [];
        }else{
           return(explode(";",$this->tutorApprovedCourses)); 
        }
    }
    
    public function addCourse($course){
        if(strcmp($this->tutorApprovedCourses, "")==0){
            $this->tutorApprovedCourses = $course;
        }else{
            $this->tutorApprovedCourses .= ";".$course;
        }
    }
    
    public function removeCourse($course){
        if(strpos($this->tutorApprovedCourses, $course) == 0){
            $this->tutorApprovedCourses = substr($this->tutorApprovedCourses, strlen($course)); 
        }else{
            $this->tutorApprovedCourses = str_replace(";".$course, "", $this->tutorApprovedCourses);
        }
    }
}

