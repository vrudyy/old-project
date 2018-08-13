<?php

class inClass{
    public $classId;
    public $classLabel;
    public $branchId;
    public $roomId;
    public $tutorId;
    public $classAcademicCoordinator;
    public $classAssoicatedCourses;
    public $classStatus;
    public $classIsPrivate;
    public $clientId;
    
    public function getCourseArray(){
        if(strcmp($this->classAssoicatedCourses, "")==0){
            return [];
        }else{
           return(explode(";",$this->classAssoicatedCourses)); 
        }
    }
    
    public function addCourse($course){
        if(strcmp($this->classAssoicatedCourses, "")==0){
            $this->classAssoicatedCourses = $course;
        }else{
            $this->classAssoicatedCourses .= ";".$course;
        }
    }
    
    public function removeCourse($course){
        if(strpos($this->classAssoicatedCourses, $course) == 0){
            $this->classAssoicatedCourses = substr($this->classAssoicatedCourses, strlen($course)); 
        }else{
            $this->classAssoicatedCourses = str_replace(";".$course, "", $this->classAssoicatedCourses);
        }
    }
    
}

