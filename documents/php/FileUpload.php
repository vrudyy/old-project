<?php


class FileUpload{
    
    public static $path = "/var/www/i-nucleus.com/main/clients/";
    
    public static function image($file, $client, $name = "", $size = 50000){
        $uploadOk = 1;
        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $path = (strlen($name) == 0) ? self::$path.$client."/img/".$file["name"] : self::$path.$client."/img/".$name.".".$extension; 
        if(getimagesize($file["tmp_name"]) === false) {
            $uploadOk = 0;
            return false;
        }
        if ($file["size"] > $size) {
            $uploadOk = 0;
            return false;
        }
        if($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif" ) {
            $uploadOk = 0;
            return false;
        }
        if ($uploadOk == 0) {
            return false;
        } else {
            if (!move_uploaded_file($file["tmp_name"], $path)) {
               return false;
            }else{
                return substr($path, 28);
            } 
        }
    }
    
    public static function file($file, $client, $folder, $name = "", $size = 50000){
        $uploadOk = 1;
        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $path = (strlen($name) == 0) ? self::$path.$client."/files/$folder/".$file["name"] : self::$path.$client."/files/$folder/".$name.".".$extension; 
        if(getimagesize($file["tmp_name"]) === false) {
            $uploadOk = 0;
            return false;
        }
        if ($file["size"] > $size) {
            $uploadOk = 0;
            return false;
        }
        /*
        if($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif" ) {
            $uploadOk = 0;
            return false;
        }*/
        if ($uploadOk == 0) {
            return false;
        } else {
            if (!move_uploaded_file($file["tmp_name"], $path)) {
               return false;
            }else{
                return substr($path, 28);
            } 
        }
    }
    
}
