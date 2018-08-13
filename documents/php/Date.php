<?php

class Date{
    
    public $day;
    public $month;
    public $year;
    private $months = ["01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November" , "12" => "December"];
    
    public function fromInput($date){
        $this->day = substr($date, 8);
        $this->month = substr($date, 5, 2);
        $this->year = substr($date, 0, 4);
    }
    
    public function toString(){
        return $this->day."-".$this->month."-".$this->year;
    }
    //Tue 23, January 2018
    public function periodToDate($date){
        $this->day = substr($date, 4, 2);
        foreach($this->months as $key => $value){
            if(strpos($date, $value)){
                $this->month = $key;
            }
        }
        $this->year = substr($date, strlen($date)-4, 4);
    }
    
    public function toDB(){
        return $this->year."-".$this->month."-".$this->day;
    }
    
    public function longDate($date = ""){
        $longDate = "";
        if(strlen($date) == 0){
            $longDate = date_format(Date_create($this->toDB()), "D jS, F Y");
        }else{
            try{
                $longDate = date_format(Date_create($date), "D jS, F Y");  
            } catch (Exception $e) {
                $longDate = "Wrong date argument must be in: yyyy-mm-dd format";
            }
        }
        return $longDate;
    }
    
    public function getNormalFromDB($date){
        $this->fromInput($date);
        return $this->toString();
    }
    
    public function getLongFromDB($date){
        return $this->longDate($date);
    }
    
    public function fromInputToDB($date){
        if(strcmp($date, "0000-00-00")==0){
            return "";
        }
        $this->periodToDate($date);
        return $this->toDB();
    }
    
    public function fromDBToInput($date){
        if(strcmp($date, "0000-00-00")==0){
            return "";
        }
        return $this->longDate($date);
    }
}
