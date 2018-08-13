<?php


class Address{

    public $firstline;
    public $secondline;
    public $thirdline;
    public $town;
    public $region;
    public $zip;
    public $country;
    
    public function convertToDB(){
        $properties = (array) $this;
        $string = "";
        foreach ($properties as $property){
            $string .= $property . ";";
        }
        return $string;
    }
    
    public function convertToObject($address){
        $array = explode(";", $address);
        $this->firstline = (isset($array[0])) ? $array[0] : "";
        $this->secondline = (isset($array[1])) ? $array[1] : "";
        $this->thirdline = (isset($array[2])) ? $array[2] : "";
        $this->town = (isset($array[3])) ? $array[3] : "";
        $this->region = (isset($array[4])) ? $array[4] : "";
        $this->zip = (isset($array[5])) ? $array[5] : "";
        $this->country = (isset($array[6])) ? $array[6] : "";
    }

}
