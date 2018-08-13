<?php

require_once($_SERVER['DOCUMENT_ROOT']."/main/database/DAO.php");

class Validator{
    
    public static function isEmpty($string = "", $fieldName = "Input"){
        $string = htmlspecialchars($string);
        if(strlen($string) == 0){
            return $fieldName . " can't be empty!";
        }else{
            return "";
        }
    }
    
    public static function isEmail($email = ""){
        $email = htmlspecialchars($email);
        return (strlen(Validator::isEmpty($email))!=0) ? "Email can't be empty!" : ((!filter_var($email, FILTER_VALIDATE_EMAIL)) ? "The email is invalid!" : "");
    }
    
    public static function isPassword($password = ""){
        $password = htmlspecialchars($password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        return (strlen(Validator::isEmpty($password))!=0) ? "Password can't be empty!" :((!$uppercase || !$lowercase || !$number || strlen($password) < 8) ? "Minimum 8 characters, 1 number, 1 uppercase, 1 lowercase" : "");
    }
   
    public static function isName($name = "", $fieldName = "Name"){
        //$name = htmlspecialchars($name);
        //$uppercase = preg_match('@[A-Z]@', $name);
        //$lowercase = preg_match('@[a-z]@', $name);
        //return (strlen($name) == 0) ? $fieldName . " can't be empty" : ((!$uppercase || !$lowercase) ? $fieldName . " can only contain letters" : "");
        return Validator::isEmpty($name, $fieldName);
    }
    
    public static function isNumber($value = "", $fieldName = "Input"){
        $value = htmlspecialchars($value);
        $fieldName = htmlspecialchars($fieldName);
        $number    = preg_match('@[0-9]@', $value);
        return (strlen($value) == 0) ? $fieldName . " can't be empty" : ((!$number) ? $fieldName . " can only contain numbers" : "");
    }
    
    public static function isCompanyName($companyName = ""){
        $companyName = htmlspecialchars($companyName);
        $dao = new DAO();
        $compExist = ($dao->get("Client", $companyName, "clientCompanyName")->clientId != null) ? true : false;
        $dao->close();
        return (strlen($companyName) == 0) ? "Company name can't be empty" : (($compExist) ? "A user with such company name already exists!" : "") ;
    }
    
    public static function isUserEmail($email = ""){
        $email = htmlspecialchars($email);
        if(strlen($email) == 0) return "Email can't be empty!";
        $dao = new DAO();
        $contact = $dao->get("Contact", $email, "contactEmail");
        $dao->close();
        if(strlen(Validator::isEmail($email)) != 0) return "Email is invalid!";
        if ($contact->contactId == null) return "The user with such email doesn't exist!";
        return "";
    }
    
    
    public static function isNotUserEmail($email = ""){
        $email = htmlspecialchars($email);
        if(strlen($email) == 0) return "Email can't be empty!";
        $dao = new DAO();
        $contact = $dao->get("Contact", $email, "contactEmail");
        $dao->close();
        if(strlen(Validator::isEmail($email)) != 0) return "Email is invalid!";
        if ($contact->contactId != null) return "The user with such email already exists";
        return "";
    }
    
    public static function isUserPassword($email = "", $password = ""){
        $email = htmlspecialchars($email);
        $password = htmlspecialchars($password);
        $dao = new DAO();
        $contact = $dao->get("Contact", $email, contactEmail);
        $user = $dao->get("User", $contact->contactId, "contactId");
        if(strcmp($user->userPassword, md5($password))!=0){
            return "The password is incorrect";
        }
        if(strcmp($user->userActivate, "n") == 0){
            return "You have to activate your account first!";
        }
        
        $dao->close();
    }
    
    public static function isDateStart($start, $end, $schoolStart, $schoolEnd){
        if($start < $schoolStart){
            return "The start date has to be within school year";
        }
        if($start >= $schoolEnd){
            return "The start date has to be withing school year";
        }
        if($start > $end){
            return "The start date has to be before the end date";
        }
        return "";
    }
    
    public static function isDateEnd($start, $end, $schoolStart, $schoolEnd){
        if($end <= $schoolStart){
            return "The end date has to be within school year";
        }
        if($end > $schoolEnd){
            return "The end date has to be within school year";
        }
        if($start > $end){
            return "The end date has to be after the start date";
        }
        return "";
    }
    
    public static function isDateRange($date, $start, $end, $name = "date", $equality = 1){
        $error = "The $name is incorrect";
        if($equality == 1){
            return ($date >= $start && $date < $end) ? "" : $error;
        }else if($equality == 2){
            return ($date <= $end && $date > $start) ? "" : $error;
        }
    }
    
    public static function check(){
        $args = func_get_args();
        foreach($args as $arg){
            if(strlen($arg) != 0){
                return false;
            }
        }
        return true;
    }
    
    
}

