<?php

class Prospect{
    
    public $prospectId;
    public $parentId;
    public $studentId;
    public $prospectStatus;
    public $branchId;
    public $marketingChannelId;
    public $prospectCourses;
    public $clientId;
    
    public function getCourseArray(){
        if(strcmp($this->prospectCourses, "")==0){
            return [];
        }else{
           return(explode(";",$this->prospectCourses)); 
        }
    }
    
    public function addCourse($course){
        if(strcmp($this->prospectCourses, "")==0){
            $this->prospectCourses = $course;
        }else{
            $this->prospectCourses .= ";".$course;
        }
    }
    
    public function removeCourse($course){
        if(strpos($this->prospectCourses, $course) == 0){
            $this->prospectCourses = substr($this->prospectCourses, strlen($course)); 
        }else{
            $this->prospectCourses = str_replace(";".$course, "", $this->prospectCourses);
        }
    }
}

